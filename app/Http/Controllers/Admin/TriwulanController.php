<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Triwulan;
use App\Models\Barang;
use App\Models\DetailMonitoringBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TriwulanController extends Controller
{
    public function index(Request $request)
    {
        $query = Triwulan::with('barang');

        // Filter berdasarkan tahun
        if ($request->has('tahun') && !empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        }

        // Filter berdasarkan triwulan
        if ($request->has('triwulan') && !empty($request->triwulan)) {
            $query->where('triwulan', $request->triwulan);
        }

        // Filter berdasarkan nama barang
        if ($request->has('search') && !empty($request->search)) {
            $query->where('nama_barang', 'LIKE', '%' . $request->search . '%');
        }

        $triwulans = $query->orderBy('tahun', 'desc')
            ->orderBy('triwulan', 'desc')
            ->orderBy('nama_barang', 'asc')
            ->paginate(15);

        // Ambil data untuk filter
        $tahuns = Triwulan::distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('admin.triwulan.index', compact('triwulans', 'tahuns'));
    }

    public function create()
    {
        return view('admin.triwulan.create');
    }

    public function generateData(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:2030',
            'triwulan' => 'required|integer|min:1|max:4'
        ]);

        $tahun = $request->tahun;
        $triwulan = $request->triwulan;

        // Tentukan range tanggal untuk triwulan
        $dateRanges = [
            1 => ['01-01', '03-31'], // Januari - Maret
            2 => ['04-01', '06-30'], // April - Juni
            3 => ['07-01', '09-30'], // Juli - September
            4 => ['10-01', '12-31']  // Oktober - Desember
        ];

        $startDate = Carbon::parse("{$tahun}-{$dateRanges[$triwulan][0]}");
        $endDate = Carbon::parse("{$tahun}-{$dateRanges[$triwulan][1]}");

        // Ambil semua barang
        $barangs = Barang::all();
        $generatedCount = 0;

        foreach ($barangs as $barang) {
            // Cek apakah data sudah ada
            $existingData = Triwulan::where([
                'id_barang' => $barang->id_barang,
                'tahun' => $tahun,
                'triwulan' => $triwulan
            ])->first();

            if ($existingData) {
                continue; // Skip jika data sudah ada
            }

            // Hitung saldo awal triwulan (dari detail monitoring barang)
            $saldoAwal = $this->getSaldoAwalTriwulan($barang->id_barang, $startDate);

            // Hitung total kredit dan debit selama triwulan
            $totals = $this->calculateTriwulanTotals($barang->id_barang, $startDate, $endDate);

            // Buat data triwulan
            Triwulan::create([
                'id_barang' => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'satuan' => $barang->satuan,
                'harga_satuan' => $barang->harga_barang,
                'tahun' => $tahun,
                'triwulan' => $triwulan,
                'saldo_awal_triwulan' => $saldoAwal,
                'total_kredit_triwulan' => $totals['total_kredit'],
                'total_harga_kredit' => $barang->harga_barang * $totals['total_kredit'],
                'total_debit_triwulan' => $totals['total_debit'],
                'total_harga_debit' => $barang->harga_barang * $totals['total_debit'],
                'total_persediaan_triwulan' => $saldoAwal + $totals['total_debit'] - $totals['total_kredit'],
                'total_harga_persediaan' => $barang->harga_barang * ($saldoAwal + $totals['total_debit'] - $totals['total_kredit'])
            ]);

            $generatedCount++;
        }

        return redirect()->route('admin.triwulan.index')
            ->with('success', "Berhasil generate data triwulan {$triwulan} tahun {$tahun} untuk {$generatedCount} barang");
    }

    public function syncData(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:2030',
            'triwulan' => 'required|integer|min:1|max:4'
        ]);

        $tahun = $request->tahun;
        $triwulan = $request->triwulan;

        // Tentukan range tanggal untuk triwulan
        $dateRanges = [
            1 => ['01-01', '03-31'], // Januari - Maret
            2 => ['04-01', '06-30'], // April - Juni
            3 => ['07-01', '09-30'], // Juli - September
            4 => ['10-01', '12-31']  // Oktober - Desember
        ];

        $startDate = Carbon::parse("{$tahun}-{$dateRanges[$triwulan][0]}");
        $endDate = Carbon::parse("{$tahun}-{$dateRanges[$triwulan][1]}");

        // Ambil semua barang yang ada di detail monitoring untuk periode tersebut
        $barangIds = DetailMonitoringBarang::whereBetween('tanggal', [$startDate, $endDate])
            ->distinct()
            ->pluck('id_barang');

        $syncedCount = 0;
        $updatedCount = 0;

        foreach ($barangIds as $idBarang) {
            $barang = Barang::where('id_barang', $idBarang)->first();
            if (!$barang) continue;

            // Hitung saldo awal dan totals
            $saldoAwal = $this->getSaldoAwalTriwulan($idBarang, $startDate);
            $totals = $this->calculateTriwulanTotals($idBarang, $startDate, $endDate);

            // Update atau create data triwulan
            $triwulanData = Triwulan::updateOrCreate(
                [
                    'id_barang' => $idBarang,
                    'tahun' => $tahun,
                    'triwulan' => $triwulan
                ],
                [
                    'nama_barang' => $barang->nama_barang,
                    'satuan' => $barang->satuan,
                    'harga_satuan' => $barang->harga_barang,
                    'saldo_awal_triwulan' => $saldoAwal,
                    'total_kredit_triwulan' => $totals['total_kredit'],
                    'total_harga_kredit' => $barang->harga_barang * $totals['total_kredit'],
                    'total_debit_triwulan' => $totals['total_debit'],
                    'total_harga_debit' => $barang->harga_barang * $totals['total_debit'],
                    'total_persediaan_triwulan' => $saldoAwal + $totals['total_debit'] - $totals['total_kredit'],
                    'total_harga_persediaan' => $barang->harga_barang * ($saldoAwal + $totals['total_debit'] - $totals['total_kredit'])
                ]
            );

            if ($triwulanData->wasRecentlyCreated) {
                $syncedCount++;
            } else {
                $updatedCount++;
            }
        }

        $message = "Berhasil sinkronkan data triwulan {$triwulan} tahun {$tahun}. ";
        $message .= "Data baru: {$syncedCount}, Data diperbarui: {$updatedCount}";

        return redirect()->route('admin.triwulan.index')
            ->with('success', $message);
    }

    public function syncAllData()
    {
        // Ambil semua tanggal dari detail monitoring barang
        $allDates = DetailMonitoringBarang::distinct()
            ->pluck('tanggal')
            ->map(function ($date) {
                return Carbon::parse($date);
            });

        if ($allDates->isEmpty()) {
            return redirect()->route('admin.triwulan.index')
                ->with('error', 'Tidak ada data detail monitoring barang untuk disinkronkan.');
        }

        // Group tanggal berdasarkan tahun dan triwulan
        $periods = [];
        foreach ($allDates as $date) {
            $tahun = $date->year;
            $triwulan = $this->getTriwulanFromDate($date);
            $key = "{$tahun}-{$triwulan}";

            if (!isset($periods[$key])) {
                $periods[$key] = ['tahun' => $tahun, 'triwulan' => $triwulan];
            }
        }

        $totalSynced = 0;
        $totalUpdated = 0;
        $processedPeriods = 0;

        foreach ($periods as $period) {
            $tahun = $period['tahun'];
            $triwulan = $period['triwulan'];

            // Tentukan range tanggal untuk triwulan
            $dateRanges = [
                1 => ['01-01', '03-31'], // Januari - Maret
                2 => ['04-01', '06-30'], // April - Juni
                3 => ['07-01', '09-30'], // Juli - September
                4 => ['10-01', '12-31']  // Oktober - Desember
            ];

            $startDate = Carbon::parse("{$tahun}-{$dateRanges[$triwulan][0]}");
            $endDate = Carbon::parse("{$tahun}-{$dateRanges[$triwulan][1]}");

            // Ambil barang yang ada di detail monitoring untuk periode tersebut
            $barangIds = DetailMonitoringBarang::whereBetween('tanggal', [$startDate, $endDate])
                ->distinct()
                ->pluck('id_barang');

            foreach ($barangIds as $idBarang) {
                $barang = Barang::where('id_barang', $idBarang)->first();
                if (!$barang) continue;

                // Hitung saldo awal dan totals
                $saldoAwal = $this->getSaldoAwalTriwulan($idBarang, $startDate);
                $totals = $this->calculateTriwulanTotals($idBarang, $startDate, $endDate);

                // Update atau create data triwulan
                $triwulanData = Triwulan::updateOrCreate(
                    [
                        'id_barang' => $idBarang,
                        'tahun' => $tahun,
                        'triwulan' => $triwulan
                    ],
                    [
                        'nama_barang' => $barang->nama_barang,
                        'satuan' => $barang->satuan,
                        'harga_satuan' => $barang->harga_barang,
                        'saldo_awal_triwulan' => $saldoAwal,
                        'total_kredit_triwulan' => $totals['total_kredit'],
                        'total_harga_kredit' => $barang->harga_barang * $totals['total_kredit'],
                        'total_debit_triwulan' => $totals['total_debit'],
                        'total_harga_debit' => $barang->harga_barang * $totals['total_debit'],
                        'total_persediaan_triwulan' => $saldoAwal + $totals['total_debit'] - $totals['total_kredit'],
                        'total_harga_persediaan' => $barang->harga_barang * ($saldoAwal + $totals['total_debit'] - $totals['total_kredit'])
                    ]
                );

                if ($triwulanData->wasRecentlyCreated) {
                    $totalSynced++;
                } else {
                    $totalUpdated++;
                }
            }

            $processedPeriods++;
        }

        $message = "Sinkronisasi berhasil. ";
        $message .= "Periode yang diproses: {$processedPeriods}, ";
        $message .= "Data baru: {$totalSynced}, Data diperbarui: {$totalUpdated}";

        return redirect()->route('admin.triwulan.index')
            ->with('success', $message);
    }

    private function getTriwulanFromDate($date): int
    {
        $month = $date->month;

        if ($month >= 1 && $month <= 3) return 1;
        if ($month >= 4 && $month <= 6) return 2;
        if ($month >= 7 && $month <= 9) return 3;
        return 4;
    }

    private function getSaldoAwalTriwulan($idBarang, $startDate)
    {
        // Strategi 1: Cari record terakhir sebelum tanggal mulai triwulan
        $lastRecord = DetailMonitoringBarang::where('id_barang', $idBarang)
            ->where('tanggal', '<', $startDate)
            ->orderBy('tanggal', 'desc')
            ->first();

        if ($lastRecord) {
            return $lastRecord->saldo;
        }

        // Strategi 2: Jika tidak ada record sebelumnya, cari record pertama dalam periode
        $firstInPeriod = DetailMonitoringBarang::where('id_barang', $idBarang)
            ->where('tanggal', '>=', $startDate)
            ->orderBy('tanggal', 'asc')
            ->first();

        if ($firstInPeriod) {
            // Hitung saldo awal berdasarkan transaksi pertama
            $saldoAwal = $firstInPeriod->saldo;

            // Kurangi dengan perubahan yang terjadi pada hari itu untuk mendapat saldo awal
            if ($firstInPeriod->debit > 0) {
                $saldoAwal -= $firstInPeriod->debit; // Kurangi debit (pengadaan)
            }
            if ($firstInPeriod->kredit > 0) {
                $saldoAwal += $firstInPeriod->kredit; // Tambah kredit (pengambilan)
            }

            return max(0, $saldoAwal); // Pastikan tidak negatif
        }

        // Strategi 3: Jika tidak ada data sama sekali, ambil dari stok barang
        $barang = Barang::where('id_barang', $idBarang)->first();
        return $barang ? $barang->stok : 0;
    }

    private function calculateTriwulanTotals($idBarang, $startDate, $endDate)
    {
        $result = DetailMonitoringBarang::where('id_barang', $idBarang)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->selectRaw('
                                          COALESCE(SUM(kredit), 0) as total_kredit,
                                          COALESCE(SUM(debit), 0) as total_debit
                                      ')
            ->first();

        return [
            'total_kredit' => $result->total_kredit ?? 0,
            'total_debit' => $result->total_debit ?? 0
        ];
    }

    public function edit($id)
    {
        $triwulan = Triwulan::with('barang')->findOrFail($id);
        return view('admin.triwulan.edit', compact('triwulan'));
    }

    public function update(Request $request, $id)
    {
        $triwulan = Triwulan::findOrFail($id);

        $request->validate([
            'saldo_awal_triwulan' => 'required|integer|min:0',
            'total_kredit_triwulan' => 'required|integer|min:0',
            'total_debit_triwulan' => 'required|integer|min:0'
        ]);

        // Update dengan perhitungan otomatis
        $triwulan->update([
            'saldo_awal_triwulan' => $request->saldo_awal_triwulan,
            'total_kredit_triwulan' => $request->total_kredit_triwulan,
            'total_harga_kredit' => $triwulan->harga_satuan * $request->total_kredit_triwulan,
            'total_debit_triwulan' => $request->total_debit_triwulan,
            'total_harga_debit' => $triwulan->harga_satuan * $request->total_debit_triwulan,
            'total_persediaan_triwulan' => $request->saldo_awal_triwulan + $request->total_debit_triwulan - $request->total_kredit_triwulan,
            'total_harga_persediaan' => $triwulan->harga_satuan * ($request->saldo_awal_triwulan + $request->total_debit_triwulan - $request->total_kredit_triwulan)
        ]);

        return redirect()->route('admin.triwulan.index')
            ->with('success', 'Data triwulan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $triwulan = Triwulan::findOrFail($id);
        $triwulan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data triwulan berhasil dihapus'
        ]);
    }
}
