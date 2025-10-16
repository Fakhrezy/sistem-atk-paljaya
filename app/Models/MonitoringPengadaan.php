<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringPengadaan extends Model
{
    use HasFactory;

    protected $table = 'monitoring_pengadaan';

    protected $fillable = [
        'user_id',
        'barang_id',
        'saldo',
        'debit',
        'saldo_akhir',
        'keterangan',
        'status',
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }
}
