<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    use HasFactory;

    protected $table = 'monitoring';
    protected $primaryKey = 'id_monitoring';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_monitoring',
        'id_pengambilan',
        'tanggal',
        'bidang',
        'pengambil',
        'id_barang',
        'debit',
        'kredit',
        'saldo',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'debit' => 'integer',
        'kredit' => 'integer',
        'saldo' => 'integer',
        'status' => 'string',
    ];

    // Relationship dengan tabel barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    // Relationship dengan tabel pengambilan
    public function pengambilan()
    {
        return $this->belongsTo(Pengambilan::class, 'id_pengambilan', 'id_pengambilan');
    }

    // Generate ID monitoring otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_monitoring)) {
                $lastRecord = static::orderBy('id_monitoring', 'desc')->first();
                if ($lastRecord) {
                    $lastNumber = (int) substr($lastRecord->id_monitoring, 3);
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }
                $model->id_monitoring = 'MON' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
