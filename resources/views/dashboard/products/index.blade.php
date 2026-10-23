@extends('layouts.dashboard')
@section('title', 'Produk — Admin Afa Xuping')
@section('header_title', 'Produk')

@section('content')
<x-dash.header title="Produk" subtitle="Kelola katalog, harga, varian, dan stok.">
    <a href="{{ route('products.create') }}" class="btn btn-dark"><x-icon name="plus" class="h-4 w-4" /> Tambah Produk</a>
</x-dash.header>

<div x-data="liveFilter({
        search: @js(request('search', '')),
        category_id: @js(request('category_id', '')),
        status: @js(request('status', '')),
        stock: @js(request('stock', '')),
        per_page: @js((string) $perPage),
    })">
    <div class="card mb-4 grid gap-3 p-3 sm:grid-cols-2 lg:grid-cols-[1fr_180px_150px_150px_110px]">
        <label class="relative">
            <span class="sr-only">Cari produk</span>
            <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" />
            <input type="search" x-model.debounce.400ms="search" class="input pl-9" placeholder="Cari nama produk…">
        </label>
        <select x-model="category_id" class="input" aria-label="Kategori">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <select x-model="status" class="input" aria-label="Status">
            <option value="">Semua status</option>
            <option value="1">Aktif</option>
            <option value="0">Draf</option>
        </select>
        <select x-model="stock" class="input" aria-label="Stok">
            <option value="">Semua stok</option>
            <option value="low">Stok menipis</option>
            <option value="out">Stok habis</option>
        </select>
        <select x-model="per_page" class="input" aria-label="Per halaman">
            @foreach ([10, 25, 50] as $n)
                <option value="{{ $n }}">{{ $n }} / hal</option>
            @endforeach
        </select>
    </div>

    <div class="card relative overflow-hidden">
        <div x-cloak x-show="loading" class="absolute inset-0 z-10 grid place-items-center bg-white/60">
            <span class="h-7 w-7 animate-spin rounded-full border-2 border-gold-200 border-t-gold-600"></span>
        </div>
        <div x-ref="results">
            @include('dashboard.products._table')
        </div>
    </div>
</div>
@endsection
