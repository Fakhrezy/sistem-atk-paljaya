<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeranjangUsulan extends Model
{
    use HasFactory;

    protected $table = 'keranjang_usulan';

    protected $fillable = [
        'user_id',
        'id_barang',
        'jumlah',
        'keterangan'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Get the user that owns this cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the barang details for this cart item.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
