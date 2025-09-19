<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Pengambilan;
use App\Models\Barang;
use App\Models\MonitoringBarang;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartByBidang = Cart::with(['barang'])
            ->where('user_id', auth()->id())
            ->get()
            ->groupBy('bidang');

        if ($request->ajax()) {
            return view('admin.cart.partials.cart-content', compact('cartByBidang'))->render();
        }

        return view('admin.cart.index', compact('cartByBidang'));
    }

    public function add(Request $request)
    {
        try {
            $request->validate([
                'id_barang' => 'required|exists:barang,id_barang',
                'quantity' => 'required|integer|min:1',
                'bidang' => 'required|string',
                'keterangan' => 'nullable|string',
                'pengambil' => 'required|string|max:255',
            ]);

            $barang = Barang::where('id_barang', $request->id_barang)->first();

            if (!$barang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barang tidak ditemukan'
                ]);
            }

            // Check if stock is sufficient
            if ($barang->stok < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $barang->stok
                ]);
            }

            // Check if item already exists in cart for the same bidang
            $existingCart = Cart::where('user_id', auth()->id())
                ->where('id_barang', $request->id_barang)
                ->where('bidang', $request->bidang)
                ->first();

            if ($existingCart) {
                // If same item + same bidang exists, update quantity
                $newQuantity = $existingCart->quantity + $request->quantity;

                if ($barang->stok < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Total quantity akan melebihi stok. Stok tersedia: ' . $barang->stok . ', sudah di cart untuk bidang "' . $request->bidang . '": ' . $existingCart->quantity
                    ]);
                }

                $existingCart->update([
                    'quantity' => $newQuantity,
                    'keterangan' => $request->keterangan,
                    'pengambil' => $request->pengambil,
                ]);

                $message = 'Item berhasil diupdate untuk pengambilan bidang "' . $request->bidang . '"! Total: ' . $newQuantity;
            } else {
                // Check total quantity across all bidang for this item
                $totalExistingQuantity = Cart::where('user_id', auth()->id())
                    ->where('id_barang', $request->id_barang)
                    ->sum('quantity');

                if ($barang->stok < ($totalExistingQuantity + $request->quantity)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Total quantity akan melebihi stok. Stok tersedia: ' . $barang->stok . ', sudah di cart (semua bidang): ' . $totalExistingQuantity
                    ]);
                }

                Cart::create([
                    'user_id' => auth()->id(),
                    'id_barang' => $request->id_barang,
                    'quantity' => $request->quantity,
                    'bidang' => $request->bidang,
                    'keterangan' => $request->keterangan,
                    'pengambil' => $request->pengambil,
                    'jenis_barang' => $barang->jenis, // Simpan jenis barang dari tabel barang
                ]);

                $message = 'Item berhasil ditambahkan untuk pengambilan bidang "' . $request->bidang . '"!';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Cart $cart)
    {
        // Check if cart belongs to authenticated user
        if ($cart->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $barang = $cart->barang;

        if ($barang->stok < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $barang->stok
            ]);
        }

        $cart->update([
            'quantity' => $request->quantity,
            'jenis_barang' => $barang->jenis, // Update jenis barang dari tabel barang
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jumlah item berhasil diupdate!'
        ]);
    }

    public function remove(Cart $cart)
    {
        // Check if cart belongs to authenticated user
        if ($cart->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari daftar pengambilan!'
        ]);
    }

    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengambilan berhasil dikosongkan!'
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'bidang' => 'nullable|string' // bidang bisa dikirim dari frontend untuk checkout bidang tertentu
        ]);

        $query = Cart::with('barang')->where('user_id', auth()->id());

        // Jika ada bidang yang dipilih, filter hanya bidang tersebut
        if ($request->bidang) {
            $query->where('bidang', $request->bidang);
        }

        $cartItems = $query->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada item untuk diambil!'
            ]);
        }

        // Validasi bahwa semua cart item memiliki nama pengambil
        $itemsWithoutPengambil = $cartItems->filter(function($item) {
            return empty($item->pengambil);
        });

        if ($itemsWithoutPengambil->isNotEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Semua item harus memiliki nama pengambil. Silakan edit item yang belum memiliki nama pengambil.'
            ]);
        }

        DB::beginTransaction();

        try {
            // Process each cart item
            foreach ($cartItems as $cartItem) {
                $barang = $cartItem->barang;

                // Check stock availability
                if ($barang->stok < $cartItem->quantity) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi. Stok tersedia: {$barang->stok}");
                }

                // Simpan saldo awal (stok sekarang) untuk monitoring
                $saldo = $barang->stok;
                $kredit = $cartItem->quantity;
                $saldo_akhir = $saldo; // Tidak mengurangi stok dulu karena masih diajukan

                // Create monitoring_barang record
                MonitoringBarang::create([
                    'id_barang' => $barang->id_barang,
                    'nama_barang' => $barang->nama_barang,
                    'jenis_barang' => $barang->jenis,
                    'nama_pengambil' => $cartItem->pengambil,
                    'bidang' => $cartItem->bidang,
                    'tanggal_ambil' => now()->toDateString(),
                    'saldo' => $saldo,
                    'saldo_akhir' => $saldo_akhir,
                    'kredit' => $kredit,
                    'status' => 'diajukan'
                ]);
            }

            // Clear cart items that were processed
            $cartIds = $cartItems->pluck('id');
            Cart::whereIn('id', $cartIds)->delete();

            DB::commit();

            $messageDetail = $request->bidang
                ? "untuk bidang " . ucfirst($request->bidang)
                : "semua bidang";

            return response()->json([
                'success' => true,
                'message' => "Pengambilan ATK {$messageDetail} berhasil diajukan dan sedang menunggu persetujuan!"
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Checkout gagal: ' . $e->getMessage()
            ]);
        }
    }

    public function count()
    {
        $count = Cart::where('user_id', auth()->id())->sum('quantity');

        return response()->json([
            'count' => $count
        ]);
    }
}
