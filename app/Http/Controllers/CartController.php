<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Barang;
use App\Models\Pengambilan;
use App\Models\Monitoring;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:user']);
    }

    /**
     * Display cart items
     */
    public function index()
    {
        try {
            $cartItems = Cart::with('barang')
                ->where('user_id', auth()->id())
                ->orderBy('bidang')
                ->orderBy('created_at')
                ->get();

            // Group cart items by bidang
            $cartByBidang = $cartItems->groupBy('bidang');

            // Debug info
            if (config('app.debug')) {
                Log::info('Cart index accessed', [
                    'user_id' => auth()->id(),
                    'cart_count' => $cartItems->count(),
                    'bidang_count' => $cartByBidang->count(),
                    'is_ajax' => request()->ajax()
                ]);
            }

            // If AJAX request, return partial view
            if (request()->ajax()) {
                return view('user.cart.partials.cart-content', compact('cartByBidang'));
            }

            return view('user.cart.index', compact('cartByBidang'));

        } catch (\Exception $e) {
            Log::error('Cart index error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'error' => 'Gagal memuat keranjang: ' . $e->getMessage()
                ], 500);
            }

            $cartByBidang = collect();
            return view('user.cart.index', compact('cartByBidang'));
        }
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        try {
            // Debug info
            if (config('app.debug')) {
                Log::info('Cart add request from user: ' . auth()->id(), [
                    'request_data' => $request->all(),
                    'user' => auth()->user()->only(['id', 'name', 'role'])
                ]);
            }

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
            Log::error('Cart add error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item
     */
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
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jumlah item berhasil diupdate!'
        ]);
    }

    /**
     * Remove item from cart
     */
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

    /**
     * Clear all cart items
     */
    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengambilan berhasil dikosongkan!'
        ]);
    }

    /**
     * Get cart count
     */
    public function count()
    {
        $count = Cart::where('user_id', auth()->id())->sum('quantity');

        return response()->json(['count' => $count]);
    }

    /**
     * Checkout cart items
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'nama_pengambil' => 'required|string|max:255',
        ]);

        $cartItems = Cart::with('barang')
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada item untuk diambil!'
            ]);
        }

        DB::beginTransaction();

        try {
            // Create pengambilan record
            $pengambilan = Pengambilan::create([
                'nama_pengambil' => $request->nama_pengambil,
            ]);

            // Process each cart item
            foreach ($cartItems as $cartItem) {
                $barang = $cartItem->barang;

                // Check stock availability
                if ($barang->stok < $cartItem->quantity) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi. Stok tersedia: {$barang->stok}");
                }

                // Create monitoring record
                Monitoring::create([
                    'id_pengambilan' => $pengambilan->id_pengambilan,
                    'tanggal' => now(),
                    'bidang' => $cartItem->bidang,
                    'pengambil' => $request->nama_pengambil,
                    'id_barang' => $cartItem->id_barang,
                    'debit' => $cartItem->quantity,
                    'kredit' => 0,
                    'saldo' => $barang->stok - $cartItem->quantity,
                    'keterangan' => $cartItem->keterangan,
                ]);

                // Update barang stock
                $barang->decrement('stok', $cartItem->quantity);
            }

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengambilan ATK berhasil diajukan! ID Pengambilan: ' . $pengambilan->id_pengambilan
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Checkout gagal: ' . $e->getMessage()
            ]);
        }
    }
}
