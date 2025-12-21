<nav x-data="{ open: false }" class="bg-white shadow-lg border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-5 lg:px-10 xl:px-1 m-2">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group hover:scale-105 transition-transform duration-200">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                    <span class="text-white font-bold text-lg">CO</span>
                </div>
                <div class="hidden sm:block">
                    <h1 class="text-xl font-bold text-gray-800">Camping Outdoor</h1>
                    <p class="text-xs text-gray-500">Admin Dashboard</p>
                </div>
            </a>

            <!-- User Profile Area -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-3 px-4 py-2 rounded-xl hover:bg-gray-50 transition-all duration-200 group border border-transparent hover:border-gray-200">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                                <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div class="text-left">
                                <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500 flex items-center">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                                    Administrator
                                </div>
                            </div>
                            <svg class="ml-2 h-4 w-4 text-gray-400 group-hover:text-gray-600 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.04.02L10 10.585l3.73-3.354a.75.75 0 111.02 1.095l-4.25 3.82a.75.75 0 01-1.02 0l-4.25-3.82a.75.75 0 01.02-1.095z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-600">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="py-2">
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-50 transition-colors duration-200">
                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700">{{ __('Profile') }}</span>
                            </x-dropdown-link>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center space-x-3 px-4 py-2 text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ __('Log Out') }}</span>
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-all duration-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white border-t border-gray-200 shadow-lg">
        <div class="px-4 py-4">
            <div class="flex items-center space-x-3 mb-4 p-3 bg-gray-50 rounded-xl">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                    <span class="text-white font-semibold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div>
                    <div class="font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
                    <div class="text-xs text-gray-500 flex items-center mt-1">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                        Administrator
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-3 py-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 border border-transparent hover:border-gray-200">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-gray-700 font-medium">Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-3 py-3 rounded-xl hover:bg-red-50 transition-colors duration-200 text-red-600 hover:text-red-700 border border-transparent hover:border-red-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium">Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
