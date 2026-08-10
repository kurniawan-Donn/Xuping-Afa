<nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'bg-white shadow-sm': scrolled, 'bg-white/90 backdrop-blur-md border-b border-gray-100': !scrolled }"
     class="fixed top-0 inset-x-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('website.index') }}" class="flex items-center gap-2">
                    <span class="text-2xl font-bold text-gray-900 tracking-tight">Afa<span class="text-indigo-600">Xuping</span></span>
                </a>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('website.index') }}" class="{{ request()->routeIs('website.index') ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600' }} transition-colors text-sm font-medium">Beranda</a>
                <a href="{{ route('website.products.index') }}" class="{{ request()->routeIs('website.products.*') ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600' }} transition-colors text-sm font-medium">Katalog</a>
                <a href="{{ route('website.about') }}" class="{{ request()->routeIs('website.about') ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-600' }} transition-colors text-sm font-medium">Tentang Kami</a>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-gray-900 focus:outline-none p-2">
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="h-6 w-6" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         style="display: none;"
         class="md:hidden absolute top-20 inset-x-0 bg-white border-b border-gray-100 shadow-lg">
        <div class="px-4 pt-2 pb-6 space-y-2">
            <a href="{{ route('website.index') }}" class="block px-3 py-3 rounded-lg text-base font-medium {{ request()->routeIs('website.index') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">Beranda</a>
            <a href="{{ route('website.products.index') }}" class="block px-3 py-3 rounded-lg text-base font-medium {{ request()->routeIs('website.products.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">Katalog</a>
            <a href="{{ route('website.about') }}" class="block px-3 py-3 rounded-lg text-base font-medium {{ request()->routeIs('website.about') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">Tentang Kami</a>
        </div>
    </div>
</nav>