<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased">

    <div class="min-h-screen flex">
        {{-- Mobile Sidebar Overlay --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

        {{-- Sidebar --}}
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg border-r border-gray-200 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-200 ease-in-out lg:flex-shrink-0">
            <div class="px-4 sm:px-6 py-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl flex items-center justify-center mr-3 shadow-md">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Admin Panel</h1>
                            <p class="text-xs sm:text-sm text-gray-500">Camping Rental</p>
                        </div>
                    </div>
                    {{-- Close button for mobile --}}
                    <button id="close-sidebar"
                        class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Sidebar Links --}}
            <nav class="px-4 py-6 space-y-1 overflow-y-auto">
                @php
                    $navLinks = [
                        [
                            'label' => 'Dashboard',
                            'items' => [
                                [
                                    'route' => 'admin.dashboard',
                                    'label' => 'Dashboard',
                                    'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Manajemen Rental',
                            'items' => [
                                [
                                    'route' => 'admin.alat.index',
                                    'label' => 'Kelola Alat',
                                    'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                                ],
                                [
                                    'route' => 'admin.booking.index',
                                    'label' => 'Booking',
                                    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z',
                                ],
                                [
                                    'route' => 'admin.paket.index',
                                    'label' => 'Paket & Promo',
                                    'icon' => 'M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0-6C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Laporan & Review',
                            'items' => [
                                [
                                    'route' => 'admin.laporan.index',
                                    'label' => 'Laporan',
                                    'icon' => 'M3 3h18M9 3v18M15 3v18M4 21h16',
                                ],
                                [
                                    'route' => 'admin.testimoni.index',
                                    'label' => 'Testimoni',
                                    'icon' => 'M7 8h10M7 12h4m1 8a9 9 0 100-18 9 9 0 000 18z',
                                ],
                            ],
                        ],
                        [
                            'label' => 'Sistem',
                            'items' => [
                                [
                                    'route' => 'admin.user.index',
                                    'label' => 'User Management',
                                    'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z',
                                ],
                                [
                                    'route' => 'admin.notifikasi.index',
                                    'label' => 'Notifikasi',
                                    'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 15.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v4.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                                ],
                                [
                                    'route' => 'admin.pengaturan.index',
                                    'label' => 'Pengaturan',
                                    'icon' => 'M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0-6C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z',
                                ],
                            ],
                        ],
                        // [
                        //     'route' => 'admin.transaksi.index',
                        //     'label' => 'Transaksi',
                        //     'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        // ],
                    ];
                @endphp

                @foreach ($navLinks as $link)
                    <div x-data="{ open: false }" class="mb-2">
                        <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-100 transition-all">
                            <span class="font-semibold text-gray-700 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                {{ $link['label'] }}
                            </span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="pl-4 mt-1 space-y-1" x-cloak>
                            @foreach ($link['items'] as $item)
                                @php
                                    $routeName = $item['route'];
                                    $url = \Illuminate\Support\Facades\Route::has($routeName) ? route($routeName) : url('/' . str_replace('.', '/', $routeName));
                                @endphp
                                <a href="{{ $url }}" class="sidebar-link flex items-center p-2 rounded-lg hover:bg-blue-50 transition-all {{ request()->routeIs(Str::before($item['route'], '.') . ' *') ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                                    </svg>
                                    <span class="font-medium">{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100 lg:ml-0">
            {{-- Header --}}
            <header class="bg-white shadow-lg border-b border-gray-200 backdrop-blur-sm">
                <div class="px-4 sm:px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        {{-- Mobile menu button --}}
                        <button id="mobile-menu-button"
                            class="lg:hidden p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 mr-3 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center">
                                {{ $title ?? 'Dashboard' }}
                                <span
                                    class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full font-medium">Admin</span>
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1 hidden sm:block">Kelola sistem camping
                                rental Anda dengan mudah</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        {{-- User info --}}
                        <div class="hidden sm:flex items-center space-x-2 text-xs text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>{{ auth()->user()->name ?? 'Administrator' }}</span>
                        </div>
                        {{-- Date/Time --}}
                        <div
                            class="flex items-center text-xs sm:text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-xl border border-gray-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ now()->format('d M Y, H:i') }}
                        </div>
                        {{-- Logout button --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center px-3 py-2 rounded-lg text-xs text-red-600 hover:bg-red-50 border border-red-100 ml-2 transition-all">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <div class="p-4 sm:p-6 lg:p-8">
                {{-- Alerts --}}
                @if (session('success'))
                    <div class="mb-6 bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-400 p-4 rounded-xl shadow-sm animate-pulse"
                        role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-400 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-green-800">Berhasil!</p>
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-400 p-4 rounded-xl shadow-sm animate-pulse"
                        role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-400 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-red-800">Terjadi Kesalahan!</p>
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-6 bg-gradient-to-r from-yellow-50 to-yellow-100 border-l-4 border-yellow-400 p-4 rounded-xl shadow-sm animate-pulse"
                        role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-yellow-800">Peringatan!</p>
                                <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Main Content Container --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden backdrop-blur-sm">
                    <div class="relative">
                        {{-- Content decoration --}}
                        <div
                            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-blue-600">
                        </div>

                        {{-- Main content area --}}
                        <div class="p-6 sm:p-8">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Styles --}}
    <style>
        .sidebar-link {
            @apply flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200;
        }

        .sidebar-link.active {
            @apply bg-blue-100 text-blue-800 border-r-4 border-blue-600 font-semibold;
        }
    </style>

    {{-- JavaScript for mobile menu --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const closeSidebarButton = document.getElementById('close-sidebar');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            mobileMenuButton.addEventListener('click', openSidebar);
            closeSidebarButton.addEventListener('click', closeSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);

            // Close sidebar when clicking on a link (mobile)
            sidebar.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
