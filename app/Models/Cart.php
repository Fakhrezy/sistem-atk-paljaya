<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_barang',
        'quantity',
        'bidang',
        'keterangan',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Relationship ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship ke barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    // Scope untuk mendapatkan cart user tertentu
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Get total quantity in cart for a user
    public static function getTotalQuantityForUser($userId)
    {
        return static::forUser($userId)->sum('quantity');
    }

    // Get total items count in cart for a user
    public static function getTotalItemsForUser($userId)
    {
        return static::forUser($userId)->count();
    }
}
