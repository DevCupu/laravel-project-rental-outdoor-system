@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.alat.index') }}" class="hover:text-gray-700">Alat</a>
                    <span>/</span>
                    <span class="text-gray-900">Edit</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900">Edit Alat</h1>
                <p class="text-gray-600 mt-2">Perbarui informasi alat camping yang tersedia</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Alat</h2>
                </div>

                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Alat -->
                            <div class="md:col-span-2">
                                <label for="nama_alat" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Alat <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama_alat" name="nama_alat"
                                    value="{{ old('nama_alat', $alat->nama_alat) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                    placeholder="Masukkan nama alat">
                            </div>

                            <!-- Harga Sewa -->
                            <div>
                                <label for="harga_sewa_per_hari" class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga Sewa per Hari <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" id="harga_sewa_per_hari" name="harga_sewa_per_hari"
                                        value="{{ old('harga_sewa_per_hari', $alat->harga_sewa_per_hari) }}"
                                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                        placeholder="0">
                                </div>
                            </div>

                            <!-- Stok Total -->
                            <div>
                                <label for="stok_total" class="block text-sm font-medium text-gray-700 mb-2">
                                    Stok Total <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="stok_total" name="stok_total"
                                    value="{{ old('stok_total', $alat->stok_total) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                    placeholder="0">
                            </div>

                            <!-- Deskripsi -->
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi
                                </label>
                                <textarea id="deskripsi" name="deskripsi" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                    placeholder="Masukkan deskripsi alat">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
                            </div>

                            <!-- Kategori -->
                            @php
                                $kategoriValue = old('kategori', $alat->kategori);
                                $kategoriList = ['Tenda', 'Matras', 'Kompor', 'Sleeping Bag', 'Lampu'];
                            @endphp
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                        </svg>
                                        Kategori
                                    </span>
                                </label>
                                <select id="kategori_select" name="kategori_select"
                                    class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 mb-2">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoriList as $item)
                                        <option value="{{ $item }}" {{ $kategoriValue == $item ? 'selected' : '' }}>
                                            {{ $item }}</option>
                                    @endforeach
                                    <option value="Lainnya"
                                        {{ !in_array($kategoriValue, $kategoriList) && $kategoriValue ? 'selected' : '' }}>
                                        Lainnya / Input Manual</option>
                                </select>
                                <input type="text" id="kategori_input" name="kategori" value="{{ $kategoriValue }}"
                                    placeholder="Contoh: Tenda, Matras, Kompor"
                                    class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                    {{ in_array($kategoriValue, $kategoriList) && $kategoriValue != '' ? 'readonly' : '' }}>
                                <p class="text-xs text-gray-500 mt-1">Pilih kategori atau masukkan manual jika tidak ada di
                                    daftar</p>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const select = document.getElementById('kategori_select');
                                    const input = document.getElementById('kategori_input');
                                    select.addEventListener('change', function() {
                                        if (this.value && this.value !== 'Lainnya') {
                                            input.value = this.value;
                                            input.readOnly = true;
                                        } else {
                                            input.value = '';
                                            input.readOnly = false;
                                            input.focus();
                                        }
                                    });
                                });
                            </script>
                            <!-- Foto URL -->
                            <div class="group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-pink-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Foto Alat
                                    </span>
                                </label>
                                <input type="file" name="foto_path" accept="image/*"
                                    class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-500 mt-1">Upload gambar alat (format: JPG, PNG, atau JPEG, max
                                    2MB)</p>
                                @if ($alat->foto_path)
                                    <div class="mt-3">
                                        <p class="text-xs text-gray-600 mb-1">Foto saat ini:</p>
                                        <img src="{{ asset('storage/' . $alat->foto_path) }}" alt="Foto Alat"
                                            class="h-32 rounded shadow border border-gray-200 object-contain bg-gray-50">
                                    </div>
                                @endif
                            </div>

                            <!-- Tombol Submit & Batal -->
                            <div class="md:col-span-2 flex justify-end space-x-3">
                                <a href="{{ route('admin.alat.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-md shadow-sm transition-all duration-150 text-sm border border-gray-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Batal
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Perbarui Alat
                                </button>
                            </div>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
