<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengambilan;
use App\Models\Barang;
use App\Models\Cart;
use App\Models\MonitoringBarang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengambilanAdminController extends Controller
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

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'quantity' => 'required|integer|min:1',
            'nama_pengambil' => 'required|string|max:255',
            'bidang' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($request->id_barang);

            // Check stock availability
            if ($barang->stok < $request->quantity) {
                throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi. Stok tersedia: {$barang->stok}");
            }

            // Simpan saldo awal (stok sekarang) untuk monitoring
            $saldo = $barang->stok;
            $kredit = $request->quantity;
            $saldo_akhir = $saldo; // Tidak mengurangi stok dulu karena masih diajukan

            // Create monitoring_barang record
            MonitoringBarang::create([
                'id_barang' => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'jenis_barang' => $barang->jenis,
                'nama_pengambil' => $request->nama_pengambil,
                'bidang' => $request->bidang,
                'tanggal_ambil' => now()->toDateString(),
                'saldo' => $saldo,
                'saldo_akhir' => $saldo_akhir,
                'kredit' => $kredit,
                'status' => 'diajukan'
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pengambilan barang berhasil diajukan dan menunggu persetujuan!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Pengambilan gagal: ' . $e->getMessage()
            ], 400);
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
