<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengambilan;
use App\Models\Barang;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengambilanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Barang::query();
        $perPage = $request->input('per_page', 12);

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        // Order by nama barang
        $query->orderBy('nama_barang', 'asc');

        $barang = $query->paginate($perPage);

        // Calculate available stock (actual stock minus items in cart)
        foreach ($barang as $item) {
            $cartQuantity = Cart::where('id_barang', $item->id_barang)
                ->sum('quantity');

            $item->available_stock = $item->stok - $cartQuantity;

            // Make sure available stock doesn't go below 0
            $item->available_stock = max(0, $item->available_stock);
        }

        // Get distinct jenis for filter dropdown
        $jenisBarang = Barang::distinct()
            ->pluck('jenis')
            ->sort();

        return view('admin.pengambilan.index', compact('barang', 'jenisBarang'));
    }

    public function show($id)
    {
        $pengambilan = Pengambilan::with(['barang', 'user'])->findOrFail($id);
        return view('admin.pengambilan.show', compact('pengambilan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $pengambilan = Pengambilan::with(['barang', 'user'])->findOrFail($id);

            // Update pengambilan status
            $pengambilan->status = $request->status;
            $pengambilan->catatan_admin = $request->catatan;
            $pengambilan->processed_at = now();
            $pengambilan->processed_by = auth()->id();

            // If rejected, return items to stock
            if ($request->status === 'rejected') {
                $barang = Barang::find($pengambilan->id_barang);
                if ($barang) {
                    $barang->increment('stok', $pengambilan->jumlah);
                }
            }

            $pengambilan->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status pengambilan berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current available stock for a specific item
     */
    public function getStock(Barang $barang)
    {
        $cartQuantity = Cart::where('id_barang', $barang->id_barang)
            ->sum('quantity');

        $availableStock = max(0, $barang->stok - $cartQuantity);

        return response()->json([
            'available_stock' => $availableStock,
            'original_stock' => $barang->stok,
            'cart_quantity' => $cartQuantity
        ]);
    }
}
