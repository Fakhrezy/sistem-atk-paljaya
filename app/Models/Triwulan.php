<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Triwulan extends Model
{
    use HasFactory;

    protected $table = 'triwulan';

    protected $fillable = [
        'id_barang',
        'nama_barang',
        'satuan',
        'harga_satuan',
        'tahun',
        'triwulan',
        'saldo_awal_triwulan',
        'total_kredit_triwulan',
        'total_harga_kredit',
        'total_debit_triwulan',
        'total_harga_debit',
        'total_persediaan_triwulan',
        'total_harga_persediaan'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total_harga_kredit' => 'decimal:2',
        'total_harga_debit' => 'decimal:2',
        'total_harga_persediaan' => 'decimal:2',
        'tahun' => 'integer',
        'triwulan' => 'integer',
        'saldo_awal_triwulan' => 'integer',
        'total_kredit_triwulan' => 'integer',
        'total_debit_triwulan' => 'integer',
        'total_persediaan_triwulan' => 'integer'
    ];

    // Relasi dengan model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    // Method untuk mendapatkan nama triwulan
    public function getNamaTriwulanAttribute()
    {
        return "Triwulan {$this->triwulan} Tahun {$this->tahun}";
    }

    // Method untuk menghitung total harga kredit
    public function calculateTotalHargaKredit()
    {
        return $this->harga_satuan * $this->total_kredit_triwulan;
    }

    // Method untuk menghitung total harga debit
    public function calculateTotalHargaDebit()
    {
        return $this->harga_satuan * $this->total_debit_triwulan;
    }

    // Method untuk menghitung total persediaan
    public function calculateTotalPersediaan()
    {
        return $this->saldo_awal_triwulan + $this->total_debit_triwulan - $this->total_kredit_triwulan;
    }

    // Method untuk menghitung total harga persediaan
    public function calculateTotalHargaPersediaan()
    {
        return $this->harga_satuan * $this->calculateTotalPersediaan();
    }

    // Scope untuk filter berdasarkan tahun dan triwulan
    public function scopeByPeriod($query, $tahun, $triwulan = null)
    {
        $query->where('tahun', $tahun);

        if ($triwulan) {
            $query->where('triwulan', $triwulan);
        }

        return $query;
    }
}
