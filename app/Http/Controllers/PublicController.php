<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Transaksi;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function index()
    {
        $alats = Alat::orderBy('nama_alat')->get();

        return view('public.home', compact('alats'));
    }

    public function show(Alat $alat)
    {
        return view('public.alats.show', compact('alat'));
    }

    public function bookingForm()
    {
        $alats = Alat::orderBy('nama_alat')->get();
        $transferConfig = config('services.payment.transfer', []);
        $cashNote = config('services.payment.cash_note', 'Bayar cash saat pengambilan di gudang Bontang Outdoor.');
        $selectedPayment = old('metode_pembayaran', 'cash');

        return view('public.booking-form', compact('alats', 'transferConfig', 'cashNote', 'selectedPayment'));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'nama_penyewa' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:30'],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alat_id' => ['required'],
            'jumlah' => ['required'],
            'metode_pembayaran' => ['required', 'in:cash,transfer'],
        ]);

        $alatIds = Arr::wrap($request->input('alat_id'));
        $quantities = Arr::wrap($request->input('jumlah'));

        if (count($alatIds) !== count($quantities)) {
            return back()->withInput()->with('error', 'Jumlah alat tidak sesuai.');
        }

        $startDate = Carbon::parse($request->tanggal_mulai)->startOfDay();
        $endDate = Carbon::parse($request->tanggal_selesai)->startOfDay();
        $duration = max(1, $startDate->diffInDays($endDate));

        DB::beginTransaction();

        try {
            $bookingDetailsPayload = [];
            $totalHarga = 0;

            foreach ($alatIds as $index => $alatId) {
                $alat = Alat::findOrFail($alatId);
                $jumlah = max(1, (int) ($quantities[$index] ?? 1));

                $available = $this->calculateAvailableStock($alat, $startDate, $endDate);
                if ($jumlah > $available) {
                    DB::rollBack();
                    return back()->withInput()->with('error', 'Stok ' . $alat->nama_alat . ' tersisa ' . $available . ' unit pada tanggal tersebut.');
                }

                $subtotal = $alat->harga_sewa_per_hari * $jumlah * $duration;
                $bookingDetailsPayload[] = [
                    'alat_id' => $alat->id,
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal,
                ];

                $totalHarga += $subtotal;
            }

            $booking = Booking::create([
                'booking_code' => $this->generateBookingCode(),
                'nama_penyewa' => $request->nama_penyewa,
                'no_hp' => $request->no_hp,
                'tanggal_mulai' => $startDate->toDateString(),
                'tanggal_selesai' => $endDate->toDateString(),
                'total_hari' => $duration,
                'total_harga' => $totalHarga,
                'status' => 'pending',
            ]);

            foreach ($bookingDetailsPayload as $detail) {
                $booking->bookingDetails()->create($detail);
            }

            $booking->transaksi()->create([
                'total' => $totalHarga,
                'status_pembayaran' => 'menunggu_verifikasi',
                'metode_pembayaran' => $request->metode_pembayaran,
            ]);

            DB::commit();

            session(['recent_booking_id' => $booking->id]);

            return redirect()->route('booking.success');
        } catch (\Throwable $exception) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan. ' . $exception->getMessage());
        }
    }

    public function success()
    {
        $bookingId = session('recent_booking_id');

        if (!$bookingId) {
            return redirect()->route('public.home')->with('warning', 'Halaman tidak dapat diakses langsung.');
        }

        $booking = Booking::with(['bookingDetails.alat', 'transaksi'])->findOrFail($bookingId);
        session()->forget('recent_booking_id');

        $whatsappLink = $this->buildWhatsappLink($booking);
        $receiptUrl = URL::temporarySignedRoute('booking.receipt', now()->addDay(), ['booking' => $booking->id]);

        return view('public.booking-success', compact('booking', 'whatsappLink', 'receiptUrl'));
    }

    public function downloadReceipt(Request $request, Booking $booking)
    {
        $booking->load(['bookingDetails.alat', 'transaksi']);

        $paymentMethod = optional($booking->transaksi)->metode_pembayaran ?? 'cash';
        $transferConfig = config('services.payment.transfer', []);
        $cashNote = config('services.payment.cash_note', 'Bayar cash saat pengambilan di gudang Bontang Outdoor.');

        $html = view('public.booking-receipt', compact('booking', 'paymentMethod', 'transferConfig', 'cashNote'))->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Bukti-Booking-' . $booking->booking_code . '.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function checkAvailability(Request $request, Alat $alat)
    {
        $request->validate([
            'start' => ['required', 'date', 'after_or_equal:today'],
            'end' => ['required', 'date', 'after_or_equal:start'],
        ]);

        $startDate = Carbon::parse($request->query('start'))->startOfDay();
        $endDate = Carbon::parse($request->query('end'))->startOfDay();

        $available = $this->calculateAvailableStock($alat, $startDate, $endDate);

        return response()->json([
            'available' => $available,
            'status' => $available > 0 ? 'available' : 'unavailable',
        ]);
    }

    protected function calculateAvailableStock(Alat $alat, Carbon $startDate, Carbon $endDate): int
    {
        $start = $startDate->toDateString();
        $end = $endDate->toDateString();

        $lockedQuantity = BookingDetail::where('alat_id', $alat->id)
            ->whereHas('booking', function ($query) use ($start, $end) {
                $query->whereIn('status', ['pending', 'confirmed', 'picked_up'])
                    ->where(function ($dateQuery) use ($start, $end) {
                        $dateQuery->whereBetween('tanggal_mulai', [$start, $end])
                            ->orWhereBetween('tanggal_selesai', [$start, $end])
                            ->orWhere(function ($overlapQuery) use ($start, $end) {
                                $overlapQuery->where('tanggal_mulai', '<=', $start)
                                    ->where('tanggal_selesai', '>=', $end);
                            });
                    });
            })
            ->sum('jumlah');

        return max($alat->stok_total - $lockedQuantity, 0);
    }

    protected function generateBookingCode(): string
    {
        do {
            $code = 'BO-' . Str::upper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }

    protected function buildWhatsappLink(Booking $booking): string
    {
        $metodePembayaran = optional($booking->transaksi)->metode_pembayaran === 'transfer'
            ? 'Transfer Bank'
            : 'Cash saat pickup';

        $alatList = $booking->bookingDetails->map(function ($detail) {
            $name = $detail->alat->nama_alat ?? 'Peralatan';
            return $name . ' x' . $detail->jumlah;
        })->implode(', ');

        $lines = [
            'Halo Bontang Outdoor',
            '',
            'Saya ' . $booking->nama_penyewa . ' telah membuat booking dengan kode ' . $booking->booking_code . '.',
            'Detail alat: ' . ($alatList ?: '-'),
            'Periode: ' . optional($booking->tanggal_mulai)->format('d M Y') . ' - ' . optional($booking->tanggal_selesai)->format('d M Y'),
            'Total: Rp ' . number_format($booking->total_harga ?? 0, 0, ',', '.'),
            'Metode pembayaran: ' . $metodePembayaran,
            '',
            'Mohon informasi langkah selanjutnya. Terima kasih!',
        ];

        $message = rawurlencode(implode(PHP_EOL, $lines));
        $number = config('services.whatsapp.booking_number', '6281234567890');

        return 'https://wa.me/' . $number . '?text=' . $message;
    }
}
