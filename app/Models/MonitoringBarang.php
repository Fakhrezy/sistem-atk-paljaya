<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringBarang extends Model
{
    use HasFactory;

    protected $table = 'monitoring_barang';

    protected $fillable = [
        'nama_barang',
        'jenis_barang',
        'nama_pengambil',
        'bidang',
        'tanggal_ambil',
        'saldo',
        'saldo_akhir',
        'kredit',
        'status'
    ];

    protected $casts = [
        'tanggal_ambil' => 'date',
    ];
}
