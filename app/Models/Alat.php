<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alat extends Model
{

    protected $fillable = [
        'nama_alat',
        'slug',
        'kategori',
        'deskripsi',
        'harga_sewa_per_hari',
        'stok_total',
        'foto_path',
        'is_active',
    ];

    protected $casts = [
        'harga_sewa_per_hari' => 'integer',
        'stok_total' => 'integer',
    ];

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function getFotoUrlAttribute($value)
    {
        if (!$value) {
            return asset('images/default-alat.png');
        }
        return $value;
    }
}
