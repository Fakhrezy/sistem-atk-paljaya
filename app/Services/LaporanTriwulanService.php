<?php

namespace App\Services;

use App\Models\LaporanTriwulan;
use App\Models\Barang;
use App\Models\MonitoringPengadaan;
use App\Models\MonitoringBarang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanTriwulanService
{
    /**
     * Generate laporan triwulan untuk periode tertentu
     */
    public function generateLaporanTriwulan($tahun, $triwulan)
    {
        try {
            DB::beginTransaction();

            // Hapus laporan existing untuk periode ini jika ada
            LaporanTriwulan::where('tahun', $tahun)
                ->where('triwulan', $triwulan)
                ->delete();

            // Get range tanggal untuk triwulan
            $tanggalRange = $this->getTriwulanDateRange($tahun, $triwulan);
            $tanggalMulai = $tanggalRange['mulai'];
            $tanggalSelesai = $tanggalRange['selesai'];

            // Get semua barang yang aktif
            $barangs = Barang::all();

            $laporanData = [];

            foreach ($barangs as $barang) {
                // 1. Saldo akhir triwulan sebelumnya
                $saldoAkhirSebelumnya = $this->getSaldoAkhirTriwulanSebelumnya($barang->id_barang, $tahun, $triwulan);

                // 2. Jumlah pengadaan dalam triwulan ini (debit)
                $jumlahPengadaan = MonitoringPengadaan::where('barang_id', $barang->id_barang)
                    ->where('status', 'selesai')
                    ->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai])
                    ->sum('debit');

                // 3. Jumlah pemakaian dalam triwulan ini (kredit)
                $jumlahPemakaian = MonitoringBarang::where('id_barang', $barang->id_barang)
                    ->where('status', 'diterima')
                    ->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai])
                    ->sum('kredit');

                // 4. Saldo tersedia (stok saat ini)
                $saldoTersedia = $barang->stok;

                // 5. Harga satuan
                $hargaSatuan = $barang->harga_barang;

                // 6. Kalkulasi harga
                $jumlahHarga = $hargaSatuan * $saldoAkhirSebelumnya;
                $totalHarga = $hargaSatuan * $saldoTersedia;

                // Create laporan entry
                $laporanData[] = [
                    'barang_id' => $barang->id_barang,
                    'nama_barang' => $barang->nama_barang,
                    'satuan' => $barang->satuan,
                    'tahun' => $tahun,
                    'triwulan' => $triwulan,
                    'saldo_akhir_sebelumnya' => $saldoAkhirSebelumnya,
                    'jumlah_pengadaan' => $jumlahPengadaan,
                    'harga_satuan' => $hargaSatuan,
                    'jumlah_harga' => $jumlahHarga,
                    'jumlah_pemakaian' => $jumlahPemakaian,
                    'saldo_tersedia' => $saldoTersedia,
                    'total_harga' => $totalHarga,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Bulk insert untuk performance
            if (!empty($laporanData)) {
                LaporanTriwulan::insert($laporanData);
            }

            DB::commit();

            Log::info("Laporan triwulan berhasil digenerate", [
                'tahun' => $tahun,
                'triwulan' => $triwulan,
                'jumlah_barang' => count($laporanData)
            ]);

            return [
                'success' => true,
                'message' => "Laporan triwulan {$tahun} Q{$triwulan} berhasil digenerate untuk " . count($laporanData) . " barang",
                'data' => $laporanData
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error generate laporan triwulan", [
                'tahun' => $tahun,
                'triwulan' => $triwulan,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal generate laporan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get saldo akhir dari triwulan sebelumnya
     */
    private function getSaldoAkhirTriwulanSebelumnya($barangId, $tahun, $triwulan)
    {
        // Tentukan periode triwulan sebelumnya
        if ($triwulan == 1) {
            $tahunSebelumnya = $tahun - 1;
            $triwulanSebelumnya = 4;
        } else {
            $tahunSebelumnya = $tahun;
            $triwulanSebelumnya = $triwulan - 1;
        }

        // Cek apakah ada laporan triwulan sebelumnya
        $laporanSebelumnya = LaporanTriwulan::where('barang_id', $barangId)
            ->where('tahun', $tahunSebelumnya)
            ->where('triwulan', $triwulanSebelumnya)
            ->first();

        if ($laporanSebelumnya) {
            return $laporanSebelumnya->saldo_tersedia;
        }

        // Jika tidak ada laporan sebelumnya, hitung manual berdasarkan transaksi
        $tanggalRangeSebelumnya = $this->getTriwulanDateRange($tahunSebelumnya, $triwulanSebelumnya);

        // Get stok awal dari transaksi sampai akhir triwulan sebelumnya
        $totalPengadaan = MonitoringPengadaan::where('barang_id', $barangId)
            ->where('status', 'selesai')
            ->where('created_at', '<=', $tanggalRangeSebelumnya['selesai'])
            ->sum('debit');

        $totalPemakaian = MonitoringBarang::where('id_barang', $barangId)
            ->where('status', 'diterima')
            ->where('created_at', '<=', $tanggalRangeSebelumnya['selesai'])
            ->sum('kredit');

        // Asumsi stok awal 0, lalu ditambah pengadaan dikurangi pemakaian
        return max(0, $totalPengadaan - $totalPemakaian);
    }

    /**
     * Get range tanggal untuk triwulan tertentu
     */
    private function getTriwulanDateRange($tahun, $triwulan)
    {
        switch ($triwulan) {
            case 1: // Q1: Januari - Maret
                return [
                    'mulai' => Carbon::create($tahun, 1, 1)->startOfDay(),
                    'selesai' => Carbon::create($tahun, 3, 31)->endOfDay()
                ];
            case 2: // Q2: April - Juni
                return [
                    'mulai' => Carbon::create($tahun, 4, 1)->startOfDay(),
                    'selesai' => Carbon::create($tahun, 6, 30)->endOfDay()
                ];
            case 3: // Q3: Juli - September
                return [
                    'mulai' => Carbon::create($tahun, 7, 1)->startOfDay(),
                    'selesai' => Carbon::create($tahun, 9, 30)->endOfDay()
                ];
            case 4: // Q4: Oktober - Desember
                return [
                    'mulai' => Carbon::create($tahun, 10, 1)->startOfDay(),
                    'selesai' => Carbon::create($tahun, 12, 31)->endOfDay()
                ];
            default:
                throw new \InvalidArgumentException('Triwulan harus 1-4');
        }
    }

    /**
     * Generate laporan untuk triwulan saat ini secara otomatis
     */
    public function generateLaporanTriwulanSaatIni()
    {
        $now = Carbon::now();
        $tahun = $now->year;
        $triwulan = $this->getTriwulanSaatIni($now);

        return $this->generateLaporanTriwulan($tahun, $triwulan);
    }

    /**
     * Get triwulan berdasarkan tanggal
     */
    public function getTriwulanSaatIni($tanggal)
    {
        $bulan = $tanggal->month;

        if ($bulan >= 1 && $bulan <= 3) return 1;
        if ($bulan >= 4 && $bulan <= 6) return 2;
        if ($bulan >= 7 && $bulan <= 9) return 3;
        if ($bulan >= 10 && $bulan <= 12) return 4;

        return 1;
    }

    /**
     * Get laporan triwulan dengan filter
     */
    public function getLaporanTriwulan($tahun, $triwulan = null, $barangId = null)
    {
        $query = LaporanTriwulan::with('barang');

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        if ($triwulan) {
            $query->where('triwulan', $triwulan);
        }

        if ($barangId) {
            $query->where('barang_id', $barangId);
        }

        return $query->orderBy('tahun', 'desc')
            ->orderBy('triwulan', 'desc')
            ->orderBy('nama_barang')
            ->get();
    }

    /**
     * Get ringkasan laporan per tahun
     */
    public function getRingkasanPerTahun($tahun)
    {
        return LaporanTriwulan::where('tahun', $tahun)
            ->selectRaw('
                triwulan,
                COUNT(*) as jumlah_barang,
                SUM(saldo_akhir_sebelumnya) as total_saldo_awal,
                SUM(jumlah_pengadaan) as total_pengadaan,
                SUM(jumlah_pemakaian) as total_pemakaian,
                SUM(saldo_tersedia) as total_saldo_akhir,
                SUM(jumlah_harga) as total_nilai_awal,
                SUM(total_harga) as total_nilai_akhir
            ')
            ->groupBy('triwulan')
            ->orderBy('triwulan')
            ->get();
    }

    /**
     * Cek apakah laporan triwulan sudah ada
     */
    public function isLaporanExists($tahun, $triwulan)
    {
        return LaporanTriwulan::where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->exists();
    }

    /**
     * Get available tahun dan triwulan
     */
    public function getAvailablePeriodes()
    {
        return LaporanTriwulan::selectRaw('tahun, triwulan, COUNT(*) as jumlah_barang')
            ->groupBy('tahun', 'triwulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('triwulan', 'desc')
            ->get();
    }
}
