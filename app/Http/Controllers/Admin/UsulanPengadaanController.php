<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\KeranjangUsulan;
use App\Models\Usulan;

class UsulanPengadaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get distinct jenis values from barang table
        $jenisBarang = Barang::distinct()->pluck('jenis')->toArray();

        $query = Barang::query();

        // Apply jenis filter if set
        if ($request->jenis && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        // Apply search filter if set
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('id_barang', 'like', '%' . $request->search . '%');
            });
        }

        // Get items with pagination and per_page setting
        $perPage = $request->per_page ?? 12;
        $barang = $query->orderBy('nama_barang')->paginate($perPage);

        // Calculate available stock for each item
        foreach ($barang as $item) {
            // Get total items in cart for this barang
            $cartQuantity = \App\Models\Cart::where('id_barang', $item->id_barang)->sum('quantity');

            // Calculate available stock
            $item->available_stock = max(0, $item->stok - $cartQuantity);
        }

        return view('admin.usulan.index', compact('barang', 'jenisBarang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'barang_id' => 'required|exists:barangs,id_barang',
                'jumlah' => 'required|integer|min:1',
                'keterangan' => 'required|string|min:10'
            ], [
                'barang_id.required' => 'Barang harus dipilih',
                'barang_id.exists' => 'Barang tidak ditemukan',
                'jumlah.required' => 'Jumlah harus diisi',
                'jumlah.integer' => 'Jumlah harus berupa angka',
                'jumlah.min' => 'Jumlah minimal 1',
                'keterangan.required' => 'Keterangan harus diisi',
                'keterangan.min' => 'Keterangan minimal 10 karakter'
            ]);

            $usulan = new Usulan();
            $usulan->user_id = auth()->id();
            $usulan->id_barang = $request->barang_id;
            $usulan->jumlah = $request->jumlah;
            $usulan->keterangan = $request->keterangan;
            $usulan->status = 'pending';
            $usulan->save();

            return response()->json([
                'success' => true,
                'message' => 'Usulan pengadaan berhasil diajukan'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengajukan usulan'
            ], 500);
        }
    }
}
