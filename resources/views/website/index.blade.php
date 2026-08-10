@extends('layouts.website')
@section('title', ($settings['hero_title'] ?? 'Afa Xuping - Aksesoris Premium') . ' - Beranda')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gray-50 pt-16 pb-24 lg:pt-32 lg:pb-40 overflow-hidden">
    @if(isset($settings['hero_image']) && $settings['hero_image'])
    <div class="absolute inset-0 z-0">
        <img src="{{ Storage::url($settings['hero_image']) }}" alt="Background" class="w-full h-full object-cover opacity-20">
    </div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-50 z-10"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight leading-tight mb-6">
                {{ $settings['hero_title'] ?? 'Keanggunan dalam Setiap Detail' }}
            </h1>
            <p class="text-lg md:text-xl text-gray-600 mb-10 leading-relaxed">
                {{ $settings['hero_subtitle'] ?? 'Temukan koleksi perhiasan Xuping eksklusif yang dirancang khusus untuk memancarkan pesona dan kecantikan alami Anda setiap saat.' }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('website.products.index') }}" class="inline-flex justify-center items-center px-8 py-3.5 border border-transparent text-base font-semibold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-1">
                    Jelajahi Koleksi
                </a>
                <a href="{{ route('website.about') }}" class="inline-flex justify-center items-center px-8 py-3.5 border border-gray-300 text-base font-semibold rounded-full text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-all hover:-translate-y-1">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories / Features (Static for modern feel) -->
<section class="py-16 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
            <div class="p-6">
                <div class="w-16 h-16 mx-auto bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Kualitas Premium</h3>
                <p class="text-gray-500 leading-relaxed">Perhiasan Xuping berkualitas yang tahan lama dan aman untuk semua jenis kulit.</p>
            </div>
            <div class="p-6">
                <div class="w-16 h-16 mx-auto bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Harga Terjangkau</h3>
                <p class="text-gray-500 leading-relaxed">Tampil mewah dan elegan tidak perlu mahal. Kami menawarkan harga terbaik.</p>
            </div>
            <div class="p-6">
                <div class="w-16 h-16 mx-auto bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Desain Kekinian</h3>
                <p class="text-gray-500 leading-relaxed">Pilihan model yang selalu update mengikuti tren aksesoris masa kini.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Koleksi Terbaru</h2>
                <p class="text-gray-600">Perhiasan pilihan yang baru saja kami rilis.</p>
            </div>
            <a href="{{ route('website.products.index') }}" class="hidden md:inline-flex items-center gap-2 text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">
                Lihat Semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($products as $product)
            <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="group block bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-300">
                <div class="aspect-[4/5] bg-gray-50 overflow-hidden relative">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                <div class="p-5">
                    <p class="text-xs font-medium text-indigo-600 mb-1 tracking-wide uppercase">{{ $product->category->name ?? 'Aksesoris' }}</p>
                    <h3 class="text-base font-bold text-gray-900 truncate mb-2">{{ $product->name }}</h3>
                    <p class="text-lg text-gray-900 font-extrabold">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>
            </a>
            @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-gray-500">Belum ada produk untuk ditampilkan.</p>
            </div>
            @endforelse
        </div>
        
        <div class="mt-10 text-center md:hidden">
            <a href="{{ route('website.products.index') }}" class="inline-flex items-center justify-center w-full px-6 py-3 border border-gray-200 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                Lihat Semua Koleksi
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-indigo-600 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0 40V0H40" fill="none" stroke="white" stroke-width="2"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-pattern)"/>
        </svg>
    </div>
    <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Siap Untuk Tampil Lebih Memukau?</h2>
        <p class="text-indigo-100 text-lg mb-10 max-w-2xl mx-auto">Tingkatkan rasa percaya diri Anda dengan sentuhan aksesoris yang tepat. Temukan gaya Anda hari ini.</p>
        <a href="{{ route('website.products.index') }}" class="inline-flex justify-center items-center px-8 py-4 border border-transparent text-lg font-bold rounded-full text-indigo-600 bg-white hover:bg-indigo-50 hover:scale-105 transition-all shadow-xl">
            Mulai Belanja Sekarang
        </a>
    </div>
</section>
@endsection
