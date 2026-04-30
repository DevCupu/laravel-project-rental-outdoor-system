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

    <section class="pb-16 pt-28 bg-primary-50">
        <div class="max-w-5xl px-4 mx-auto text-center">
            <p class="text-xs tracking-[0.3em] uppercase text-primary-600">Barru Outdoor</p>
            <h1 class="mt-4 text-4xl font-semibold text-gray-900 md:text-5xl">Semua alat camping favorit dalam satu klik.
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                Lihat stok real-time, pilih tanggal, isi data singkat, dan booking selesai kurang dari 2 menit.
            </p>
            <div class="flex flex-col justify-center gap-4 mt-8 sm:flex-row">
                <a href="#products"
                    class="inline-flex items-center justify-center px-6 py-3 font-medium text-white rounded-lg bg-primary-600 hover:bg-primary-700">
                    Lihat daftar alat
                </a>
                <a href="{{ route('booking.form') }}"
                    class="inline-flex items-center justify-center px-6 py-3 font-medium text-gray-700 border border-gray-300 rounded-lg hover:border-gray-400">
                    Booking banyak alat
                </a>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-12 bg-white">
        <div class="grid max-w-6xl gap-6 px-4 mx-auto md:grid-cols-3">
            @foreach ($highlights as $index => $highlight)
                <div class="p-6 text-center bg-white border border-gray-200 shadow-sm rounded-2xl">
                    <div
                        class="flex items-center justify-center w-12 h-12 mx-auto mb-4 text-xl font-semibold rounded-full text-primary-600 bg-primary-50">
                        {{ $index + 1 }}
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ $highlight['title'] }}</h3>
                    <p class="mt-2 text-gray-600">{{ $highlight['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section id="products" class="py-16 bg-gray-50">
        <div class="max-w-6xl px-4 mx-auto">
            <div class="mb-10 text-center">
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
                    <article class="flex flex-col overflow-hidden bg-white border border-gray-100 shadow-sm rounded-3xl">
                        <div class="relative">
                            <img src="{{ $photo }}" alt="{{ $alat->nama_alat }}" class="object-cover w-full h-48">
                            <span
                                class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-semibold {{ $statusAvailable ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $statusAvailable ? 'Available' : 'Habis' }}
                            </span>
                        </div>
                        <div class="flex flex-col flex-1 gap-4 p-6">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">{{ $alat->nama_alat }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $alat->kategori ?? 'Peralatan Outdoor' }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Harga / hari</p>
                                <p class="text-2xl font-semibold text-gray-900">Rp
                                    {{ number_format($alat->harga_sewa_per_hari ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <p class="text-sm text-gray-600">
                                {{ \Illuminate\Support\Str::limit($alat->deskripsi ?? 'Peralatan siap pakai untuk kebutuhan petualangan Anda.', 120) }}
                            </p>
                            <a href="{{ route('alat.show', $alat) }}"
                                class="mt-auto inline-flex items-center justify-center rounded-xl {{ $statusAvailable ? 'bg-primary-600 text-white hover:bg-primary-700' : 'bg-gray-200 text-gray-500 cursor-not-allowed' }} px-4 py-3 font-semibold transition-colors">
                                Booking Sekarang
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="py-10 text-center text-gray-500 md:col-span-2 lg:col-span-3">
                        Belum ada peralatan yang tersedia saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-4xl px-4 mx-auto text-center">
            <h2 class="text-3xl font-semibold text-gray-900">Siap berangkat kapan pun.</h2>
            <p class="mt-3 text-gray-600">Jika bingung memilih alat, kirim daftar peserta dan lokasi tujuan. Tim kami bantu
                siapkan paket paling efisien.</p>
            <div class="flex flex-col justify-center gap-4 mt-6 sm:flex-row">
                <a href="{{ route('booking.form') }}"
                    class="inline-flex items-center justify-center px-6 py-3 font-medium text-white rounded-lg bg-primary-600">
                    Mulai booking banyak alat
                </a>
                <a href="tel:+6281234567890"
                    class="inline-flex items-center justify-center px-6 py-3 font-medium text-gray-700 border border-gray-300 rounded-lg">
                    Konsultasi via telepon
                </a>
            </div>
        </div>
    </section>
@endsection
