@extends('layouts.admin')

@section('content')
@php
	$tones = [
		'amber' => [
			'gradient' => 'from-amber-50 to-amber-100',
			'border' => 'border-amber-200',
			'text' => 'text-amber-700',
			'chip' => 'bg-amber-100 text-amber-700',
		],
		'emerald' => [
			'gradient' => 'from-emerald-50 to-emerald-100',
			'border' => 'border-emerald-200',
			'text' => 'text-emerald-700',
			'chip' => 'bg-emerald-100 text-emerald-700',
		],
		'blue' => [
			'gradient' => 'from-sky-50 to-sky-100',
			'border' => 'border-sky-200',
			'text' => 'text-sky-700',
			'chip' => 'bg-sky-100 text-sky-700',
		],
		'rose' => [
			'gradient' => 'from-rose-50 to-rose-100',
			'border' => 'border-rose-200',
			'text' => 'text-rose-700',
			'chip' => 'bg-rose-100 text-rose-700',
		],
	];

	$statCards = [
		[
			'label' => 'Menunggu Konfirmasi',
			'value' => $pendingCount ?? $pendingBookings->total(),
			'hint' => 'Perlu validasi pembayaran',
			'chip' => 'bg-amber-50 text-amber-700',
		],
		[
			'label' => 'Siap Diambil',
			'value' => $confirmedCount ?? $confirmedBookings->total(),
			'hint' => 'Menunggu pickup',
			'chip' => 'bg-emerald-50 text-emerald-700',
		],
		[
			'label' => 'Sedang Dipinjam',
			'value' => $pickedUpCount ?? $pickedUpBookings->total(),
			'hint' => 'Aktif di lapangan',
			'chip' => 'bg-sky-50 text-sky-700',
		],
		[
			'label' => 'Selesai / Batal',
			'value' => ($returnedCount ?? $returnedBookings->total()) + ($cancelledCount ?? $cancelledBookings->total()),
			'hint' => 'Riwayat',
			'chip' => 'bg-rose-50 text-rose-700',
		],
	];

	$sections = [
		[
			'key' => 'pending',
			'title' => 'Menunggu Konfirmasi',
			'description' => 'Permintaan baru yang menunggu validasi pembayaran.',
			'empty' => 'Belum ada booking yang menunggu persetujuan.',
			'tone' => $tones['amber'],
			'collection' => $pendingBookings,
			'group' => 'active',
		],
		[
			'key' => 'confirmed',
			'title' => 'Siap Diambil',
			'description' => 'Pembayaran sudah dicek, menunggu penyewa mengambil alat.',
			'empty' => 'Belum ada booking yang siap diambil.',
			'tone' => $tones['emerald'],
			'collection' => $confirmedBookings,
			'group' => 'active',
		],
		[
			'key' => 'picked_up',
			'title' => 'Sedang Dipinjam',
			'description' => 'Alat sudah keluar dan menunggu pengembalian.',
			'empty' => 'Belum ada booking yang aktif di lapangan.',
			'tone' => $tones['blue'],
			'collection' => $pickedUpBookings,
			'group' => 'active',
		],
		[
			'key' => 'returned',
			'title' => 'Sudah Dikembalikan',
			'description' => 'Booking selesai dan barang sudah diterima kembali.',
			'empty' => 'Belum ada booking yang selesai.',
			'tone' => $tones['blue'],
			'collection' => $returnedBookings,
			'group' => 'history',
		],
		[
			'key' => 'cancelled',
			'title' => 'Dibatalkan',
			'description' => 'Booking dibatalkan oleh admin atau penyewa.',
			'empty' => 'Belum ada booking yang dibatalkan.',
			'tone' => $tones['rose'],
			'collection' => $cancelledBookings,
			'group' => 'history',
		],
	];
@endphp

<div class="space-y-12">
	<section class="rounded-[32px] border border-gray-100 bg-white/80 p-8 shadow-sm backdrop-blur">
		<div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
			<div>
				<p class="text-xs uppercase tracking-[0.3em] text-gray-400">Manajemen Booking</p>
				<h1 class="mt-2 text-3xl font-semibold text-gray-900">Ringkasan Penyewaan</h1>
				<p class="mt-2 text-sm text-gray-500">Pantau status penyewaan peralatan dengan cepat dan lakukan tindakan bila diperlukan.</p>
			</div>
			<div class="flex gap-4 text-sm text-gray-500">
				<div class="flex items-center gap-2">
					<span class="h-2 w-2 rounded-full bg-emerald-400"></span>
					Aktif
				</div>
				<div class="flex items-center gap-2">
					<span class="h-2 w-2 rounded-full bg-gray-300"></span>
					Riwayat
				</div>
			</div>
		</div>

		<div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
			@foreach ($statCards as $card)
				<div class="rounded-3xl border border-gray-100 bg-white/70 p-6 shadow-sm">
					<div class="flex items-center justify-between">
						<p class="text-xs font-semibold uppercase tracking-[0.3em] text-gray-400">{{ $card['label'] }}</p>
						<span class="rounded-full px-3 py-1 text-xs font-medium {{ $card['chip'] }}">{{ $card['hint'] }}</span>
					</div>
					<div class="mt-6 flex items-end justify-between">
						<p class="text-4xl font-semibold text-gray-900">{{ $card['value'] }}</p>
						<div class="text-right text-xs text-gray-400">
							<p>Status terkini</p>
							<p class="text-sm font-semibold text-gray-900">{{ now()->format('d M Y') }}</p>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</section>

	<section class="rounded-[24px] border border-dashed border-gray-200 bg-white/70 p-6 shadow-sm">
		<div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
			<div class="inline-flex w-full max-w-md items-center gap-3 rounded-2xl bg-gray-100 p-2">
				<button type="button" data-booking-filter="active"
					class="booking-filter-btn flex-1 rounded-2xl border border-transparent bg-white px-5 py-3 text-sm font-semibold text-gray-900">
					Booking Aktif
				</button>
				<button type="button" data-booking-filter="history"
					class="booking-filter-btn flex-1 rounded-2xl border border-transparent bg-transparent px-5 py-3 text-sm font-semibold text-gray-600">
					Riwayat & Dibatalkan
				</button>
			</div>
			<form method="GET" action="{{ route('admin.booking.index') }}" class="w-full max-w-md">
				<label class="block text-sm font-medium text-gray-700 mb-1">Cari booking</label>
				<div class="relative">
					<input
						type="text"
						name="search"
						value="{{ request('search') }}"
						placeholder="Cari berdasarkan nama penyewa, nomor WA, atau kode booking"
						class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-2 pr-10 text-sm focus:border-primary-500 focus:ring-primary-500" />
					<button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
						</svg>
					</button>
				</div>
			</form>
		</div>
	</section>

	@foreach ($sections as $section)
		@php
			$tone = $section['tone'];
			$isHistory = ($section['group'] ?? 'active') === 'history';
		@endphp
		<section data-booking-group="{{ $section['group'] ?? 'active' }}" class="booking-section rounded-[32px] border {{ $tone['border'] }} bg-white/90 p-6 shadow-sm {{ $isHistory ? 'hidden' : '' }}">
			<div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
				<div>
					<p class="text-xs uppercase tracking-[0.3em] text-gray-400">{{ $section['title'] }}</p>
					<h2 class="mt-1 text-2xl font-semibold text-gray-900">{{ $section['collection']->total() }} Booking</h2>
					<p class="mt-2 text-sm text-gray-500">{{ $section['description'] }}</p>
				</div>
				<span class="inline-flex items-center rounded-full px-4 py-2 text-xs font-semibold {{ $tone['chip'] }}">
					{{ $section['title'] }}
				</span>
			</div>

			<div class="mt-6 divide-y divide-gray-100">
				@forelse ($section['collection'] as $booking)
					@php
						$equipmentSummary = $booking->bookingDetails
							->map(function ($detail) {
								$name = $detail->alat->nama_alat ?? 'Peralatan';
								return $name . ' x' . ($detail->jumlah ?? 0);
							})
							->implode(', ');

						$bookingTotal = $booking->total_harga
							?? optional($booking->transaksi)->total
							?? $booking->bookingDetails->sum('subtotal');

						$expiresAt = $booking->expires_at;
					@endphp
					<article class="flex flex-col gap-4 px-6 py-5 hover:bg-gray-50 lg:flex-row lg:items-center lg:justify-between">
						<div class="flex-1">
							<div class="flex items-center gap-3">
								<h3 class="text-lg font-semibold text-gray-900">{{ $booking->nama_penyewa }}</h3>
								<span class="text-xs {{ $tone['chip'] }} rounded-full px-3 py-1">
									{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $booking->status ?? 'pending')) }}
								</span>
							</div>
							<div class="mt-3 grid gap-3 text-sm text-gray-600 md:grid-cols-4">
								<p><span class="font-medium text-gray-900">Kode Booking:</span> {{ $booking->booking_code ?? '' }}</p>
								<p><span class="font-medium text-gray-900">Peralatan:</span> {{ $equipmentSummary ?: 'Tidak ada data' }}</p>
								<p>
									<span class="font-medium text-gray-900">Tanggal:</span>
									{{ optional($booking->tanggal_mulai)->format('d M Y') ?? '' }}
									s/d
									{{ optional($booking->tanggal_selesai)->format('d M Y') ?? '' }}
								</p>
								<p><span class="font-medium text-gray-900">Total:</span> Rp {{ number_format($bookingTotal ?? 0, 0, ',', '.') }}</p>
						</div>
							<p class="mt-2 text-xs text-gray-500">Diperbarui: {{ optional($booking->updated_at)->format('d M Y, H:i') ?? '—' }} | Dibuat {{ optional($booking->created_at)->diffForHumans() ?? '-' }}</p>

							@if ($section['key'] === 'pending' && $expiresAt)
								<p class="mt-1 text-xs font-semibold {{ $expiresAt->isPast() ? 'text-rose-600' : 'text-amber-600' }}">
									{{ $expiresAt->isPast() ? 'Lewat jatuh tempo' : 'Auto batal' }} {{ $expiresAt->diffForHumans(now(), ['syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW]) }}
									<span class="font-medium text-gray-500">({{ $expiresAt->format('d M Y H:i') }})</span>
								</p>
							@endif
						</div>

						<div class="flex flex-wrap gap-2 lg:flex-none">
							<a href="{{ route('admin.booking.show', $booking->id) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
								Detail
							</a>

							@if ($section['key'] === 'pending')
								<a href="{{ route('admin.booking.approve', $booking->id) }}" onclick="return confirm('Konfirmasi pembayaran booking ini?')" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700">
									Konfirmasi Pembayaran
								</a>
								<a href="{{ route('admin.booking.cancel', $booking->id) }}" onclick="return confirm('Yakin ingin membatalkan booking ini?')" class="rounded-lg bg-rose-600 px-4 py-2 text-sm text-white hover:bg-rose-700">
									Batalkan
								</a>
							@elseif ($section['key'] === 'confirmed')
								<a href="{{ route('admin.booking.pickup', $booking->id) }}" onclick="return confirm('Tandai alat sudah diambil penyewa?')" class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">
									Tandai Diambil
								</a>
								<a href="{{ route('admin.booking.cancel', $booking->id) }}" onclick="return confirm('Yakin ingin membatalkan booking ini?')" class="rounded-lg bg-rose-600 px-4 py-2 text-sm text-white hover:bg-rose-700">
									Batalkan
								</a>
							@elseif ($section['key'] === 'picked_up')
								@if ($booking->tanggal_selesai && \Carbon\Carbon::parse($booking->tanggal_selesai)->isPast())
									<span class="rounded-lg bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-800">Lewat jatuh tempo</span>
								@endif
								<a href="{{ route('admin.booking.return', $booking->id) }}" onclick="return confirm('Yakin barang sudah dikembalikan?')" class="rounded-lg bg-sky-600 px-4 py-2 text-sm text-white hover:bg-sky-700">
									Tandai Kembali
								</a>
							@elseif ($section['key'] === 'returned')
								<span class="rounded-lg bg-sky-100 px-3 py-2 text-xs font-semibold text-sky-700">Dikembalikan {{ optional($booking->updated_at)->diffForHumans() }}</span>
							@elseif ($section['key'] === 'cancelled')
								<span class="rounded-lg bg-rose-100 px-3 py-2 text-xs font-semibold text-rose-700">Dibatalkan {{ optional($booking->updated_at)->diffForHumans() }}</span>
							@endif

							@if (($section['group'] ?? 'active') === 'history')
								<form action="{{ route('admin.booking.destroy', $booking->id) }}" method="POST" class="inline-flex" onsubmit="return confirm('Hapus booking ini dari riwayat? Tindakan tidak dapat dibatalkan.');">
									@csrf
									@method('DELETE')
									<button type="submit" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100">Hapus</button>
								</form>
							@endif
						</div>
					</article>
				@empty
					<div class="px-6 py-10 text-center text-gray-500">
						<svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
						</svg>
						<p class="mt-3 text-sm">{{ $section['empty'] }}</p>
					</div>
				@endforelse
			</div>

			@if ($section['collection']->hasPages())
				<div class="mt-4 border-t border-gray-100 pt-4">
					{{ $section['collection']->links() }}
				</div>
			@endif
		</section>
	@endforeach
</div>
@endsection

@push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const storageKey = 'booking.admin.filter';
			const buttons = document.querySelectorAll('.booking-filter-btn');
			const sections = document.querySelectorAll('.booking-section');
			const activeClasses = ['bg-white', 'text-gray-900', 'shadow-sm'];
			const inactiveClasses = ['bg-transparent', 'text-gray-600'];

			const applyFilter = (filter) => {
				sections.forEach((section) => {
					const group = section.dataset.bookingGroup || 'active';
					const shouldShow = filter === 'history' ? group === 'history' : group === 'active';
					section.classList.toggle('hidden', !shouldShow);
				});

				buttons.forEach((button) => {
					const isActive = button.dataset.bookingFilter === filter;
					activeClasses.forEach((cls) => button.classList.toggle(cls, isActive));
					inactiveClasses.forEach((cls) => button.classList.toggle(cls, !isActive));
				});

				localStorage.setItem(storageKey, filter);
			};

			const initialFilter = localStorage.getItem(storageKey) || 'active';
			applyFilter(initialFilter);

			buttons.forEach((button) => {
				button.addEventListener('click', () => applyFilter(button.dataset.bookingFilter));
			});
		});
	</script>
@endpush
