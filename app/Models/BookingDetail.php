<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    protected $fillable = ['booking_id', 'alat_id', 'jumlah', 'subtotal'];

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
