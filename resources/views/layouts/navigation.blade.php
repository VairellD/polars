<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="block h-7">
                    <img src="{{ asset('assets/terminallogo.png') }}" alt="PoLaRs." class="h-8 w-auto">
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex items-center sm:hidden">
                <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500"
                        x-data=""
                        @click="$dispatch('toggle-mobile-menu')">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-center space-x-8">
                <a href="/" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('/') ? 'border-purple-600 text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    HOME
                </a>
                <a href="{{ route('posts.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('posts*') ? 'border-purple-600 text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    GALLERY
                </a>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('profile*') ? 'border-purple-600 text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    PROFILE
                </a>
            </div>

            <!-- Search Form -->
            <div class="hidden sm:flex sm:items-center">
                <form action="{{ route('posts.index') }}" method="GET" class="relative flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search"
                           class="w-64 h-9 pl-3 pr-10 text-sm placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Right Logo with Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 relative group">
                <a href="#" class="flex-shrink-0">
                    <img src="{{ asset('assets/himedia logo 1.png') }}" alt="HiMedia" class="h-8 w-auto">
                </a>

                <!-- Dropdown Menu (appears on hover) -->
                <div class="hidden group-hover:block absolute right-0 w-48 py-1 mt-12 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    @auth
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            My Profile
                        </a>
                        <a href="{{ route('posts.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Upload
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div class="sm:hidden" x-data="{ mobileMenuOpen: false }" @toggle-mobile-menu.window="mobileMenuOpen = !mobileMenuOpen" x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="pt-2 pb-3 space-y-1">
            <a href="/" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('/') ? 'border-purple-600 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                HOME
            </a>
            <a href="{{ route('posts.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('posts*') ? 'border-purple-600 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                GALLERY
            </a>
            <a href="{{ route('profile.edit') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->is('profile*') ? 'border-purple-600 text-purple-700 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                PROFILE
            </a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            <!-- Mobile Search Form -->
            <form action="{{ route('posts.index') }}" method="GET" class="mt-3 px-4 space-y-1">
                <div class="relative rounded-md shadow-sm">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search"
                           class="block w-full h-10 pl-3 pr-10 text-sm placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Mobile Auth Links -->
            <div class="mt-3 px-2 space-y-1">
                @auth
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        My Profile
                    </a>
                    <a href="{{ route('posts.create') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Upload
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:text-red-800 hover:bg-gray-50">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
