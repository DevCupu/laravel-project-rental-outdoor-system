<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::query();

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_bayar', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_bayar', $request->tahun);
        }

        $transaksis = $query->with('booking')->get();
        $totalPendapatan = $transaksis->sum('total');

        return view('transaksi.index', compact('transaksis', 'totalPendapatan'));
    }
}
