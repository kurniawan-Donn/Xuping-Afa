@extends('layouts.website')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-semibold text-gray-800 mb-2">Koleksi Perhiasan Kami</h1>
    <p class="text-gray-500 mb-8">Temukan perhiasan Xuping pilihan untuk gaya Anda</p>
    <div class="flex flex-col md:flex-row gap-8">
        <aside class="w-full md:w-56 shrink-0">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Kategori</h3>
            <ul class="space-y-2 text-sm">
                <li>
                    <a href="{{ route('website.products.index') }}" class="{{ !request('category') ? 'text-gray-900 font-medium' : 'text-gray-500' }}">
                        Semua Produk
                    </a>
                </li>
                @foreach ($categories as $category)
                <li>
                    <a href="{{ route('website.products.index', ['category' => $category->id]) }}" class="{{ request('category') == $category->id ? 'text-gray-900 font-medium' : 'text-gray-500' }}">
                        {{ $category->name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </aside>
        <div class="flex-1">
            <form method="GET" class="mb-6">
                @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perhiasan..." class="w-full max-w-sm px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-300">
            </form>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                @forelse ($products as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="group block bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="aspect-square bg-gray-50 overflow-hidden">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                    </div>
                    <div class="p-3">
                        <p class="text-xs text-gray-400">{{ $product->category->name }}</p>
                        <h3 class="text-sm font-medium text-gray-700 truncate">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-900 font-semibold mt-1">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>
                </a>
                @empty
                <p class="col-span-full text-center text-gray-400 py-12">
                    Belum ada produk tersedia.
                </p>
                @endforelse
            </div>
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
