<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonitoringBarang;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

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
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_pengambil', 'like', '%' . $request->search . '%');
            });
        }

        $monitoringBarang = $query->orderBy('created_at', 'desc')->paginate(15);

        // Sync saldo dengan stok barang terkini untuk status 'diajukan'
        $this->syncSaldoWithCurrentStock();

        return view('admin.monitoring-barang.index', compact('monitoringBarang'));
    }

    /**
     * Update status of monitoring barang
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diajukan,diproses,diterima,ditolak'
        ]);

        try {
            DB::beginTransaction();

            $monitoringBarang = MonitoringBarang::findOrFail($id);
            $currentStatus = $monitoringBarang->status;
            $newStatus = $request->status;

            $barang = Barang::findOrFail($monitoringBarang->id_barang);

            // If status is being changed to 'diterima', reduce the stock
            if ($newStatus === 'diterima' && $currentStatus !== 'diterima') {
                // Recheck stock availability
                if ($barang->stok < $monitoringBarang->kredit) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi. Stok tersedia: {$barang->stok}");
                }

                // Update the stock (reduce)
                $barang->decrement('stok', $monitoringBarang->kredit);

                // Update saldo_akhir in monitoring record
                $monitoringBarang->saldo_akhir = $barang->stok;
            }

            // If status is being changed from 'diterima' to 'diajukan', restore the stock
            if ($currentStatus === 'diterima' && $newStatus === 'diajukan') {
                // Return the stock (add back)
                $barang->increment('stok', $monitoringBarang->kredit);

                // Update saldo_akhir in monitoring record to reflect new stock
                $monitoringBarang->saldo_akhir = $barang->stok;
            }

            // Update the status
            $monitoringBarang->status = $newStatus;
            $monitoringBarang->save();

            DB::commit();

            // Generate appropriate success message based on status change
            $message = 'Status berhasil diperbarui!';
            if ($currentStatus === 'diterima' && $newStatus === 'diajukan') {
                $message = 'Status dikembalikan ke diajukan dan stok berhasil dipulihkan!';
            } elseif ($newStatus === 'diterima' && $currentStatus !== 'diterima') {
                $message = 'Status diterima dan stok berhasil dikurangi!';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified monitoring barang
     */
    public function edit($id)
    {
        try {
            $monitoringBarang = MonitoringBarang::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $monitoringBarang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data monitoring tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified monitoring barang in storage
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kredit' => 'required|numeric|min:0'
        ]);

        try {
            $monitoringBarang = MonitoringBarang::findOrFail($id);

            // Hanya update field kredit
            $monitoringBarang->update([
                'kredit' => $request->kredit
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kredit berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kredit: ' . $e->getMessage()
            ], 500);
        }
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

    /**
     * Sync saldo in monitoring barang with current stock from barang table
     */
    private function syncSaldoWithCurrentStock()
    {
        // Update saldo untuk semua monitoring barang dengan status 'diajukan'
        // berdasarkan stok terkini dari tabel barang
        DB::statement("
            UPDATE monitoring_barang mb
            INNER JOIN barang b ON mb.id_barang = b.id_barang
            SET mb.saldo = b.stok,
                mb.saldo_akhir = b.stok - mb.kredit
            WHERE mb.status = 'diajukan'
        ");
    }
}
