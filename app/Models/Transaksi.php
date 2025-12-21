<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['booking_id', 'total', 'status_pembayaran', 'metode_pembayaran', 'tanggal_bayar'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
