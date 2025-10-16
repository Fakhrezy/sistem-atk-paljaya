<?php

namespace App\Services;

use App\Models\DetailMonitoringBarang;
use App\Models\MonitoringBarang;
use App\Models\MonitoringPengadaan;
use App\Models\Barang;
use Carbon\Carbon;

class DetailMonitoringBarangService
{
    /**
     * Sinkronisasi data dari monitoring_barang ke detail_monitoring_barang
     */
    public function syncFromMonitoringBarang($monitoringBarangId = null)
    {
        $query = MonitoringBarang::with('barang');

        if ($monitoringBarangId) {
            $query->where('id', $monitoringBarangId);
        } else {
            // Hanya ambil monitoring barang yang sudah disetujui/diterima
            $query->whereIn('status', ['disetujui', 'terima', 'diterima']);
        }

        $monitoringItems = $query->get();

        foreach ($monitoringItems as $item) {
            // Cek apakah sudah ada record untuk monitoring_barang ini
            $existing = DetailMonitoringBarang::where('monitoring_barang_id', $item->id)->first();

            $data = [
                'nama_barang' => $item->nama_barang,
                'tanggal' => $item->tanggal_ambil,
                'saldo' => $item->barang ? $item->barang->stok : $item->saldo_akhir,
                'keterangan' => $item->keterangan,
                'bidang' => $item->bidang,
                'pengambil' => $item->nama_pengambil,
                'debit' => 0, // Tidak ada debit dari monitoring barang
                'kredit' => $item->kredit,
                'id_barang' => $item->id_barang,
                'monitoring_barang_id' => $item->id,
                'monitoring_pengadaan_id' => null,
            ];

            if ($existing) {
                $existing->update($data);
            } else {
                DetailMonitoringBarang::create($data);
            }
        }
    }

    /**
     * Sinkronisasi data dari monitoring_pengadaan ke detail_monitoring_barang
     */
    public function syncFromMonitoringPengadaan($monitoringPengadaanId = null)
    {
        $query = MonitoringPengadaan::with('barang');

        if ($monitoringPengadaanId) {
            $query->where('id', $monitoringPengadaanId);
        } else {
            // Hanya ambil monitoring pengadaan yang sudah selesai
            $query->where('status', 'selesai');
        }

        $monitoringItems = $query->get();

        foreach ($monitoringItems as $item) {
            // Cek apakah sudah ada record untuk monitoring_pengadaan ini
            $existing = DetailMonitoringBarang::where('monitoring_pengadaan_id', $item->id)->first();

            $data = [
                'nama_barang' => $item->barang ? $item->barang->nama_barang : 'Barang tidak ditemukan',
                'tanggal' => $item->tanggal, // Gunakan kolom 'tanggal' yang ada di tabel
                'saldo' => $item->barang ? $item->barang->stok : ($item->saldo_akhir ?? 0),
                'keterangan' => $item->keterangan ?? null,
                'bidang' => null, // Tidak ada bidang di monitoring pengadaan
                'pengambil' => null, // Tidak ada pengambil di monitoring pengadaan
                'debit' => $item->debit ?? 0, // Mengambil dari kolom debit di monitoring_pengadaan
                'kredit' => 0, // Tidak ada kredit di monitoring pengadaan
                'id_barang' => $item->barang_id, // Gunakan kolom 'barang_id' yang ada di tabel
                'monitoring_barang_id' => null,
                'monitoring_pengadaan_id' => $item->id,
            ];

            if ($existing) {
                $existing->update($data);
            } else {
                DetailMonitoringBarang::create($data);
            }
        }
    }
    /**
     * Sinkronisasi semua data monitoring
     */
    public function syncAllData()
    {
        $this->syncFromMonitoringBarang();
        $this->syncFromMonitoringPengadaan();
    }

    /**
     * Hapus record detail monitoring berdasarkan source
     */
    public function deleteByMonitoringBarang($monitoringBarangId)
    {
        DetailMonitoringBarang::where('monitoring_barang_id', $monitoringBarangId)->delete();
    }

    public function deleteByMonitoringPengadaan($monitoringPengadaanId)
    {
        DetailMonitoringBarang::where('monitoring_pengadaan_id', $monitoringPengadaanId)->delete();
    }

    /**
     * Ambil data detail monitoring dengan filter
     */
    public function getDetailMonitoring($filters = [])
    {
        $query = DetailMonitoringBarang::with(['barang', 'monitoringBarang', 'monitoringPengadaan']);

        // Filter berdasarkan barang
        if (isset($filters['id_barang']) && $filters['id_barang']) {
            $query->where('id_barang', $filters['id_barang']);
        }

        // Filter berdasarkan rentang tanggal
        if (isset($filters['start_date']) && $filters['start_date']) {
            $query->where('tanggal', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date']) && $filters['end_date']) {
            $query->where('tanggal', '<=', $filters['end_date']);
        }

        // Filter berdasarkan bidang
        if (isset($filters['bidang']) && $filters['bidang']) {
            $query->where('bidang', $filters['bidang']);
        }

        // Filter berdasarkan jenis (debit/kredit)
        if (isset($filters['jenis']) && $filters['jenis']) {
            if ($filters['jenis'] === 'debit') {
                $query->where('debit', '>', 0);
            } elseif ($filters['jenis'] === 'kredit') {
                $query->where('kredit', '>', 0);
            }
        }

        // Urutkan berdasarkan tanggal dan nama barang
        $query->orderBy('tanggal', 'desc')->orderBy('nama_barang', 'asc');

        return $query;
    }

    /**
     * Update saldo berdasarkan stok terkini dari tabel barang
     */
    public function updateSaldoFromBarang()
    {
        $details = DetailMonitoringBarang::with('barang')->get();

        foreach ($details as $detail) {
            if ($detail->barang) {
                $detail->update(['saldo' => $detail->barang->stok]);
            }
        }
    }

    /**
     * Sinkronisasi otomatis ketika status monitoring barang berubah
     */
    public function syncOnStatusChange($monitoringType, $monitoringId, $newStatus)
    {
        if ($monitoringType === 'barang') {
            // Jika status menjadi disetujui/terima/diterima, tambahkan ke detail monitoring
            if (in_array($newStatus, ['disetujui', 'terima', 'diterima'])) {
                $this->syncFromMonitoringBarang($monitoringId);
            }
            // Jika status berubah dari disetujui/terima/diterima ke status lain, hapus dari detail monitoring
            else {
                $this->deleteByMonitoringBarang($monitoringId);
            }
        } elseif ($monitoringType === 'pengadaan') {
            // Jika status menjadi selesai, tambahkan ke detail monitoring
            if ($newStatus === 'selesai') {
                $this->syncFromMonitoringPengadaan($monitoringId);
            }
            // Jika status berubah dari selesai ke status lain, hapus dari detail monitoring
            else {
                $this->deleteByMonitoringPengadaan($monitoringId);
            }
        }
    }
}
