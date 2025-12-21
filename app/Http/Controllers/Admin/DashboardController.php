<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaksi;
use App\Models\Alat;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooking = Booking::count();
        $totalTransaksi = Transaksi::sum('total');

        // Tambahan:
        $totalAlat = Alat::count();

        // // misal user biasa punya role 'user'
        // $totalUsers = User::where('role', 'user')->count(); // Opsional

        // Stok hampir habis (misalnya stok <= 3)
        $stokHampirHabis = Alat::where('stok_total', '<=', 3)->get();

        // 5 booking terbaru
        // $bookingTerbaru = Booking::with(['user', 'alat'])->latest()->take(5)->get();

        $pendingBookings = Booking::where('status', 'pending')->get();


        // Hitung total stok alat yang tersedia
        $alatTersedia = Alat::sum('stok_total');

        $pendingBookings = Booking::with('bookingDetails.alat')
            ->where('status', 'pending')->get();

        $approvedBookings = Booking::with('bookingDetails.alat')
            ->where('status', 'disetujui')->get();

        $cancelledBookings = Booking::with('bookingDetails.alat')
            ->where('status', 'dibatalkan')->get();

        $kembaliBookings = Booking::with('bookingDetails.alat')
            ->where('status', 'kembali')->get();


        return view('admin.dashboard', compact(
            'totalBooking',
            'totalTransaksi',
            'totalAlat',
            'stokHampirHabis',
            'alatTersedia',
            'pendingBookings',
            'approvedBookings',
            'cancelledBookings',
            'kembaliBookings'
        ));
    }
}
