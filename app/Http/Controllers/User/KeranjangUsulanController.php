<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KeranjangUsulan;
use App\Models\MonitoringPengadaan;
use App\Models\Barang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KeranjangUsulanController extends Controller
{
    /**
     * Display cart contents
     */
    public function index()
    {
        $items = KeranjangUsulan::with('barang')
            ->where('user_id', auth()->id())
            ->get();

        return view('user.usulan.cart', compact('items'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        try {
            Log::info('Request data:', $request->all());

            $validatedData = $request->validate([
                'barang_id' => 'required|exists:barang,id_barang',
                'jumlah' => 'required|integer|min:1',
                'keterangan' => 'nullable|string'
            ], [
                'barang_id.required' => 'Barang harus dipilih',
                'barang_id.exists' => 'Barang tidak ditemukan',
                'jumlah.required' => 'Jumlah harus diisi',
                'jumlah.integer' => 'Jumlah harus berupa angka',
                'jumlah.min' => 'Jumlah minimal 1'
            ]);

            // Check if item already exists in cart
            $existingItem = KeranjangUsulan::where('user_id', auth()->id())
                ->where('id_barang', $request->barang_id)
                ->first();

            if ($existingItem) {
                // Update existing item
                $existingItem->jumlah += $request->jumlah;
                $existingItem->save();
            } else {
                // Create new cart item
                KeranjangUsulan::create([
                    'user_id' => auth()->id(),
                    'id_barang' => $request->barang_id,
                    'jumlah' => $request->jumlah,
                    'keterangan' => $request->keterangan ?? null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditambahkan ke keranjang usulan'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in KeranjangUsulanController@add:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'jumlah' => 'required|integer|min:1'
            ]);

            $item = KeranjangUsulan::where('user_id', auth()->id())
                ->findOrFail($id);

            $item->jumlah = $request->jumlah;
            $item->save();

            return response()->json([
                'success' => true,
                'message' => 'Jumlah barang berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui jumlah'
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        try {
            KeranjangUsulan::where('user_id', auth()->id())
                ->findOrFail($id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus dari keranjang'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus barang'
            ], 500);
        }
    }

    /**
     * Get cart count
     */
    public function count()
    {
        $count = KeranjangUsulan::where('user_id', auth()->id())->count();

        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        try {
            KeranjangUsulan::where('user_id', auth()->id())->delete();

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengosongkan keranjang'
            ], 500);
        }
    }

    /**
     * Submit usulan from cart
     */
    public function submit()
    {
        try {
            // Get all items from keranjang
            $items = KeranjangUsulan::with('barang')
                ->where('user_id', Auth::id())
                ->get();

            if ($items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada barang dalam keranjang untuk diajukan'
                ]);
            }

            // Begin transaction
            DB::beginTransaction();

            try {
                // Create monitoring_pengadaan entries
                foreach ($items as $item) {
                    // Log untuk debugging
                    Log::info('Processing item:', [
                        'item_id' => $item->id,
                        'barang' => $item->barang,
                        'id_barang' => $item->id_barang
                    ]);

                    MonitoringPengadaan::create([
                        'user_id' => Auth::id(),
                        'barang_id' => $item->barang->id_barang,
                        'debit' => $item->jumlah,
                        'keterangan' => $item->keterangan,
                        'status' => 'proses',
                        'tanggal' => Carbon::now(),
                    ]);
                }

                // Clear the cart
                KeranjangUsulan::where('user_id', Auth::id())->delete();

                // Commit transaction
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Usulan pengadaan berhasil diajukan'
                ]);

            } catch (\Exception $e) {
                // Rollback transaction on error
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error submitting usulan:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengajukan usulan: ' . $e->getMessage()
            ], 500);
        }
    }
}
