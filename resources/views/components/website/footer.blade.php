<footer class="bg-gray-900 text-white pt-16 pb-8 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <div>
                <span class="text-2xl font-bold tracking-tight mb-6 block">Afa<span class="text-indigo-400">Xuping</span></span>
                <p class="text-gray-400 text-sm leading-relaxed mb-6 max-w-xs">
                    {{ $settings['about_content'] ?? 'Kami menyediakan berbagai macam aksesoris perhiasan xuping berkualitas tinggi dengan harga yang terjangkau.' }}
                </p>
            </div>
            <div>
                <h4 class="text-sm font-semibold tracking-wider uppercase mb-6 text-gray-300">Tautan Pintas</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('website.index') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="{{ route('website.products.index') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Katalog Aksesoris</a></li>
                    <li><a href="{{ route('website.about') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Tentang Kami</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold tracking-wider uppercase mb-6 text-gray-300">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li class="flex gap-3 text-sm text-gray-400">
                        <svg class="w-5 h-5 shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $settings['contact_address'] ?? 'Pusat Aksesoris Jakarta' }}</span>
                    </li>
                    <li class="flex gap-3 text-sm text-gray-400">
                        <svg class="w-5 h-5 shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $settings['contact_email'] ?? 'hello@afaxuping.com' }}</span>
                    </li>
                    <li class="flex gap-3 text-sm text-gray-400">
                        <svg class="w-5 h-5 shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>{{ $settings['contact_phone'] ?? '+62 812 3456 7890' }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} {{ $settings['footer_text'] ?? 'Afa Xuping. Hak Cipta Dilindungi.' }}</p>
        </div>
    </div>
</footer>
