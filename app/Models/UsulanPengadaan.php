<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanPengadaan extends Model
{
    use HasFactory;

    protected $table = 'usulan_pengadaan';

    protected $fillable = [
        'nama_barang',
        'jenis',
        'jumlah',
        'satuan',
        'status',
        'bidang',
        'keterangan',
        'user_id',
        'processed_by',
        'processed_at'
    ];

    protected $dates = [
        'processed_at',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function barang()
    {
        // Mencoba mencari barang berdasarkan nama_barang dan jenis yang cocok
        return $this->hasOne(Barang::class, 'nama_barang', 'nama_barang')
            ->where('jenis', $this->jenis);
    }
}
