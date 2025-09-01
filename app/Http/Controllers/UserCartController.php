<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Barang;
use App\Models\Monitoring;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserCartController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:user']);
    }

    /**
     * Display the cart
     */
    public function index()
    {
        $cartItems = Cart::with('barang')
            ->forUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.cart.index', compact('cartItems'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'quantity' => 'required|integer|min:1',
            'bidang' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $barang = Barang::where('id_barang', $request->id_barang)->first();

        // Check if requested quantity is available
        if ($request->quantity > $barang->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah yang diminta melebihi stok tersedia (' . $barang->stok . ')'
            ], 400);
        }

        // Check if item already in cart
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('id_barang', $request->id_barang)
            ->first();

        if ($existingCart) {
            // Update quantity
            $newQuantity = $existingCart->quantity + $request->quantity;

            if ($newQuantity > $barang->stok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total jumlah dalam keranjang akan melebihi stok tersedia'
                ], 400);
            }

            $existingCart->update([
                'quantity' => $newQuantity,
                'bidang' => $request->bidang,
                'keterangan' => $request->keterangan,
            ]);
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'id_barang' => $request->id_barang,
                'quantity' => $request->quantity,
                'bidang' => $request->bidang,
                'keterangan' => $request->keterangan,
            ]);
        }

        $cartCount = Cart::getTotalItemsForUser(Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan ke keranjang',
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, Cart $cart)
    {
        // Ensure cart belongs to authenticated user
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check stock availability
        if ($request->quantity > $cart->barang->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok tersedia (' . $cart->barang->stok . ')'
            ], 400);
        }

        $cart->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Kuantitas berhasil diupdate'
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Cart $cart)
    {
        // Ensure cart belongs to authenticated user
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $cart->delete();

        $cartCount = Cart::getTotalItemsForUser(Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Barang dihapus dari keranjang',
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        Cart::forUser(Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan',
            'cart_count' => 0
        ]);
    }

    /**
     * Checkout - process all cart items
     */
    public function checkout(Request $request)
    {
        $cartItems = Cart::with('barang')->forUser(Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')
                ->with('error', 'Keranjang kosong');
        }

        // Validate all items have sufficient stock
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->barang->stok) {
                return redirect()->route('user.cart.index')
                    ->with('error', "Stok {$item->barang->nama_barang} tidak mencukupi");
            }
        }

        DB::transaction(function () use ($cartItems) {
            foreach ($cartItems as $item) {
                // Create monitoring record
                $lastSaldo = Monitoring::where('id_barang', $item->id_barang)
                    ->orderBy('created_at', 'desc')
                    ->value('saldo') ?? $item->barang->stok;

                Monitoring::create([
                    'tanggal' => now(),
                    'bidang' => $item->bidang,
                    'pengambil' => Auth::user()->name,
                    'id_barang' => $item->id_barang,
                    'debit' => 0,
                    'kredit' => $item->quantity,
                    'saldo' => $lastSaldo - $item->quantity,
                    'keterangan' => $item->keterangan ?? 'Pengambilan barang melalui keranjang oleh ' . Auth::user()->name,
                ]);

                // Update stock
                $item->barang->decrement('stok', $item->quantity);
            }

            // Clear cart after successful checkout
            Cart::forUser(Auth::id())->delete();
        });

        return redirect()->route('user.cart.index')
            ->with('success', 'Pengambilan barang berhasil diproses');
    }

    /**
     * Get cart count for AJAX
     */
    public function count()
    {
        $count = Cart::getTotalItemsForUser(Auth::id());

        return response()->json(['count' => $count]);
    }
}
