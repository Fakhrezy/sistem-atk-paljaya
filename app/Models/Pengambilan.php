<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengambilan extends Model
{
    use HasFactory;

    protected $table = 'pengambilan';
    protected $primaryKey = 'id_pengambilan';

    protected $fillable = [
        'nama_pengambil',
    ];

    // Relasi ke tabel monitoring
    public function monitoring()
    {
        return $this->hasMany(Monitoring::class, 'id_pengambilan', 'id_pengambilan');
    }
}
