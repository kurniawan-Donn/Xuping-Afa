@extends('layouts.website')

@php
    $siteName = $settings['site_name'] ?? 'Afa Xuping Accessories';
    $activeCategory = $categories->firstWhere('slug', request('category'));
    $sorts = [
        '' => 'Rekomendasi',
        'popular' => 'Paling diminati',
        'price_asc' => 'Harga terendah',
        'price_desc' => 'Harga tertinggi',
    ];
@endphp

@section('title', ($activeCategory ? $activeCategory->name . ' — ' : 'Katalog — ') . $siteName)
@section('meta_description', $activeCategory?->description ?: 'Lihat semua koleksi perhiasan Xuping: cincin, kalung, gelang, dan anting. Pesan mudah via WhatsApp.')

@section('content')
<div x-data="liveFilter({
        search: @js(request('search', '')),
        category: @js(request('category', '')),
        sort: @js(request('sort', '')),
        min_price: @js(request('min_price', '')),
        max_price: @js(request('max_price', '')),
        available: {{ request()->boolean('available') ? 'true' : 'false' }},
    }, { filtersOpen: false })"
    @keydown.escape.window="filtersOpen = false"
    class="container-page pt-8 lg:pt-12">

    {{-- Header --}}
    <nav class="text-xs text-stone-500" aria-label="Breadcrumb">
        <a href="{{ route('website.index') }}" class="hover:text-ink">Beranda</a> <span class="mx-1.5">/</span> <span class="text-ink">Katalog</span>
    </nav>
    <div class="mt-3 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="heading-display text-4xl lg:text-6xl">Katalog Produk</h1>
            <p class="mt-2 text-sm text-stone-500">Pilih model favorit, tambahkan ke Daftar Pesanan, lalu kirim lewat WhatsApp.</p>
        </div>
    </div>

    {{-- Pencarian + urutkan --}}
    <div class="sticky top-16 z-20 -mx-4 mt-6 border-b border-stone-200/70 bg-ivory/90 px-4 py-3 backdrop-blur-lg sm:mx-0 sm:rounded-2xl sm:border sm:bg-white sm:px-3 lg:top-20">
        <div class="flex items-center gap-2">
            <label class="relative flex-1">
                <span class="sr-only">Cari produk</span>
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-stone-400" />
                <input type="search" x-model.debounce.400ms="search" placeholder="Cari produk…" class="input rounded-full border-stone-200 pl-10">
            </label>
            <select x-model="sort" class="input hidden w-48 rounded-full sm:block" aria-label="Urutkan">
                @foreach ($sorts as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            <button type="button" @click="filtersOpen = true" class="btn btn-outline relative h-[42px] px-4 lg:hidden">
                <x-icon name="sliders" class="h-4.5 w-4.5" /> <span class="hidden sm:inline">Filter</span>
                <span x-show="category || min_price || max_price || available" class="absolute -right-0.5 -top-0.5 h-2.5 w-2.5 rounded-full bg-gold-500"></span>
            </button>
        </div>

        {{-- Kategori cepat --}}
        <div class="no-scrollbar -mx-4 mt-3 flex gap-2 overflow-x-auto px-4 sm:mx-0 sm:px-0">
            <button type="button" @click="category = ''" :class="!category && 'chip-active'" class="chip py-1.5">Semua</button>
            @foreach ($categories as $category)
                <button type="button" @click="category = '{{ $category->slug }}'" :class="category === '{{ $category->slug }}' && 'chip-active'" class="chip py-1.5">
                    {{ $category->name }} <span class="text-xs opacity-60">{{ $category->products_count }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <div class="mt-8 grid gap-10 lg:grid-cols-[240px_1fr]">
        {{-- Panel filter: sidebar di desktop, bottom sheet di HP --}}
        <div x-cloak x-show="filtersOpen" x-transition.opacity class="fixed inset-0 z-50 bg-ink/40 backdrop-blur-sm lg:hidden" @click="filtersOpen = false"></div>
        {{-- Pakai :style, bukan class translate-y-0: class itu kalah dari translate-y-full sehingga panel tidak pernah naik --}}
        <aside :style="filtersOpen && 'translate: none'"
            class="fixed inset-x-0 bottom-0 z-50 max-h-[85vh] translate-y-full overflow-y-auto rounded-t-3xl bg-ivory p-6 pb-[max(1.5rem,env(safe-area-inset-bottom))] shadow-lift transition duration-300 lg:static lg:z-auto lg:max-h-none lg:translate-y-0 lg:overflow-visible lg:rounded-none lg:bg-transparent lg:p-0 lg:shadow-none">
            <div class="mb-6 flex items-center justify-between lg:hidden">
                <p class="font-display text-2xl font-bold">Filter</p>
                <button type="button" @click="filtersOpen = false" class="btn-ghost grid h-10 w-10 place-items-center rounded-full" aria-label="Tutup"><x-icon name="x" /></button>
            </div>

            <div class="space-y-8 lg:sticky lg:top-56">
                <div class="sm:hidden">
                    <p class="label">Urutkan</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($sorts as $value => $label)
                            <button type="button" @click="sort = '{{ $value }}'" :class="sort === '{{ $value }}' && 'option-active'" class="option">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="label">Rentang harga</p>
                    <div class="flex items-center gap-2">
                        <input type="number" inputmode="numeric" min="0" step="1000" x-model.debounce.600ms="min_price" placeholder="Min" class="input" aria-label="Harga minimum">
                        <span class="text-stone-400">–</span>
                        <input type="number" inputmode="numeric" min="0" step="1000" x-model.debounce.600ms="max_price" placeholder="Maks" class="input" aria-label="Harga maksimum">
                    </div>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        @foreach ([[0, 50000, '< 50rb'], [50000, 100000, '50–100rb'], [100000, 250000, '100–250rb'], [250000, '', '> 250rb']] as [$min, $max, $label])
                            <button type="button" @click="min_price = '{{ $min ?: '' }}'; max_price = '{{ $max }}'" class="chip px-3 py-1 text-xs">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <label class="flex cursor-pointer items-center justify-between gap-3 rounded-2xl border border-stone-200 bg-white p-4">
                    <span class="text-sm font-medium">Hanya yang tersedia</span>
                    <input type="checkbox" x-model="available" class="h-5 w-5 rounded border-stone-300 text-ink focus:ring-gold-500">
                </label>

                <button type="button" @click="reset()" class="text-sm font-medium text-stone-500 underline-offset-4 hover:text-ink hover:underline">Hapus semua filter</button>

                <button type="button" @click="filtersOpen = false" class="btn btn-dark w-full lg:hidden">Tampilkan hasil</button>
            </div>
        </aside>

        {{-- Hasil --}}
        <div class="relative min-h-[50vh]">
            <div x-show="loading" x-cloak class="absolute inset-0 z-10 flex justify-center bg-ivory/60 pt-24 backdrop-blur-[1px]">
                <span class="h-8 w-8 animate-spin rounded-full border-2 border-gold-200 border-t-gold-600"></span>
            </div>
            <div x-ref="results">
                @include('website.products._grid')
            </div>
        </div>
    </div>
</div>
@endsection
