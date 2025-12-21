@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header Section -->
            <div class="mb-8">
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.alat.index') }}" class="hover:text-gray-700">Alat</a>
                    <span>/</span>
                    <span class="text-gray-900">Add</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900">Add Alat</h1>
                <p class="text-gray-600 mt-2">Tambah informasi alat camping yang tersedia</p>
            </div>


            <!-- Error Alert -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg mb-6 shadow-sm">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <h3 class="text-red-800 font-medium">Terdapat kesalahan input:</h3>
                    </div>
                    <ul class="text-red-700 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-start">
                                <span class="text-red-400 mr-2">•</span>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Section -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <!-- Nama Alat -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Nama Alat
                            </span>
                        </label>
                        <input type="text" name="nama_alat" value="{{ old('nama_alat') }}"
                            placeholder="Contoh: Tenda Dome 4 Orang"
                            class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <p class="text-xs text-gray-500 mt-1">Masukkan nama alat yang jelas dan deskriptif</p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Deskripsi
                            </span>
                        </label>
                        <textarea name="deskripsi" rows="4" placeholder="Deskripsikan spesifikasi, kondisi, dan fitur utama alat..."
                            class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 resize-none">{{ old('deskripsi') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Jelaskan spesifikasi dan kondisi alat secara detail</p>
                    </div>

                    <!-- Kategori -->
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
                            <option value="Tenda" {{ old('kategori') == 'Tenda' ? 'selected' : '' }}>Tenda</option>
                            <option value="Matras" {{ old('kategori') == 'Matras' ? 'selected' : '' }}>Matras</option>
                            <option value="Kompor" {{ old('kategori') == 'Kompor' ? 'selected' : '' }}>Kompor</option>
                            <option value="Sleeping Bag" {{ old('kategori') == 'Sleeping Bag' ? 'selected' : '' }}>Sleeping
                                Bag</option>
                            <option value="Lampu" {{ old('kategori') == 'Lampu' ? 'selected' : '' }}>Lampu</option>
                            <option value="Lainnya">Lainnya / Input Manual</option>
                        </select>
                        <input type="text" id="kategori_input" name="kategori" value="{{ old('kategori') }}"
                            placeholder="Contoh: Tenda, Matras, Kompor"
                            class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                            {{ in_array(old('kategori'), ['Tenda', 'Matras', 'Kompor', 'Sleeping Bag', 'Lampu']) && old('kategori') != '' ? 'readonly' : '' }}>
                        <p class="text-xs text-gray-500 mt-1">Pilih kategori atau masukkan manual jika tidak ada di daftar
                        </p>
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


                    <!-- Harga dan Stok -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    Harga Sewa/Hari
                                </span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                                <input type="number" name="harga_sewa_per_hari" value="{{ old('harga_sewa_per_hari') }}"
                                    placeholder="50000"
                                    class="w-full border-2 border-gray-300 rounded-lg pl-12 pr-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Harga sewa per hari dalam rupiah</p>
                        </div>

                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Stok Total
                                </span>
                            </label>
                            <input type="number" name="stok_total" value="{{ old('stok_total') }}" placeholder="10"
                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <p class="text-xs text-gray-500 mt-1">Jumlah unit alat yang tersedia</p>
                        </div>
                    </div>

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
                        <p class="text-xs text-gray-500 mt-1">Upload gambar alat (format: JPG, PNG, atau JPEG, max 2MB)</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                        <button type="submit"
                            class="flex-1 max-w-xs bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold px-4 py-2 rounded-md shadow hover:shadow-lg transition-all duration-150 text-sm mx-auto">
                            <span class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Alat
                            </span>
                        </button>
                        <a href="{{ route('admin.alat.index') }}"
                            class="flex-1 max-w-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-md shadow hover:shadow-lg transition-all duration-150 text-sm text-center mx-auto">
                            <span class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batal
                            </span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Help Section -->
            <div class="bg-blue-50 rounded-lg p-4 mt-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="text-blue-800 font-medium mb-1">Tips Pengisian Form:</h4>
                        <ul class="text-blue-700 text-sm space-y-1">
                            <li>• Gunakan nama yang jelas dan mudah dicari</li>
                            <li>• Sertakan spesifikasi lengkap dalam deskripsi</li>
                            <li>• Pastikan harga kompetitif dengan pasar</li>
                            <li>• Gunakan foto berkualitas baik untuk menarik pelanggan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
