<!-- Header -->
<header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Mobile Menu Toggle -->
        <div class="flex items-center space-x-4">
            <button class="text-gray-600 hover:text-gray-900" onclick="toggleSidebar()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>

        </div>

        <!-- Right Side -  & User Menu -->
        <div class="flex items-center space-x-6">

            <!-- User Dropdown -->
            <div class="relative group">
                <button class="flex items-center space-x-3 text-gray-700 hover:text-gray-900 px-2 py-1">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=3B82F6&color=fff"
                        alt="User" class="w-8 h-8 rounded-full">
                    <div class="flex-1 text-left hidden md:block">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                    </div>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div
                    class="absolute right-0 w-48 mt-2 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                    <a href="{{ route('profile.show') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 first:rounded-t-lg">Lihat Profil</a>
                  
                  
                    <hr class="my-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 rounded-b-lg">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
