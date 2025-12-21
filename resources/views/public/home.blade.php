@extends('layouts.public')

@section('title', 'Home')

@section('content')
    @php
        $highlights = [
            ['title' => 'Pilih Alat', 'desc' => 'Semua stok bersih dan siap digunakan.'],
            ['title' => 'Atur Jadwal', 'desc' => 'Kalender sewa transparan dan real-time.'],
            ['title' => 'Konfirmasi Cepat', 'desc' => 'Tanpa login, cukup nama & WhatsApp.'],
        ];
    @endphp

    <section class="pt-28 pb-16 bg-primary-50">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <p class="text-xs tracking-[0.3em] uppercase text-primary-600">Bontang Outdoor</p>
            <h1 class="mt-4 text-4xl md:text-5xl font-semibold text-gray-900">Semua alat camping favorit dalam satu klik.</h1>
            <p class="mt-4 text-gray-600 text-lg">
                Lihat stok real-time, pilih tanggal, isi data singkat, dan booking selesai kurang dari 2 menit.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#products" class="inline-flex items-center justify-center rounded-lg bg-primary-600 text-white px-6 py-3 font-medium hover:bg-primary-700">
                    Lihat daftar alat
                </a>
                <a href="{{ route('booking.form') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-6 py-3 font-medium text-gray-700 hover:border-gray-400">
                    Booking banyak alat
                </a>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-12 bg-white">
        <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-3 gap-6">
            @foreach ($highlights as $index => $highlight)
                <div class="p-6 border border-gray-200 rounded-2xl text-center bg-white shadow-sm">
                    <div class="w-12 h-12 mx-auto text-primary-600 text-xl font-semibold flex items-center justify-center rounded-full bg-primary-50 mb-4">
                        {{ $index + 1 }}
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ $highlight['title'] }}</h3>
                    <p class="mt-2 text-gray-600">{{ $highlight['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section id="products" class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-10">
                <p class="text-xs tracking-[0.3em] uppercase text-primary-600">Langkah 1</p>
                <h2 class="mt-3 text-3xl font-semibold text-gray-900">Pilih alat yang ingin disewa</h2>
                <p class="mt-2 text-gray-600">Setiap kartu menampilkan harga per hari dan status stok terkini.</p>
            </div>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($alats as $alat)
                    @php
                        $statusAvailable = ($alat->stok_total ?? 0) > 0;
                        $photo = $alat->foto_path
                            ? (\Illuminate\Support\Str::startsWith($alat->foto_path, ['http://', 'https://'])
                                ? $alat->foto_path
                                : asset('storage/' . ltrim($alat->foto_path, '/')))
                            : asset('images/default-alat.png');
                    @endphp
                    <article class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm flex flex-col">
                        <div class="relative">
                            <img src="{{ $photo }}" alt="{{ $alat->nama_alat }}" class="h-48 w-full object-cover">
                            <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-semibold {{ $statusAvailable ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $statusAvailable ? 'Available' : 'Habis' }}
                            </span>
                        </div>
                        <div class="flex-1 p-6 flex flex-col gap-4">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">{{ $alat->nama_alat }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $alat->kategori ?? 'Peralatan Outdoor' }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Harga / hari</p>
                                <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($alat->harga_sewa_per_hari ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($alat->deskripsi ?? 'Peralatan siap pakai untuk kebutuhan petualangan Anda.', 120) }}</p>
                            <a href="{{ route('alat.show', $alat) }}"
                                class="mt-auto inline-flex items-center justify-center rounded-xl {{ $statusAvailable ? 'bg-primary-600 text-white hover:bg-primary-700' : 'bg-gray-200 text-gray-500 cursor-not-allowed' }} px-4 py-3 font-semibold transition-colors">
                                Booking Sekarang
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 text-center text-gray-500 py-10">
                        Belum ada peralatan yang tersedia saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-semibold text-gray-900">Siap berangkat kapan pun.</h2>
            <p class="mt-3 text-gray-600">Jika bingung memilih alat, kirim daftar peserta dan lokasi tujuan. Tim kami bantu siapkan paket paling efisien.</p>
            <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('booking.form') }}" class="inline-flex items-center justify-center bg-primary-600 text-white px-6 py-3 rounded-lg font-medium">
                    Mulai booking banyak alat
                </a>
                <a href="tel:+6281234567890" class="inline-flex items-center justify-center border border-gray-300 px-6 py-3 rounded-lg font-medium text-gray-700">
                    Konsultasi via telepon
                </a>
            </div>
        </div>
    </section>
@endsection
