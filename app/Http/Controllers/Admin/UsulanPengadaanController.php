<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\KeranjangUsulan;
use App\Models\Usulan;
use App\Models\UsulanPengadaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsulanPengadaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();
        $jenisBarang = Barang::distinct('jenis')->pluck('jenis');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('jenis', 'like', "%{$search}%");
            });
        }

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis', $request->jenis);
        }

        // Calculate available stock
        $query->selectRaw('barang.*,
            (COALESCE(masuk.total_masuk, 0) - COALESCE(keluar.total_keluar, 0)) as available_stock')
            ->leftJoin(DB::raw('(SELECT id_barang, SUM(saldo) as total_masuk FROM monitoring_barang GROUP BY id_barang) as masuk'),
                'barang.id_barang', '=', 'masuk.id_barang')
            ->leftJoin(DB::raw('(SELECT id_barang, SUM(kredit) as total_keluar FROM monitoring_barang GROUP BY id_barang) as keluar'),
                'barang.id_barang', '=', 'keluar.id_barang');

        // Get items per page from request, default to 12
        $perPage = $request->get('per_page', 12);
        $barang = $query->paginate($perPage);

        return view('admin.usulan-pengadaan.index', compact('barang', 'jenisBarang'));
    }

    public function cartIndex()
    {
        $cartItems = KeranjangUsulan::with('barang')
            ->where('user_id', Auth::id())
            ->get();

        return view('admin.usulan-pengadaan.cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id_barang',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:1000'
        ]);

        // Check if item already in cart
        $existingItem = KeranjangUsulan::where('user_id', Auth::id())
            ->where('id_barang', $request->barang_id)
            ->first();

        if ($existingItem) {
            $existingItem->jumlah = $request->jumlah;
            $existingItem->keterangan = $request->keterangan;
            $existingItem->save();
        } else {
            KeranjangUsulan::create([
                'user_id' => Auth::id(),
                'id_barang' => $request->barang_id,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan ke keranjang'
        ]);
    }

    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:1000'
        ]);

        $cartItem = KeranjangUsulan::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->update([
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diperbarui'
        ]);
    }

    public function removeFromCart($id)
    {
        $cartItem = KeranjangUsulan::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus dari keranjang'
        ]);
    }

    public function cartCount()
    {
        $count = KeranjangUsulan::where('user_id', Auth::id())->count();
        return response()->json(['count' => $count]);
    }

    public function submitCart(Request $request)
    {
        // Get all cart items for the user
        $cartItems = KeranjangUsulan::with('barang')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong'
            ], 400);
        }

        DB::beginTransaction();
        try {
            foreach ($cartItems as $item) {
                UsulanPengadaan::create([
                    'nama_barang' => $item->barang->nama_barang,
                    'jenis' => $item->barang->jenis,
                    'jumlah' => $item->jumlah,
                    'satuan' => $item->barang->satuan,
                    'status' => 'menunggu',
                    'keterangan' => $item->keterangan,
                    'user_id' => Auth::id(),
                    'bidang' => Auth::user()->bidang ?? 'admin'
                ]);
            }

            // Clear the cart
            KeranjangUsulan::where('user_id', Auth::id())->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usulan pengadaan berhasil disubmit'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses usulan'
            ], 500);
        }
    }

    public function show($id)
    {
        $usulan = UsulanPengadaan::with(['user'])->findOrFail($id);
        return view('admin.usulan-pengadaan.show', compact('usulan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diproses,diterima,ditolak',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $usulan = UsulanPengadaan::findOrFail($id);
        $usulan->status = $request->status;
        $usulan->keterangan = $request->keterangan;
        $usulan->processed_by = Auth::id();
        $usulan->processed_at = now();
        $usulan->save();

        return response()->json([
            'success' => true,
            'message' => 'Status usulan berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $usulan = UsulanPengadaan::findOrFail($id);
        $usulan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usulan berhasil dihapus'
        ]);
    }
}
