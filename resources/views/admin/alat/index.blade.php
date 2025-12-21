@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Manajemen Alat Camping</h1>
                    <p class="text-gray-600 mt-1">Kelola inventori alat camping rental Anda</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Total Alat</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $alats->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Action Bar -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <a href="{{ route('admin.alat.create') }}"
                    class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-1.5 rounded-md shadow-sm transition duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Alat
                </a>
            </div>

            <!-- Quick Stats -->
            <div class="flex items-center space-x-4 text-sm">
                <div class="flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                    {{ $alats->where('stok_total', '>', 0)->count() }} Tersedia
                </div>
                <div class="flex items-center bg-red-100 text-red-800 px-3 py-1 rounded-full">
                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                    {{ $alats->where('stok_total', '<=', 0)->count() }} Habis
                </div>
            </div>
        </div>

        <!-- Content Area -->
        @if ($alats->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($alats as $alat)
                    <div
                        class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group">
                        <!-- Image Section -->
                        <div class="relative overflow-hidden">
                            <img src="{{ $alat->foto_path ? asset('storage/' . $alat->foto_path) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                                alt="{{ $alat->nama_alat }}"
                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">

                            <!-- Overlay gradient -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>

                            <!-- Stock Badge -->
                            <div class="absolute top-3 right-3">
                                @if ($alat->stok_total > 0)
                                    <span
                                        class="bg-green-500/90 backdrop-blur-sm text-white text-xs font-semibold px-2.5 py-1 rounded-full shadow-lg">
                                        {{ $alat->stok_total }} unit
                                    </span>
                                @else
                                    <span
                                        class="bg-red-500/90 backdrop-blur-sm text-white text-xs font-semibold px-2.5 py-1 rounded-full shadow-lg">
                                        Habis
                                    </span>
                                @endif
                            </div>

                            <!-- Category Badge -->
                            @if ($alat->kategori)
                                <div class="absolute top-3 left-3">
                                    <span
                                        class="bg-blue-500/90 backdrop-blur-sm text-white text-xs font-medium px-2.5 py-1 rounded-full shadow-lg">
                                        {{ $alat->kategori }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Content Section -->
                        <div class="p-5">
                            <!-- Title & Description -->
                            <div class="mb-4">
                                <h3
                                    class="text-lg font-bold text-gray-800 mb-2 line-clamp-1 group-hover:text-blue-600 transition-colors">
                                    {{ $alat->nama_alat }}
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-2 leading-relaxed">
                                    {{ $alat->deskripsi ?: 'Tidak ada deskripsi tersedia.' }}
                                </p>
                            </div>

                            <!-- Price Section -->
                            <div
                                class="mb-4 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-blue-600">
                                            Rp {{ number_format($alat->harga_sewa_per_hari, 0, ',', '.') }}
                                        </span>
                                        <span class="text-gray-500 text-sm font-medium">/ hari</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Per minggu</div>
                                        <div class="text-sm font-semibold text-gray-700">
                                            Rp {{ number_format($alat->harga_sewa_per_hari * 7 * 0.9, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status & Info Grid -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <!-- Status -->
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                    <span class="text-xs text-gray-600 font-medium">Status:</span>
                                    @if ($alat->stok_total > 0)
                                        <span class="flex items-center text-green-600">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></div>
                                            <span class="text-xs font-semibold">Tersedia</span>
                                        </span>
                                    @else
                                        <span class="flex items-center text-red-600">
                                            <div class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></div>
                                            <span class="text-xs font-semibold">Habis</span>
                                        </span>
                                    @endif
                                </div>

                                <!-- Quick Info -->
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                    <span class="text-xs text-gray-600 font-medium">Kondisi:</span>
                                    <span class="text-xs font-semibold text-blue-600">Baik</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 pt-3 border-t border-gray-100">
                                <a href="{{ route('admin.alat.edit', $alat->id) }}"
                                    class="flex-1 bg-gradient-to-r from-amber-400 to-orange-400 hover:from-amber-500 hover:to-orange-500 text-white text-sm font-medium px-3 py-2 rounded-lg text-center transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.alat.destroy', $alat->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus alat ini?')" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-medium px-3 py-2 rounded-lg transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>

                            <!-- Quick Actions (Additional) -->
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>Update: {{ $alat->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination (if needed) -->
            @if (method_exists($alats, 'links'))
                <div class="mt-8">
                    {{ $alats->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Alat Camping</h3>
                <p class="text-gray-500 mb-6">Mulai tambahkan alat camping untuk disewakan</p>
                <a href="{{ route('admin.alat.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Alat Pertama
                </a>
            </div>
        @endif
    </div>
@endsection
