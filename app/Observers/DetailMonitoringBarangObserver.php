<?php

namespace App\Observers;

use App\Models\DetailMonitoringBarang;
use App\Models\Triwulan;
use App\Models\Barang;
use Carbon\Carbon;

class DetailMonitoringBarangObserver
{
    /**
     * Handle the DetailMonitoringBarang "created" event.
     */
    public function created(DetailMonitoringBarang $detailMonitoringBarang): void
    {
        $this->updateTriwulanData($detailMonitoringBarang);
    }

    /**
     * Handle the DetailMonitoringBarang "updated" event.
     */
    public function updated(DetailMonitoringBarang $detailMonitoringBarang): void
    {
        $this->updateTriwulanData($detailMonitoringBarang);
    }

    /**
     * Handle the DetailMonitoringBarang "deleted" event.
     */
    public function deleted(DetailMonitoringBarang $detailMonitoringBarang): void
    {
        $this->updateTriwulanData($detailMonitoringBarang);
    }

    /**
     * Handle the DetailMonitoringBarang "restored" event.
     */
    public function restored(DetailMonitoringBarang $detailMonitoringBarang): void
    {
        $this->updateTriwulanData($detailMonitoringBarang);
    }

    /**
     * Handle the DetailMonitoringBarang "force deleted" event.
     */
    public function forceDeleted(DetailMonitoringBarang $detailMonitoringBarang): void
    {
        $this->updateTriwulanData($detailMonitoringBarang);
    }

    /**
     * Update data triwulan otomatis ketika ada perubahan detail monitoring
     */
    private function updateTriwulanData(DetailMonitoringBarang $detailMonitoring)
    {
        // Ambil tanggal dan tentukan triwulan
        $tanggal = Carbon::parse($detailMonitoring->tanggal);
        $tahun = $tanggal->year;
        $triwulan = $this->getTriwulanFromDate($tanggal);

        // Ambil data barang
        $barang = Barang::where('id_barang', $detailMonitoring->id_barang)->first();
        if (!$barang) return;

        // Tentukan range tanggal untuk triwulan
        $dateRanges = [
            1 => ['01-01', '03-31'], // Januari - Maret
            2 => ['04-01', '06-30'], // April - Juni
            3 => ['07-01', '09-30'], // Juli - September
            4 => ['10-01', '12-31']  // Oktober - Desember
        ];

        $startDate = Carbon::parse("{$tahun}-{$dateRanges[$triwulan][0]}");
        $endDate = Carbon::parse("{$tahun}-{$dateRanges[$triwulan][1]}");

        // Cari atau buat data triwulan
        $triwulanData = Triwulan::updateOrCreate(
            [
                'id_barang' => $detailMonitoring->id_barang,
                'tahun' => $tahun,
                'triwulan' => $triwulan
            ],
            [
                'nama_barang' => $barang->nama_barang,
                'satuan' => $barang->satuan,
                'harga_satuan' => $barang->harga_barang,
            ]
        );

        // Hitung ulang saldo awal dan totals
        $saldoAwal = $this->getSaldoAwalTriwulan($detailMonitoring->id_barang, $startDate);
        $totals = $this->calculateTriwulanTotals($detailMonitoring->id_barang, $startDate, $endDate);

        // Update data triwulan
        $triwulanData->update([
            'saldo_awal_triwulan' => $saldoAwal,
            'total_kredit_triwulan' => $totals['total_kredit'],
            'total_harga_kredit' => $barang->harga_barang * $totals['total_kredit'],
            'total_debit_triwulan' => $totals['total_debit'],
            'total_harga_debit' => $barang->harga_barang * $totals['total_debit'],
            'total_persediaan_triwulan' => $saldoAwal + $totals['total_debit'] - $totals['total_kredit'],
            'total_harga_persediaan' => $barang->harga_barang * ($saldoAwal + $totals['total_debit'] - $totals['total_kredit'])
        ]);
    }

    /**
     * Tentukan triwulan berdasarkan tanggal
     */
    private function getTriwulanFromDate(Carbon $date): int
    {
        $month = $date->month;

        if ($month >= 1 && $month <= 3) return 1;
        if ($month >= 4 && $month <= 6) return 2;
        if ($month >= 7 && $month <= 9) return 3;
        return 4;
    }

    /**
     * Ambil saldo awal triwulan
     */
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
        $barang = \App\Models\Barang::where('id_barang', $idBarang)->first();
        return $barang ? $barang->stok : 0;
    }

    /**
     * Hitung total kredit dan debit dalam periode triwulan
     */
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
}
