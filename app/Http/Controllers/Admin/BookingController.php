<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $relations = ['bookingDetails.alat', 'transaksi'];

        $applySearch = function ($query) use ($search) {
            if (!$search) {
                return $query;
            }

            return $query->where(function ($q) use ($search) {
                $q->where('nama_penyewa', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('booking_code', 'like', "%{$search}%");
            });
        };

        $pendingCount = Booking::where('status', 'pending')->count();
        $confirmedCount = Booking::where('status', 'confirmed')->count();
        $pickedUpCount = Booking::where('status', 'picked_up')->count();
        $returnedCount = Booking::where('status', 'returned')->count();
        $cancelledCount = Booking::where('status', 'cancelled')->count();

        $pendingBookings = $applySearch(Booking::with($relations)->where('status', 'pending'))
            ->latest()
            ->paginate(5, ['*'], 'pending_page')
            ->withQueryString();

        $confirmedBookings = $applySearch(Booking::with($relations)->where('status', 'confirmed'))
            ->latest()
            ->paginate(5, ['*'], 'confirmed_page')
            ->withQueryString();

        $pickedUpBookings = $applySearch(Booking::with($relations)->where('status', 'picked_up'))
            ->latest()
            ->paginate(5, ['*'], 'picked_up_page')
            ->withQueryString();

        $returnedBookings = $applySearch(Booking::with($relations)->where('status', 'returned'))
            ->latest()
            ->paginate(5, ['*'], 'returned_page')
            ->withQueryString();

        $cancelledBookings = $applySearch(Booking::with($relations)->where('status', 'cancelled'))
            ->latest()
            ->paginate(5, ['*'], 'cancelled_page')
            ->withQueryString();

        return view('admin.booking.index', compact(
            'pendingBookings',
            'confirmedBookings',
            'pickedUpBookings',
            'returnedBookings',
            'cancelledBookings',
            'pendingCount',
            'confirmedCount',
            'pickedUpCount',
            'returnedCount',
            'cancelledCount',
            'search'
        ));
    }

    public function show($id)
    {
        $booking = Booking::with(['bookingDetails.alat', 'transaksi'])->findOrFail($id);

        return view('admin.booking.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        return view('booking.edit', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,picked_up,returned,cancelled'
        ]);

        $booking->update(['status' => $request->status]);

        return redirect()->route('booking.index')->with('success', 'Status booking diperbarui.');
    }

    public function destroy($id)
    {
        $booking = Booking::with(['bookingDetails', 'transaksi'])->findOrFail($id);

        if (!in_array($booking->status, ['returned', 'cancelled'])) {
            return back()->with('error', 'Hanya booking pada riwayat (dikembalikan/dibatalkan) yang bisa dihapus.');
        }

        DB::transaction(function () use ($booking) {
            $booking->bookingDetails()->delete();
            if ($booking->transaksi) {
                $booking->transaksi->delete();
            }
            $booking->delete();
        });

        return redirect()->route('admin.booking.index')->with('success', 'Booking riwayat berhasil dihapus.');
    }

    public function cancel($id)
    {
        DB::beginTransaction();

        try {
            $booking = Booking::with('bookingDetails.alat')->findOrFail($id);

            if (in_array($booking->status, ['cancelled', 'returned'])) {
                return back()->with('error', 'Booking tidak bisa dibatalkan.');
            }

            if ($booking->status === 'picked_up') {
                return back()->with('error', 'Booking sudah diambil, gunakan proses pengembalian.');
            }

            $booking->status = 'cancelled';
            $booking->save();

            DB::commit();

            return redirect()->route('admin.booking.index')
                ->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan booking. ' . $e->getMessage());
        }
    }


    public function approveBooking($id)
    {
        $booking = Booking::with(['bookingDetails', 'transaksi'])->findOrFail($id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking sudah diproses sebelumnya.');
        }

        $booking->status = 'confirmed';
        $booking->save();

        if ($booking->transaksi) {
            $booking->transaksi->update(['status_pembayaran' => 'terverifikasi']);
        }

        return back()->with('success', 'Booking berhasil dikonfirmasi.');
    }

    public function pickupBooking($id)
    {
        $booking = Booking::with('bookingDetails.alat')->findOrFail($id);

        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Hanya booking yang sudah dikonfirmasi yang dapat ditandai diambil.');
        }

        DB::beginTransaction();

        try {
            foreach ($booking->bookingDetails as $detail) {
                $alat = $detail->alat;

                if (!$alat || $alat->stok_total < $detail->jumlah) {
                    DB::rollBack();
                    return back()->with('error', 'Stok alat tidak mencukupi untuk diserahkan.');
                }

                $alat->stok_total -= $detail->jumlah;
                $alat->save();
            }

            $booking->status = 'picked_up';
            $booking->save();

            DB::commit();

            return back()->with('success', 'Booking ditandai sudah diambil.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status. ' . $e->getMessage());
        }
    }

    public function returnBooking($id)
    {
        DB::beginTransaction();

        try {
            $booking = Booking::with('bookingDetails.alat')->findOrFail($id);

            if ($booking->status !== 'picked_up') {
                return back()->with('error', 'Hanya booking yang sedang dipinjam yang bisa dikembalikan.');
            }

            foreach ($booking->bookingDetails as $detail) {
                $alat = $detail->alat;
                $alat->stok_total += $detail->jumlah;
                $alat->save();
            }

            $booking->status = 'returned';
            $booking->save();

            DB::commit();

            return back()->with('success', 'Barang berhasil dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengembalikan barang. ' . $e->getMessage());
        }
    }
}
