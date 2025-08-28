<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id_barang';
    }

    protected $fillable = [
        'id_barang',
        'nama_barang',
        'satuan',
        'harga_barang',
        'jenis',
        'foto'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id_barang) {
                // Generate ID: BRG-[jenis]-[random string]
                $jenis = substr($model->jenis, 0, 3); // Mengambil 3 huruf pertama dari jenis
                $model->id_barang = 'BRG-' . strtoupper($jenis) . '-' . strtoupper(Str::random(5));
            }
        });
    }
}
