<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonitoringPengadaan;

class MonitoringPengadaanController extends Controller
{
    public function index(Request $request)
    {
        $query = MonitoringPengadaan::with(['barang'])
            ->orderBy('created_at', 'desc');

        // Filter pencarian berdasarkan nama barang
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('barang', function ($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan jenis barang
        if ($request->filled('jenis')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('jenis', $request->jenis);
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengadaans = $query->get();

        return view('admin.monitoring-pengadaan.index', compact('pengadaans'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:proses,terima'
        ]);

        $pengadaan = MonitoringPengadaan::findOrFail($id);
        $pengadaan->status = $request->status;
        $pengadaan->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui'
        ]);
    }
}
