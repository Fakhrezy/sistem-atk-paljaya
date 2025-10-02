<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonitoringPengadaan;
use App\Models\MonitoringBarang;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Sync saldo_akhir dengan stok barang terkini
        $this->syncSaldoAkhirWithCurrentStock();

        return view('admin.monitoring-pengadaan.index', compact('pengadaans'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:proses,selesai'
        ]);

        try {
            DB::beginTransaction();

            $pengadaan = MonitoringPengadaan::with('barang')->findOrFail($id);
            $oldStatus = $pengadaan->status;
            $newStatus = $request->status;
            $jumlahPengadaan = $pengadaan->debit;

            // Update barang stock based on status change
            if ($oldStatus === 'proses' && $newStatus === 'selesai') {
                // When completing pengadaan, increase stock
                $pengadaan->barang->stok += $jumlahPengadaan;
                $pengadaan->barang->save();

                // Update saldo_akhir di monitoring pengadaan
                $pengadaan->saldo_akhir = $pengadaan->barang->stok;

                // Update saldo di tabel monitoring barang yang belum diterima
                $this->updateMonitoringBarangSaldo($pengadaan->barang->id_barang, $pengadaan->barang->stok);

                $message = 'Pengadaan berhasil diselesaikan, stok barang telah ditambahkan, dan saldo monitoring diperbarui';
            } elseif ($oldStatus === 'selesai' && $newStatus === 'proses') {
                // When reverting completion, decrease stock
                if ($pengadaan->barang->stok < $jumlahPengadaan) {
                    throw new \Exception('Stok tidak mencukupi untuk pembatalan. Pastikan stok barang cukup.');
                }
                $pengadaan->barang->stok -= $jumlahPengadaan;
                $pengadaan->barang->save();

                // Reset saldo_akhir karena kembali ke proses
                $pengadaan->saldo_akhir = $pengadaan->barang->stok;

                // Update saldo di tabel monitoring barang yang belum diterima
                $this->updateMonitoringBarangSaldo($pengadaan->barang->id_barang, $pengadaan->barang->stok);

                $message = 'Status pengadaan dikembalikan ke proses, stok barang telah dikurangi, dan saldo monitoring diperbarui';
            }

            // Update pengadaan status
            $pengadaan->status = $newStatus;
            $pengadaan->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $pengadaan = MonitoringPengadaan::with('barang')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $pengadaan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'debit' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $pengadaan = MonitoringPengadaan::with('barang')->findOrFail($id);
            $oldDebit = $pengadaan->debit;
            $newDebit = $request->debit;
            $diffDebit = $newDebit - $oldDebit;

            // Jika status sudah 'selesai', perlu update stok barang
            if ($pengadaan->status === 'selesai') {
                $barang = $pengadaan->barang;

                // Jika pengurangan debit, pastikan stok mencukupi
                if ($diffDebit < 0 && $barang->stok < abs($diffDebit)) {
                    throw new \Exception('Stok tidak mencukupi untuk pengurangan jumlah pengadaan.');
                }

                // Update stok barang
                $barang->stok += $diffDebit;
                $barang->save();
            }

            // Update data pengadaan
            $pengadaan->debit = $newDebit;
            $pengadaan->keterangan = $request->keterangan;
            $pengadaan->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data pengadaan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pengadaan = MonitoringPengadaan::with('barang')->findOrFail($id);

            // Jika status selesai, kembalikan stok
            if ($pengadaan->status === 'selesai') {
                $barang = $pengadaan->barang;
                $barang->stok -= $pengadaan->debit;
                $barang->save();
            }

            // Hapus data pengadaan
            $pengadaan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data pengadaan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to update saldo in monitoring barang table when stock changes
     */
    private function updateMonitoringBarangSaldo($idBarang, $newStok)
    {
        // Update saldo untuk monitoring barang dengan status 'diajukan' (belum diterima)
        // Karena ketika status 'diterima', saldo_akhir sudah dihitung berdasarkan pengambilan
        MonitoringBarang::where('id_barang', $idBarang)
            ->where('status', 'diajukan')
            ->update([
                'saldo' => $newStok,
                'saldo_akhir' => DB::raw('saldo - kredit')
            ]);

        // Log untuk debugging (optional)
        Log::info("Updated monitoring barang saldo for barang ID: {$idBarang}, new stock: {$newStok}");
    }

    /**
     * Sync saldo_akhir in monitoring pengadaan with current stock from barang table
     */
    private function syncSaldoAkhirWithCurrentStock()
    {
        // Update saldo_akhir untuk semua monitoring pengadaan berdasarkan stok terkini dari tabel barang
        DB::statement("
            UPDATE monitoring_pengadaan mp
            INNER JOIN barang b ON mp.barang_id = b.id_barang
            SET mp.saldo_akhir = b.stok
        ");
    }
}
