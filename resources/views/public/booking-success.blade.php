@extends('layouts.public')

@section('title', 'Booking Berhasil')

@section('content')
    @php
        $paymentMethod = optional($booking->transaksi)->metode_pembayaran ?? 'cash';
        $isTransfer = $paymentMethod === 'transfer';
        $transferConfig = config('services.payment.transfer', []);
        $cashNote = config('services.payment.cash_note', 'Bayar cash saat pengambilan di gudang Bontang Outdoor.');
    @endphp
    <div class="min-h-screen flex items-center justify-center bg-primary-50 px-4 py-10">
        <div class="w-full max-w-3xl rounded-[32px] bg-white shadow-2xl border border-gray-100 overflow-hidden">
            <div class="p-8 md:p-12">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-full bg-emerald-100 flex items-center justify-center">
                        <svg class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Langkah 5</p>
                        <h1 class="text-3xl font-semibold text-gray-900">Booking berhasil dibuat</h1>
                    </div>
                </div>

                <p class="mt-4 text-gray-600">
                    Kode booking Anda sudah aktif dengan status <strong class="text-primary-600">PENDING</strong>.<br>
                    Silakan segera proses pembayaran atau konfirmasi booking Anda.<br>
                    Jika dalam waktu <strong>1×24 jam</strong> tidak ada pembayaran atau konfirmasi, sistem akan otomatis membatalkan booking Anda.<br>
                    Tim admin akan menghubungi Anda untuk validasi pembayaran.
                </p>

                <div class="mt-8 grid gap-6 md:grid-cols-2">
                    <div class="rounded-3xl border border-gray-100 bg-gray-50 p-5 space-y-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Kode Booking</p>
                            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $booking->booking_code }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Nama Penyewa</p>
                            <p class="mt-2 text-lg font-semibold text-gray-900">{{ $booking->nama_penyewa }}</p>
                            <p class="text-sm text-gray-500">WhatsApp: {{ $booking->no_hp }}</p>
                        </div>
                    </div>
                    <div class="rounded-3xl border border-gray-100 bg-gray-50 p-5 space-y-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Periode Sewa</p>
                            <p class="mt-2 text-lg font-semibold text-gray-900">
                                {{ optional($booking->tanggal_mulai)->format('d M Y') }} → {{ optional($booking->tanggal_selesai)->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-500">Durasi {{ $booking->total_hari }} hari</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Total Harga</p>
                            <p class="mt-2 text-2xl font-semibold text-gray-900">Rp {{ number_format($booking->total_harga ?? 0, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">Status pembayaran: {{ ucfirst(str_replace('_', ' ', $booking->transaksi->status_pembayaran ?? 'menunggu verifikasi')) }}</p>
                            <p class="text-sm text-gray-500">Metode: {{ $isTransfer ? 'Transfer bank' : 'Cash saat pickup' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-3xl border {{ $isTransfer ? 'border-primary-100 bg-primary-50 text-primary-800' : 'border-emerald-100 bg-emerald-50 text-emerald-800' }} p-6 text-sm space-y-2">
                    @if ($isTransfer)
                        <p class="font-semibold text-base text-primary-900">Instruksi transfer</p>
                        <p>{{ $transferConfig['bank'] ?? 'BCA' }} • {{ $transferConfig['account_number'] ?? '1234567890' }}</p>
                        <p>a.n {{ $transferConfig['account_name'] ?? 'Bontang Outdoor' }}</p>
                        <p>{{ $transferConfig['instructions'] ?? 'Setelah transfer, kirim bukti pembayaran melalui WhatsApp agar booking segera diproses.' }}</p>
                    @else
                        <p class="font-semibold text-base text-emerald-900">Bayar cash saat serah terima</p>
                        <p>{{ $cashNote }}</p>
                    @endif
                </div>

                <div class="mt-6 rounded-3xl border border-gray-100 bg-white p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Ringkasan Alat</h2>
                        <span class="text-xs text-gray-500">{{ $booking->bookingDetails->count() }} jenis</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        @foreach ($booking->bookingDetails as $detail)
                            <div class="flex items-center justify-between rounded-2xl border border-gray-100 bg-gray-50 px-4 py-2 text-sm text-gray-600">
                                <span>{{ $detail->alat->nama_alat ?? 'Peralatan' }} <span class="text-gray-400">x{{ $detail->jumlah }}</span></span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 rounded-3xl border border-primary-100 bg-primary-50 p-6 text-sm text-primary-800 space-y-3">
                    <p class="font-semibold">Apa selanjutnya?</p>
                    <ul class="list-disc list-inside space-y-2">
                        <li>
                            @if ($isTransfer)
                                Segera lakukan transfer lalu kirim bukti pembayaran agar admin bisa memverifikasi DP / pelunasan.
                            @else
                                Siapkan pembayaran cash saat pengambilan alat sesuai jadwal yang dipilih.
                            @endif
                        </li>
                        <li>Tunjukkan kode booking ini saat pengambilan alat.</li>
                        <li>Jika ingin mempercepat proses, kirim detail booking ke WhatsApp kami.</li>
                    </ul>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    @if (isset($receiptUrl))
                        <a href="{{ $receiptUrl }}" target="_blank"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-primary-200 px-6 py-3 text-primary-700 font-semibold hover:bg-primary-50">
                            <i class="fas fa-file-download text-lg"></i>
                            Download Bukti (PDF)
                        </a>
                    @endif
                    <a href="{{ $whatsappLink }}" target="_blank" rel="noopener"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-white font-semibold shadow-lg hover:bg-emerald-700">
                        <i class="fab fa-whatsapp text-lg"></i>
                        Kirim ke WhatsApp
                    </a>
                    <a href="{{ route('public.home') }}#products"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-gray-300 px-6 py-3 text-gray-700 font-semibold hover:bg-gray-50">
                        Lihat alat lainnya
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
