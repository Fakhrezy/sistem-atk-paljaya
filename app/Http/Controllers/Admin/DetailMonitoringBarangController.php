<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailMonitoringBarang;
use App\Models\Barang;
use App\Services\DetailMonitoringBarangService;

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

            // Data untuk filter dropdown
            $barangList = Barang::select('id_barang', 'nama_barang')->orderBy('nama_barang')->get();
            $bidangList = ['umum', 'perencanaan', 'keuangan', 'operasional', 'lainnya'];

            return view('admin.detail-monitoring-barang.index', compact(
                'detailMonitoring',
                'barangList',
                'bidangList',
                'filters'
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
     * Ekspor data ke Excel/CSV
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

            $filename = 'detail-monitoring-barang-' . date('Y-m-d-H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($detailMonitoring) {
                $file = fopen('php://output', 'w');

                // Header CSV
                fputcsv($file, [
                    'No',
                    'Tanggal',
                    'Nama Barang',
                    'Keterangan',
                    'Bidang',
                    'Pengambil',
                    'Debit',
                    'Kredit',
                    'Saldo'
                ]);

                // Data
                foreach ($detailMonitoring as $index => $item) {
                    fputcsv($file, [
                        $index + 1,
                        $item->tanggal->format('d/m/Y'),
                        $item->nama_barang,
                        $item->keterangan ?? '-',
                        $item->bidang ? ucfirst($item->bidang) : '-',
                        $item->pengambil ?? '-',
                        $item->debit ? number_format($item->debit, 0, ',', '.') : '0',
                        $item->kredit ? number_format($item->kredit, 0, ',', '.') : '0',
                        number_format($item->saldo, 0, ',', '.')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
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
