<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailMonitoringBarang;
use App\Models\Barang;
use App\Services\DetailMonitoringBarangService;
use App\Constants\BidangConstants;

class DetailMonitoringBarangController extends Controller
{
    protected $detailMonitoringService;

    public function __construct(DetailMonitoringBarangService $detailMonitoringService)
    {
        $this->middleware(['auth', 'role:admin']);
        $this->detailMonitoringService = $detailMonitoringService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Sinkronisasi data terlebih dahulu jika diminta
            if ($request->get('sync')) {
                $this->detailMonitoringService->syncAllData();
                return redirect()->route('admin.detail-monitoring-barang.index')
                    ->with('success', 'Data monitoring berhasil disinkronisasi!');
            }

            // Persiapan filter
            $filters = [
                'id_barang' => $request->get('id_barang'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'bidang' => $request->get('bidang'),
                'jenis' => $request->get('jenis'),
            ];

            // Ambil data detail monitoring dengan filter
            $query = $this->detailMonitoringService->getDetailMonitoring($filters);
            $detailMonitoring = $query->paginate(20)->appends($request->query());

            // Hitung total kredit dan debit berdasarkan filter yang sama
            $statsQuery = $this->detailMonitoringService->getDetailMonitoring($filters);
            $statistics = [
                'total_kredit' => (int) ($statsQuery->sum('kredit') ?? 0),
                'total_debit' => (int) ($statsQuery->sum('debit') ?? 0),
                'total_saldo' => (int) ($statsQuery->sum('saldo') ?? 0),
            ];

            // Data untuk filter dropdown
            $barangList = Barang::select('id_barang', 'nama_barang')->orderBy('nama_barang')->get();
            $bidangList = BidangConstants::getBidangKeys();

            return view('admin.detail-monitoring-barang.index', compact(
                'detailMonitoring',
                'barangList',
                'bidangList',
                'filters',
                'statistics'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Sinkronisasi manual data monitoring
     */
    public function sync()
    {
        try {
            $this->detailMonitoringService->syncAllData();
            return response()->json([
                'success' => true,
                'message' => 'Data monitoring berhasil disinkronisasi!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan sinkronisasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for filtered data (AJAX)
     */
    public function getStatistics(Request $request)
    {
        try {
            $filters = [
                'id_barang' => $request->get('id_barang'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'bidang' => $request->get('bidang'),
                'jenis' => $request->get('jenis'),
            ];

            // Mendapatkan query yang sama dengan index
            $statsQuery = $this->detailMonitoringService->getDetailMonitoring($filters);

            $statistics = [
                'total_kredit' => (int) ($statsQuery->sum('kredit') ?? 0),
                'total_debit' => (int) ($statsQuery->sum('debit') ?? 0),
                'total_saldo' => (int) ($statsQuery->sum('saldo') ?? 0),
            ];

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update saldo berdasarkan stok terkini
     */
    public function updateSaldo()
    {
        try {
            $this->detailMonitoringService->updateSaldoFromBarang();
            return response()->json([
                'success' => true,
                'message' => 'Saldo berhasil diperbarui berdasarkan stok terkini!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui saldo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ekspor data ke Excel dengan format tabel yang sama
     */
    public function export(Request $request)
    {
        try {
            $filters = [
                'id_barang' => $request->get('id_barang'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'bidang' => $request->get('bidang'),
                'jenis' => $request->get('jenis'),
            ];

            $detailMonitoring = $this->detailMonitoringService->getDetailMonitoring($filters)->get();
            $barangList = Barang::select('id_barang', 'nama_barang')->orderBy('nama_barang')->get();

            // Generate HTML table with exact styling
            $html = $this->generateExcelHtml($detailMonitoring, $filters, $barangList);

            $filename = 'detail-monitoring-barang-' . date('Y-m-d-H-i-s') . '.xls';

            return response($html, 200, [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    /**
     * Generate HTML untuk Excel dengan format tabel yang sama
     */
    private function generateExcelHtml($detailMonitoring, $filters, $barangList)
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Monitoring Barang</title>
    <style>
        body { font-family: "Times New Roman", serif; font-size: 11px; }
        .header { text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 20px; }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
            font-size: 11px;
        }
        th {
            background-color: #F3F4F6;
            font-weight: bold;
        }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .header-main { background-color: #E5E7EB; font-weight: bold; }
        .header-sub { background-color: #F3F4F6; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        SISTEM INFORMASI MONITORING BARANG HABIS PAKAI<br>
        Detail Monitoring Barang<br>
        <small style="font-size: 12px;">Rekapitulasi monitoring pengambilan dan pengadaan barang</small>
        <br><br>
    </div>

    <table>
        <thead>
            <!-- Header Utama dengan rowspan dan colspan yang sama -->
            <tr class="header-main">
                <th rowspan="2">No</th>
                <th rowspan="2">Tanggal</th>
                <th rowspan="2">Nama Barang</th>
                <th colspan="3">Uraian</th>
                <th colspan="3">Persediaan</th>
            </tr>
            <!-- Sub Header -->
            <tr class="header-sub">
                <th>Keterangan</th>
                <th>Bidang</th>
                <th>Pengambil</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($detailMonitoring as $index => $item) {
            $html .= '<tr>
                <td class="text-center">' . ($index + 1) . '</td>
                <td class="text-center">' . $item->tanggal->format('d/m/Y') . '</td>
                <td class="text-left">' . htmlspecialchars($item->barang->nama_barang ?? $item->nama_barang ?? '-') . '</td>
                <td class="text-left">' . htmlspecialchars($item->keterangan ?? '-') . '</td>
                <td class="text-center">' . ($item->bidang ? BidangConstants::getBidangName($item->bidang) : '-') . '</td>
                <td class="text-center">' . htmlspecialchars($item->pengambil ?? '-') . '</td>
                <td class="text-center">' . ($item->debit ? number_format($item->debit, 0, ',', '.') : '0') . '</td>
                <td class="text-center">' . ($item->kredit ? number_format($item->kredit, 0, ',', '.') : '0') . '</td>
                <td class="text-center">' . number_format($item->saldo, 0, ',', '.') . '</td>
            </tr>';
        }

        if ($detailMonitoring->isEmpty()) {
            $html .= '<tr>
                <td colspan="9" class="text-center" style="padding: 20px; font-style: italic;">
                    Belum ada data monitoring
                </td>
            </tr>';
        }

        $html .= '</tbody>
    </table>
</body>
</html>';

        return $html;
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Detail monitoring biasanya otomatis dari sinkronisasi
        return redirect()->route('admin.detail-monitoring-barang.index')
            ->with('info', 'Data detail monitoring dibuat otomatis dari sinkronisasi monitoring barang dan pengadaan.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Detail monitoring biasanya otomatis dari sinkronisasi
        return redirect()->route('admin.detail-monitoring-barang.index')
            ->with('info', 'Data detail monitoring dibuat otomatis dari sinkronisasi monitoring barang dan pengadaan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $detail = DetailMonitoringBarang::with(['barang', 'monitoringBarang', 'monitoringPengadaan'])
                ->findOrFail($id);

            return view('admin.detail-monitoring-barang.show', compact('detail'));
        } catch (\Exception $e) {
            return redirect()->route('admin.detail-monitoring-barang.index')
                ->with('error', 'Data tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $detail = DetailMonitoringBarang::with(['barang'])->findOrFail($id);
            $barangList = Barang::select('id_barang', 'nama_barang')->orderBy('nama_barang')->get();

            return view('admin.detail-monitoring-barang.edit', compact('detail', 'barangList'));
        } catch (\Exception $e) {
            return redirect()->route('admin.detail-monitoring-barang.index')
                ->with('error', 'Data tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'saldo' => 'required|integer|min:0',
                'keterangan' => 'nullable|string',
                'bidang' => 'nullable|string',
                'pengambil' => 'nullable|string',
                'debit' => 'nullable|integer|min:0',
                'kredit' => 'nullable|integer|min:0',
            ]);

            $detail = DetailMonitoringBarang::findOrFail($id);
            $detail->update($request->only([
                'tanggal',
                'saldo',
                'keterangan',
                'bidang',
                'pengambil',
                'debit',
                'kredit'
            ]));

            return redirect()->route('admin.detail-monitoring-barang.index')
                ->with('success', 'Detail monitoring berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $detail = DetailMonitoringBarang::findOrFail($id);
            $detail->delete();

            return response()->json([
                'success' => true,
                'message' => 'Detail monitoring berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
