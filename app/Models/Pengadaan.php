<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    use HasFactory;

    protected $table = 'pengadaans';

    protected $fillable = [
        'monitoring_barang_id',
        'tanggal',
        'debit',
        'status'
    ];

    public function monitoring_barang()
    {
        return $this->belongsTo(MonitoringBarang::class);
    }
}
