@extends('layouts.website')
@section('title', 'Katalog Produk - Afa Xuping')

@section('content')
<!-- Header -->
<div class="bg-indigo-50/50 border-b border-gray-100 py-12 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-4">Koleksi Perhiasan Kami</h1>
        <p class="text-lg text-gray-500 max-w-2xl mx-auto">Temukan perhiasan Xuping pilihan yang dirancang untuk memancarkan pesona dan kecantikan Anda.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <div class="flex flex-col lg:flex-row gap-10">
        
        <!-- Sidebar / Filter -->
        <aside class="w-full lg:w-64 shrink-0 space-y-8">
            <!-- Search -->
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Cari Produk</h3>
                <form method="GET" action="{{ route('website.products.index') }}">
                    @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perhiasan..." class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
            </div>
            
            <!-- Category List -->
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Kategori</h3>
                <div class="space-y-1">
                    <a href="{{ route('website.products.index', ['search' => request('search')]) }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ !request('category') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        Semua Produk
                    </a>
                    @foreach ($categories as $category)
                    <a href="{{ route('website.products.index', ['category' => $category->id, 'search' => request('search')]) }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request('category') == $category->id ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        {{ $category->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>
        
        <!-- Product Grid -->
        <div class="flex-1">
            @if(request('search') || request('category'))
            <div class="mb-6 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Menampilkan hasil untuk: 
                    @if(request('search')) <span class="font-semibold text-gray-900">"{{ request('search') }}"</span> @endif
                    @if(request('search') && request('category')) dan @endif
                    @if(request('category')) kategori <span class="font-semibold text-gray-900">{{ $categories->firstWhere('id', request('category'))->name ?? '' }}</span> @endif
                </p>
                <a href="{{ route('website.products.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Hapus Filter</a>
            </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @forelse ($products as $product)
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
                <div class="col-span-full py-20 text-center bg-gray-50 rounded-2xl border border-gray-100 border-dashed">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500">Belum ada perhiasan yang sesuai dengan kriteria pencarian Anda.</p>
                </div>
                @endforelse
            </div>
            
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
