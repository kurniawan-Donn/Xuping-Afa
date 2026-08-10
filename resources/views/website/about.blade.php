@extends('layouts.website')
@section('title', 'Tentang Kami - Afa Xuping')

@section('content')
<!-- Header -->
<section class="bg-gray-50 py-16 md:py-24 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-4">{{ $settings['about_title'] ?? 'Tentang Afa Xuping' }}</h1>
        <p class="text-lg text-gray-500 max-w-2xl mx-auto">Lebih dekat dengan kami dan cari tahu bagaimana kami memberikan yang terbaik untuk aksesoris perhiasan Anda.</p>
    </div>
</section>

<!-- Content -->
<section class="py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-16 items-center">
            <div class="w-full md:w-1/2">
                @if(isset($settings['about_image']) && $settings['about_image'])
                <div class="rounded-3xl overflow-hidden shadow-2xl shadow-indigo-100/50">
                    <img src="{{ Storage::url($settings['about_image']) }}" alt="Tentang Kami" class="w-full h-auto object-cover aspect-[4/3]">
                </div>
                @else
                <div class="rounded-3xl bg-indigo-50 aspect-[4/3] flex items-center justify-center border border-indigo-100">
                    <svg class="w-24 h-24 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                @endif
            </div>
            
            <div class="w-full md:w-1/2 prose prose-lg prose-indigo text-gray-600">
                <p class="leading-relaxed whitespace-pre-line">{{ $settings['about_content'] ?? 'Kami adalah toko aksesoris yang berdedikasi untuk menyediakan produk Xuping berkualitas tinggi.' }}</p>
                
                <h3 class="text-xl font-bold text-gray-900 mt-10 mb-4">Mengapa Memilih Kami?</h3>
                <ul class="space-y-3 list-none p-0">
                    <li class="flex gap-3">
                        <svg class="w-6 h-6 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Material aman dan ramah di kulit, tidak menyebabkan alergi.</span>
                    </li>
                    <li class="flex gap-3">
                        <svg class="w-6 h-6 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Harga bersaing dengan kualitas yang memuaskan.</span>
                    </li>
                    <li class="flex gap-3">
                        <svg class="w-6 h-6 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Berbagai pilihan desain elegan yang up-to-date.</span>
                    </li>
                </ul>
                
                <div class="mt-10 pt-10 border-t border-gray-100">
                    <a href="{{ route('website.products.index') }}" class="inline-flex items-center gap-2 text-indigo-600 font-bold hover:text-indigo-700 transition-colors">
                        Lihat Katalog Kami <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
