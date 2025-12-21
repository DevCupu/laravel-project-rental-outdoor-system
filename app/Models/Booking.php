<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'nama_penyewa',
        'no_hp',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_hari',
        'total_harga',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'total_hari' => 'integer',
        'total_harga' => 'integer',
    ];

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class);
    }

    public function getExpiresAtAttribute(): ?Carbon
    {
        $minutes = (int) config('booking.pending_expiry_minutes', 120);

        if (!$minutes || !$this->created_at) {
            return null;
        }

        return Carbon::parse($this->created_at)->addMinutes($minutes);
    }
}
