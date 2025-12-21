@extends('layouts.admin')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Dashboard Admin</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-gray-500 text-sm">Total Alat</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalAlat }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-gray-500 text-sm">Alat Tersedia</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $alatTersedia ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-gray-500 text-sm">Sedang Disewa</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $alatDisewa ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-gray-500 text-sm">Total Penyewaan</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPenyewaan ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart 1: Alat Terpopuler -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Alat Terpopuler</h3>
            <div class="h-64">
                <canvas id="popularItemsChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Penyewaan Bulanan -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Penyewaan Bulanan</h3>
            <div class="h-64">
                <canvas id="monthlyRentalsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Aktivitas Terbaru</h3>
        <div class="space-y-4">
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                <div class="flex-1">
                    <p class="text-sm text-gray-600">Penyewaan baru: Tenda Dome</p>
                    <p class="text-xs text-gray-400">5 menit yang lalu</p>
                </div>
            </div>
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                <div class="flex-1">
                    <p class="text-sm text-gray-600">Alat dikembalikan: Sleeping Bag</p>
                    <p class="text-xs text-gray-400">15 menit yang lalu</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Popular Items Chart
        const popularCtx = document.getElementById('popularItemsChart').getContext('2d');
        new Chart(popularCtx, {
            type: 'doughnut',
            data: {
                labels: ['Tenda', 'Sleeping Bag', 'Backpack', 'Kompor', 'Lainnya'],
                datasets: [{
                    data: [30, 25, 20, 15, 10],
                    backgroundColor: [
                        '#3B82F6',
                        '#10B981',
                        '#F59E0B',
                        '#EF4444',
                        '#8B5CF6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Monthly Rentals Chart
        const monthlyCtx = document.getElementById('monthlyRentalsChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Penyewaan',
                    data: [12, 19, 15, 25, 22, 30],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
