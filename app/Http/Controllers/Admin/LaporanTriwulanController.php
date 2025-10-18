<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LaporanTriwulanService;
use App\Models\LaporanTriwulan;
use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class LaporanTriwulanController extends Controller
{
    protected $laporanService;

    public function __construct(LaporanTriwulanService $laporanService)
    {
        $this->middleware(['auth']);
        $this->laporanService = $laporanService;
    }

    /**
     * Display laporan triwulan dengan filter
     */
    public function index(Request $request)
    {
        $tahunSekarang = Carbon::now()->year;
        $tahun = $request->get('tahun', $tahunSekarang);
        $triwulan = $request->get('triwulan');

        // Get available periodes
        $availablePeriodes = $this->laporanService->getAvailablePeriodes();

        // Get laporan data
        $laporans = $this->laporanService->getLaporanTriwulan($tahun, $triwulan);

        // Get ringkasan per triwulan untuk tahun yang dipilih
        $ringkasan = $this->laporanService->getRingkasanPerTahun($tahun);

        // Available years untuk filter
        $availableYears = $availablePeriodes->pluck('tahun')->unique()->sortDesc();

        return view('admin.laporan-triwulan.index', compact(
            'laporans',
            'tahun',
            'triwulan',
            'ringkasan',
            'availablePeriodes',
            'availableYears'
        ));
    }

    /**
     * Generate laporan untuk periode tertentu
     */
    public function generate(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'triwulan' => 'required|integer|min:1|max:4'
        ]);

        $tahun = $request->tahun;
        $triwulan = $request->triwulan;

        // Cek apakah laporan sudah ada
        if ($this->laporanService->isLaporanExists($tahun, $triwulan)) {
            return response()->json([
                'success' => false,
                'message' => "Laporan untuk Tahun {$tahun} Triwulan {$triwulan} sudah ada. Hapus dulu jika ingin generate ulang."
            ]);
        }

        $result = $this->laporanService->generateLaporanTriwulan($tahun, $triwulan);

        return response()->json($result);
    }

    /**
     * Generate laporan triwulan saat ini
     */
    public function generateCurrent()
    {
        $result = $this->laporanService->generateLaporanTriwulanSaatIni();

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Laporan triwulan saat ini berhasil digenerate!',
                'redirect' => route('admin.laporan-triwulan.index')
            ]);
        }

        return response()->json($result);
    }

    /**
     * Delete laporan untuk periode tertentu
     */
    public function delete(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'triwulan' => 'required|integer|min:1|max:4'
        ]);

        try {
            $deleted = LaporanTriwulan::where('tahun', $request->tahun)
                ->where('triwulan', $request->triwulan)
                ->delete();

            if ($deleted > 0) {
                return response()->json([
                    'success' => true,
                    'message' => "Laporan Tahun {$request->tahun} Triwulan {$request->triwulan} berhasil dihapus ({$deleted} data)"
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Laporan untuk periode tersebut tidak ditemukan"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export laporan ke Excel/CSV
     */
    public function export(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $triwulan = $request->get('triwulan');
        $format = $request->get('format', 'excel'); // excel atau csv

        $laporans = $this->laporanService->getLaporanTriwulan($tahun, $triwulan);

        if ($laporans->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data laporan untuk periode yang dipilih');
        }

        $filename = "laporan_triwulan_{$tahun}";
        if ($triwulan) {
            $filename .= "_Q{$triwulan}";
        }

        if ($format === 'csv') {
            return $this->exportToCsv($laporans, $filename);
        } else {
            return $this->exportToExcel($laporans, $filename);
        }
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($laporans, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        $callback = function () use ($laporans) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'No',
                'Nama Barang',
                'Satuan',
                'Tahun',
                'Triwulan',
                'Saldo Akhir Sebelumnya',
                'Jumlah Pengadaan',
                'Harga Satuan',
                'Jumlah Harga',
                'Jumlah Pemakaian',
                'Saldo Tersedia',
                'Total Harga'
            ]);

            // Data
            foreach ($laporans as $index => $laporan) {
                fputcsv($file, [
                    $index + 1,
                    $laporan->nama_barang,
                    $laporan->satuan,
                    $laporan->tahun,
                    "Q{$laporan->triwulan}",
                    $laporan->saldo_akhir_sebelumnya,
                    $laporan->jumlah_pengadaan,
                    number_format($laporan->harga_satuan, 2, '.', ''),
                    number_format($laporan->jumlah_harga, 2, '.', ''),
                    $laporan->jumlah_pemakaian,
                    $laporan->saldo_tersedia,
                    number_format($laporan->total_harga, 2, '.', '')
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export to Excel (HTML format yang bisa dibuka Excel)
     */
    private function exportToExcel($laporans, $filename)
    {
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}.xls\"",
        ];

        $html = view('admin.laporan-triwulan.export-excel', compact('laporans'))->render();

        return Response::make($html, 200, $headers);
    }

    /**
     * API untuk mendapatkan statistik dashboard
     */
    public function statistik(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $ringkasan = $this->laporanService->getRingkasanPerTahun($tahun);

        // Format data untuk chart
        $chartData = $ringkasan->map(function ($item) {
            return [
                'triwulan' => "Q{$item->triwulan}",
                'total_pengadaan' => $item->total_pengadaan,
                'total_pemakaian' => $item->total_pemakaian,
                'total_nilai_akhir' => $item->total_nilai_akhir
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $chartData,
            'ringkasan' => $ringkasan
        ]);
    }

    /**
     * Show detail laporan triwulan
     */
    public function show($id)
    {
        try {
            $laporan = LaporanTriwulan::with('barang')->findOrFail($id);

            // Get historical data for this barang
            $historical = LaporanTriwulan::where('barang_id', $laporan->barang_id)
                ->where('id', '!=', $laporan->id)
                ->orderBy('tahun', 'desc')
                ->orderBy('triwulan', 'desc')
                ->limit(6)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'laporan' => $laporan,
                    'historical' => $historical,
                    'barang' => $laporan->barang
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data laporan tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update laporan triwulan item
     */
    public function updateItem(Request $request, $id)
    {
        try {
            $laporan = LaporanTriwulan::findOrFail($id);

            $request->validate([
                'harga_satuan' => 'required|numeric|min:0',
                'keterangan' => 'nullable|string|max:500'
            ]);

            $laporan->update([
                'harga_satuan' => $request->harga_satuan,
                'jumlah_harga' => $laporan->jumlah_pengadaan * $request->harga_satuan,
                'total_harga' => $laporan->saldo_tersedia * $request->harga_satuan,
                'keterangan' => $request->keterangan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data laporan berhasil diperbarui',
                'data' => $laporan->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete individual laporan item
     */
    public function destroy($id)
    {
        try {
            $laporan = LaporanTriwulan::findOrFail($id);
            $namaBarang = $laporan->nama_barang;
            $periode = "Q{$laporan->triwulan} {$laporan->tahun}";

            $laporan->delete();

            return response()->json([
                'success' => true,
                'message' => "Data laporan {$namaBarang} periode {$periode} berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data laporan: ' . $e->getMessage()
            ], 500);
        }
    }
}
