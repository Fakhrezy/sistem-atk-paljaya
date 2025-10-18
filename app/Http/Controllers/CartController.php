<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Barang;
use App\Models\Pengambilan;
use App\Models\Monitoring;
use App\Models\MonitoringBarang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:user,admin']);
    }

    /**
     * Get view prefix based on current route
     */
    private function getViewPrefix()
    {
        return request()->is('admin/*') ? 'admin' : 'user';
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

            // Determine view prefix based on route
            $viewPrefix = $this->getViewPrefix();

            // Debug info
            if (config('app.debug')) {
                Log::info('Cart index accessed', [
                    'user_id' => auth()->id(),
                    'user_role' => auth()->user()?->role,
                    'view_prefix' => $viewPrefix,
                    'cart_count' => $cartItems->count(),
                    'bidang_count' => $cartByBidang->count(),
                    'is_ajax' => request()->ajax()
                ]);
            }

            // If AJAX request, return partial view
            if (request()->ajax()) {
                return view("{$viewPrefix}.cart.partials.cart-content", compact('cartByBidang'));
            }

            return view("{$viewPrefix}.cart.index", compact('cartByBidang'));
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

            // Determine view prefix for error case
            $viewPrefix = $this->getViewPrefix();
            $cartByBidang = collect();
            return view("{$viewPrefix}.cart.index", compact('cartByBidang'));
        }
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        try {
            // Enhanced debug info
            Log::info('Cart add request received', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()?->role,
                'request_url' => $request->fullUrl(),
                'request_method' => $request->method(),
                'request_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            // Check authentication first
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
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
                    'jenis_barang' => $barang->jenis, // Simpan jenis barang dari tabel barang
                ]);

                $message = 'Item berhasil ditambahkan untuk pengambilan bidang "' . $request->bidang . '"!';
            }

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditambahkan ke keranjang'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Cart add validation error', [
                'user_id' => auth()->id(),
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', collect($e->errors())->flatten()->toArray()),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Cart add error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal: ' . $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ] : null
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
            'bidang' => 'required|string',
            'pengambil' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $barang = $cart->barang;

        if ($barang->stok < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $barang->stok
            ]);
        }

        // Check if another cart item exists with the same barang and bidang (excluding current cart)
        $existingCart = Cart::where('user_id', auth()->id())
            ->where('id_barang', $cart->id_barang)
            ->where('bidang', $request->bidang)
            ->where('id', '!=', $cart->id)
            ->first();

        if ($existingCart) {
            return response()->json([
                'success' => false,
                'message' => 'Item dengan barang yang sama sudah ada di bidang "' . $request->bidang . '". Silakan hapus salah satu atau edit yang sudah ada.'
            ]);
        }

        $cart->update([
            'quantity' => $request->quantity,
            'bidang' => $request->bidang,
            'pengambil' => $request->pengambil,
            'keterangan' => $request->keterangan,
            'jenis_barang' => $barang->jenis, // Update jenis barang dari tabel barang
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item keranjang berhasil diupdate!'
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
        $itemsWithoutPengambil = $cartItems->filter(function ($item) {
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

                // Create monitoring_barang record using create method instead of raw SQL
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
}
