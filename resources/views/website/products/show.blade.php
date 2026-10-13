@extends('layouts.website')
@section('title', $product->name . ' - Afa Xuping')

{{-- LETAKKAN PUSH META DI SINI (Agar terbaca oleh layout/app.blade.php) --}}
@push('meta')
    <meta property="og:title" content="{{ $product->name }}">
    <meta property="og:description" content="Harga: Rp {{ number_format($product->price, 0, ',', '.') }}">
    @php
        $ogImage = $product->images->where('is_primary', 1)->first() ?? $product->images->first();
    @endphp
    @if($ogImage)
        <meta property="og:image" content="{{ asset('storage/' . $ogImage->image_path) }}">
    @endif
    <meta property="og:url" content="{{ url()->current() }}">
@endpush

@section('content')
<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Tombol Kembali -->
        <div class="mb-8">
            <a href="{{ route('website.products.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Katalog
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Bagian Gambar Produk -->
            <div class="space-y-4">
                <div class="aspect-square bg-gray-50 rounded-3xl overflow-hidden border border-gray-100 shadow-sm relative">
                    @php
                        $primaryImage = $product->images->where('is_primary', 1)->first() ?? $product->images->first();
                    @endphp
                    @if($primaryImage)
                        <img src="{{ Storage::url($primaryImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&background=e2e8f0&color=64748b" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @endif
                </div>

                <!-- Thumbnail Gambar Lainnya -->
                @if($product->images->count() > 1)
                <div class="grid grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                    <div class="aspect-square rounded-xl overflow-hidden border border-gray-200 bg-white cursor-pointer hover:border-indigo-600 transition-all">
                        <img src="{{ Storage::url($image->image_path) }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Bagian Detail Informasi Produk -->
            <div class="flex flex-col justify-between">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 mb-4 tracking-wide uppercase">
                        {{ $product->category->name ?? 'Aksesoris' }}
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
                    <p class="text-2xl font-bold text-indigo-600 mb-6">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    
                    <div class="prose prose-sm text-gray-600 mb-8 leading-relaxed">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Deskripsi</h3>
                        <p>{{ $product->description ?? 'Tidak ada deskripsi tersedia untuk produk ini.' }}</p>
                    </div>
                </div>

                @php
                    $whatsappNumber = '6282133237136'; 
                    
                    $productUrl = request()->url();

                    $whatsappMessage = "Halo Kak, saya tertarik untuk memesan produk ini:\n\n" .
                       "*{$product->name}*\n" .
                       "Harga: Rp " . number_format($product->price, 0, ',', '.') . "\n\n" .
                       "Link Produk: {$productUrl}\n\n" .
                       "Apakah stoknya masih tersedia?";
                       
                    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($whatsappMessage);
                @endphp

               <!-- Tombol Pesan via WhatsApp Dinamis -->
                <div class="border-t border-gray-100 pt-6">
                    <a href="{{ $whatsappUrl }}" target="_blank" class="w-full inline-flex justify-center items-center gap-2 px-8 py-4 border border-transparent text-base font-bold rounded-2xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-600/30 transition-all">
                      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                   <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                        Pesan via WhatsApp
                    </a>
                    <p class="text-xs text-center text-gray-400 mt-3">
                        💡 Detail produk dan foto akan otomatis terlampir saat Anda mengirim pesan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection