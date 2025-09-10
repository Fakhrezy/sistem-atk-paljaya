<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonitoringBarang;

class MonitoringBarangController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of monitoring barang
     */
    public function index(Request $request)
    {
        $query = MonitoringBarang::query();

        // Filter by status if provided
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by bidang if provided
        if ($request->bidang) {
            $query->where('bidang', $request->bidang);
        }

        // Filter by jenis_barang if provided
        if ($request->jenis_barang) {
            $query->where('jenis_barang', $request->jenis_barang);
        }

        // Search by nama_barang or nama_pengambil
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_pengambil', 'like', '%' . $request->search . '%');
            });
        }

        $monitoringBarang = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.monitoring-barang.index', compact('monitoringBarang'));
    }

    /**
     * Update status of monitoring barang
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diajukan,diterima'
        ]);

        $monitoringBarang = MonitoringBarang::findOrFail($id);
        $monitoringBarang->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui!'
        ]);
    }

    /**
     * Delete monitoring barang record
     */
    public function destroy($id)
    {
        try {
            $monitoringBarang = MonitoringBarang::findOrFail($id);
            $monitoringBarang->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data monitoring berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data monitoring: ' . $e->getMessage()
            ], 500);
        }
    }
}
