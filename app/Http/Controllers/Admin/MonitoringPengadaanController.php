<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonitoringPengadaan;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

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

        try {
            DB::beginTransaction();

            $pengadaan = MonitoringPengadaan::with('barang')->findOrFail($id);
            $oldStatus = $pengadaan->status;
            $newStatus = $request->status;
            $jumlahPengadaan = $pengadaan->debit;

            // Update barang stock based on status change
            if ($oldStatus === 'proses' && $newStatus === 'terima') {
                // When accepting pengadaan, increase stock
                $pengadaan->barang->stok += $jumlahPengadaan;
                $pengadaan->barang->save();
                $message = 'Pengadaan berhasil diterima dan stok barang telah ditambahkan';
            } elseif ($oldStatus === 'terima' && $newStatus === 'proses') {
                // When cancelling acceptance, decrease stock
                if ($pengadaan->barang->stok < $jumlahPengadaan) {
                    throw new \Exception('Stok tidak mencukupi untuk pembatalan. Pastikan stok barang cukup.');
                }
                $pengadaan->barang->stok -= $jumlahPengadaan;
                $pengadaan->barang->save();
                $message = 'Status pengadaan dibatalkan dan stok barang telah dikurangi';
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

            // Jika status sudah 'terima', perlu update stok barang
            if ($pengadaan->status === 'terima') {
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

            // Jika status terima, kembalikan stok
            if ($pengadaan->status === 'terima') {
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
}
