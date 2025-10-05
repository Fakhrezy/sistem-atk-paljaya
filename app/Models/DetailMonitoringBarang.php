<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailMonitoringBarang extends Model
{
    use HasFactory;

    protected $table = 'detail_monitoring_barang';

    protected $fillable = [
        'nama_barang',
        'tanggal',
        'saldo',
        'keterangan',
        'bidang',
        'pengambil',
        'debit',
        'kredit',
        'id_barang',
        'monitoring_barang_id',
        'monitoring_pengadaan_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'saldo' => 'integer',
        'debit' => 'integer',
        'kredit' => 'integer',
    ];

    /**
     * Relasi ke tabel barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    /**
     * Relasi ke monitoring_barang
     */
    public function monitoringBarang()
    {
        return $this->belongsTo(MonitoringBarang::class, 'monitoring_barang_id');
    }

    /**
     * Relasi ke monitoring_pengadaan
     */
    public function monitoringPengadaan()
    {
        return $this->belongsTo(MonitoringPengadaan::class, 'monitoring_pengadaan_id');
    }

    /**
     * Scope untuk mengurutkan berdasarkan tanggal dan nama barang
     */
    public function scopeOrderedByDate($query)
    {
        return $query->orderBy('tanggal', 'desc')->orderBy('nama_barang', 'asc');
    }

    /**
     * Scope untuk filter berdasarkan barang tertentu
     */
    public function scopeByBarang($query, $idBarang)
    {
        return $query->where('id_barang', $idBarang);
    }

    /**
     * Scope untuk filter berdasarkan rentang tanggal
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }
}
