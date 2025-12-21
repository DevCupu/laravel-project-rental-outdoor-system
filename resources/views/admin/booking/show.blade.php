@extends('layouts.admin')

@section('content')
@php
    $statusMap = [
        'pending' => ['label' => 'Menunggu Konfirmasi', 'tone' => 'bg-amber-100 text-amber-800'],
        'confirmed' => ['label' => 'Terkonfirmasi', 'tone' => 'bg-emerald-100 text-emerald-700'],
        'picked_up' => ['label' => 'Sedang Dipinjam', 'tone' => 'bg-blue-100 text-blue-700'],
        'returned' => ['label' => 'Dikembalikan', 'tone' => 'bg-sky-100 text-sky-700'],
        'cancelled' => ['label' => 'Dibatalkan', 'tone' => 'bg-rose-100 text-rose-700'],
        'disetujui' => ['label' => 'Disetujui (legacy)', 'tone' => 'bg-emerald-100 text-emerald-700'],
        'kembali' => ['label' => 'Sudah Dikembalikan', 'tone' => 'bg-sky-100 text-sky-700'],
        'dibatalkan' => ['label' => 'Dibatalkan', 'tone' => 'bg-rose-100 text-rose-700'],
    ];

    $statusInfo = $statusMap[$booking->status] ?? ['label' => ucfirst($booking->status ?? 'Tidak diketahui'), 'tone' => 'bg-gray-100 text-gray-700'];

    $bookingTotal = $booking->total_harga
        ?? optional($booking->transaksi)->total
        ?? $booking->bookingDetails->sum('subtotal');

    $totalItems = $booking->bookingDetails->sum('jumlah');
@endphp

<div class="space-y-8">
    <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
        <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Detail Booking</p>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $booking->nama_penyewa }}</h1>
                <p class="text-sm text-gray-500">No. HP: {{ $booking->no_hp ?? 'Tidak tersedia' }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="px-4 py-2 rounded-full text-xs font-semibold {{ $statusInfo['tone'] }}">
                    {{ $statusInfo['label'] }}
                </span>

                @if ($booking->status === 'pending')
                    <a href="{{ route('admin.booking.approve', $booking->id) }}" onclick="return confirm('Setujui booking ini?')"
                        class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                        Setujui
                    </a>
                    <a href="{{ route('admin.booking.cancel', $booking->id) }}" onclick="return confirm('Batalkan booking ini?')"
                        class="px-4 py-2 rounded-lg bg-rose-600 text-white text-sm font-medium hover:bg-rose-700">
                        Batalkan
                    </a>
                @elseif ($booking->status === 'confirmed')
                    <a href="{{ route('admin.booking.pickup', $booking->id) }}" onclick="return confirm('Tandai alat sudah diambil?')"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                        Tandai Diambil
                    </a>
                    <a href="{{ route('admin.booking.cancel', $booking->id) }}" onclick="return confirm('Batalkan booking ini?')"
                        class="px-4 py-2 rounded-lg bg-rose-600 text-white text-sm font-medium hover:bg-rose-700">
                        Batalkan
                    </a>
                @elseif ($booking->status === 'picked_up')
                    <a href="{{ route('admin.booking.return', $booking->id) }}" onclick="return confirm('Tandai sudah dikembalikan?')"
                        class="px-4 py-2 rounded-lg bg-sky-600 text-white text-sm font-medium hover:bg-sky-700">
                        Tandai Kembali
                    </a>
                @endif
                <a href="{{ route('admin.booking.index') }}"
                    class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                    Kembali
                </a>
            </div>
        </div>

        @if ($booking->status === 'pending' && $booking->expires_at)
            <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                <p class="font-semibold">
                    {{ $booking->expires_at->isPast() ? 'Lewat jatuh tempo konfirmasi' : 'Menunggu konfirmasi pembayaran' }}
                </p>
                <p class="mt-1 text-amber-900">
                    Auto batal {{ $booking->expires_at->diffForHumans(null, ['syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW]) }}
                    <span class="font-medium text-amber-700">({{ $booking->expires_at->format('d M Y H:i') }})</span>
                </p>
            </div>
        @endif
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-900">Informasi Penyewaan</h2>
            <dl class="mt-6 grid gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-[0.3em] text-gray-400">Tanggal Mulai</dt>
                    <dd class="mt-2 text-gray-900">{{ optional($booking->tanggal_mulai)->format('d M Y') ?? 'Belum ditentukan' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.3em] text-gray-400">Tanggal Selesai</dt>
                    <dd class="mt-2 text-gray-900">{{ optional($booking->tanggal_selesai)->format('d M Y') ?? 'Belum ditentukan' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.3em] text-gray-400">Dibuat</dt>
                    <dd class="mt-2 text-gray-900">{{ optional($booking->created_at)->format('d M Y H:i') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.3em] text-gray-400">Terakhir Diperbarui</dt>
                    <dd class="mt-2 text-gray-900">{{ optional($booking->updated_at)->format('d M Y H:i') ?? '-' }}</dd>
                </div>
            </dl>
        </div>
        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Ringkasan</h2>
            <dl class="mt-6 space-y-4">
                <div class="flex items-center justify-between">
                    <dt class="text-sm text-gray-600">Total Item</dt>
                    <dd class="text-sm font-semibold text-gray-900">{{ $totalItems }} unit</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-sm text-gray-600">Total Harga</dt>
                    <dd class="text-sm font-semibold text-gray-900">Rp {{ number_format($bookingTotal ?? 0, 0, ',', '.') }}</dd>
                </div>
                @if ($booking->status === 'pending' && $booking->expires_at)
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Auto batal</dt>
                        <dd class="text-sm font-semibold {{ $booking->expires_at->isPast() ? 'text-rose-600' : 'text-amber-700' }}">
                            {{ $booking->expires_at->diffForHumans(null, ['syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW]) }}
                        </dd>
                    </div>
                @endif
                @if ($booking->transaksi)
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Status Pembayaran</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $booking->transaksi->status_pembayaran ?? '-' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Metode Pembayaran</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ optional($booking->transaksi)->metode_pembayaran === 'transfer' ? 'Transfer' : 'Cash' }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Detail Peralatan</h2>
            <span class="text-xs text-gray-500">{{ $booking->bookingDetails->count() }} jenis</span>
        </div>

        @if ($booking->bookingDetails->count())
            <div class="mt-6 space-y-4">
                @foreach ($booking->bookingDetails as $detail)
                    <div class="flex flex-col gap-2 rounded-2xl border border-gray-100 bg-gray-50 px-4 py-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-base font-semibold text-gray-900">{{ $detail->alat->nama_alat ?? 'Peralatan' }}</p>
                            <p class="text-sm text-gray-500">Kategori: {{ $detail->alat->kategori ?? '-' }}</p>
                        </div>
                        <div class="flex items-center gap-6 text-sm text-gray-600">
                            <p>Jumlah: <span class="font-semibold text-gray-900">{{ $detail->jumlah ?? 0 }}</span></p>
                            <p>Subtotal: <span class="font-semibold text-gray-900">Rp {{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}</span></p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="mt-6 text-sm text-gray-500">Belum ada detail peralatan yang tercatat.</p>
        @endif
    </div>

    @if ($booking->transaksi)
        <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Transaksi</h2>
            <dl class="mt-6 grid gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-[0.3em] text-gray-400">Total Pembayaran</dt>
                    <dd class="mt-2 text-gray-900">Rp {{ number_format($booking->transaksi->total ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.3em] text-gray-400">Status Pembayaran</dt>
                    <dd class="mt-2 text-gray-900">{{ $booking->transaksi->status_pembayaran ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.3em] text-gray-400">Metode Pembayaran</dt>
                    <dd class="mt-2 text-gray-900">{{ optional($booking->transaksi)->metode_pembayaran === 'transfer' ? 'Transfer bank' : 'Cash' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.3em] text-gray-400">Tanggal Pembayaran</dt>
                    <dd class="mt-2 text-gray-900">{{ optional($booking->transaksi->tanggal_bayar)->format('d M Y H:i') ?? 'Belum dibayar' }}</dd>
                </div>
            </dl>
        </div>
    @endif
</div>
@endsection
