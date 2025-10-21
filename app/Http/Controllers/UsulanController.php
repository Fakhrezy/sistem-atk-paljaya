<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Usulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsulanController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();

        // Filter berdasarkan jenis
        if ($request->has('jenis') && $request->jenis != 'semua') {
            $query->where('jenis', $request->jenis);
        }

        // Pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('id_barang', 'like', "%{$search}%");
            });
        }

        $barangs = $query->paginate(12);

        return view('user.usulan.index', [
            'barangs' => $barangs,
            'request' => $request
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id_barang',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $usulan = Usulan::create([
                'user_id' => auth()->id(),
                'barang_id' => $request->barang_id,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
                'status' => 'pending'
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengadaan berhasil dicatat'
                ]);
            }

            return redirect()->route('user.usulan.index')
                        ->with('success', 'Pengadaan berhasil dicatat');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengajukan usulan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                        ->with('error', 'Terjadi kesalahan saat mengajukan usulan');
        }
    }
}
