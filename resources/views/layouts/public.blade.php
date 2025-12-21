<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Bontang Outdoor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2d5a3d',
                        secondary: '#4a7c59',
                        accent: '#ff6b35',
                        'primary-50': '#f0f7f4',
                        'primary-100': '#dcebdf',
                        'primary-500': '#2d5a3d',
                        'primary-600': '#1f4028',
                        'primary-700': '#1a3622',
                        'primary-800': '#162c1d',
                        'primary-900': '#122317',
                    },
                    fontFamily: {
                        'sans': ['"Cormorant Garamond"', 'serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'bounce-subtle': 'bounceSubtle 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(20px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        },
                        bounceSubtle: {
                            '0%, 20%, 50%, 80%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '40%': {
                                transform: 'translateY(-5px)'
                            },
                            '60%': {
                                transform: 'translateY(-3px)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Cormorant Garamond', serif;
            letter-spacing: 0.01em;
        }

        .hero-bg {
            background: linear-gradient(135deg, rgba(45, 90, 61, 0.8), rgba(74, 124, 89, 0.6)), url('{{ asset('images/hero-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .nav-blur {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.95);
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans text-gray-900 antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 nav-blur border-b border-gray-200/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('public.home') }}"
                        class="flex items-center space-x-2 text-primary-600 hover:text-primary-700 transition-colors duration-200">
                        <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-mountain text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold">Bontang Outdoor</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('public.home') }}"
                        class="text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200 relative group">
                        Beranda
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#products"
                        class="text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200 relative group">
                        Produk
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#about"
                        class="text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200 relative group">
                        Tentang
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#contact"
                        class="text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200 relative group">
                        Kontak
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('booking.form') }}"
                        class="bg-accent hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        Booking Sekarang
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button"
                        class="text-gray-700 hover:text-primary-600 focus:outline-none focus:text-primary-600 transition-colors duration-200"
                        onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-sm border-t border-gray-200/20">
            <div class="px-4 pt-2 pb-3 space-y-1">
                <a href="#home"
                    class="block px-3 py-2 text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200">
                    Beranda
                </a>
                <a href="#products"
                    class="block px-3 py-2 text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200">
                    Produk
                </a>
                <a href="#about"
                    class="block px-3 py-2 text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200">
                    Tentang
                </a>
                <a href="#contact"
                    class="block px-3 py-2 text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200">
                    Kontak
                </a>
                <a href="{{ route('public.home') }}#products"
                    class="block mx-3 mt-3 bg-accent hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium text-center transition-all duration-200">
                    Booking Sekarang
                </a>
                <a href="{{ route('login') }}"
                    class="block mx-3 mt-2 border border-primary-500 text-primary-600 px-4 py-2 rounded-lg font-medium text-center transition-all duration-200">
                    Masuk
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-accent rounded-lg flex items-center justify-center">
                            <i class="fas fa-mountain text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold">Bontang Outdoor</span>
                    </div>
                    <p class="text-gray-300 mb-6 max-w-md">
                        Penyedia peralatan camping dan outdoor terpercaya di Bontang.
                        Wujudkan petualangan impian Anda bersama kami.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#"
                            class="w-10 h-10 bg-primary-700 hover:bg-accent rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-primary-700 hover:bg-accent rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-primary-700 hover:bg-accent rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Layanan</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Sewa Peralatan</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Paket Camping</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Konsultasi</a>
                        </li>
                        <li><a href="#" class="hover:text-white transition-colors duration-200">Panduan</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-map-marker-alt mt-1"></i>
                            <span>Jl. Outdoor No. 123, Bontang, Kalimantan Timur</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-phone"></i>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-envelope"></i>
                            <span>info@bontangoutdoor.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-primary-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Bontang Outdoor. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('nav');
            if (window.scrollY > 50) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
