@extends('layouts.dashboard')
@section('title', 'Dashboard — Admin Afa Xuping')
@section('header_title', 'Dashboard')

@section('content')
<x-dash.header :title="'Halo, ' . explode(' ', auth()->user()->name)[0]" subtitle="Ringkasan katalog dan minat pengunjung toko Anda." />

{{-- Statistik --}}
<div class="grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-4">
    @foreach ([
        ['Produk aktif', $stats['active'], 'dari ' . $stats['products'] . ' produk', 'box', 'bg-gold-50 text-gold-600', route('products.index', ['status' => 1])],
        ['Stok habis', $stats['out_of_stock'], 'produk aktif', 'alert', $stats['out_of_stock'] ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600', route('products.index', ['stock' => 'out'])],
        ['Klik WhatsApp', number_format($stats['wa_clicks'], 0, ',', '.'), 'total pesanan dibuka', 'whatsapp', 'bg-wa/10 text-wa-dark', null],
        ['Kategori', $stats['categories'], $stats['testimonials'] . ' testimoni aktif', 'tag', 'bg-stone-100 text-stone-600', route('categories.index')],
    ] as [$label, $value, $hint, $icon, $tone, $link])
        <a @if ($link) href="{{ $link }}" @endif class="card group flex flex-col gap-4 p-4 transition {{ $link ? 'hover:-translate-y-0.5 hover:shadow-lift' : '' }} lg:p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-stone-500">{{ $label }}</span>
                <span class="grid h-9 w-9 place-items-center rounded-xl {{ $tone }}"><x-icon :name="$icon" class="h-4.5 w-4.5" /></span>
            </div>
            <div>
                <p class="font-display text-4xl font-bold leading-none">{{ $value }}</p>
                <p class="mt-1.5 text-xs text-stone-500">{{ $hint }}</p>
            </div>
        </a>
    @endforeach
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-5">
    {{-- Paling diminati --}}
    <div class="card lg:col-span-3">
        <div class="flex items-center justify-between border-b border-stone-100 px-5 py-4">
            <div>
                <p class="font-semibold">Paling diminati</p>
                <p class="text-xs text-stone-500">Berdasarkan jumlah klik pesan WhatsApp</p>
            </div>
            <x-icon name="trending" class="h-5 w-5 text-gold-500" />
        </div>
        @php $maxClicks = max(1, (int) $topProducts->max('wa_clicks')); @endphp
        <ul class="divide-y divide-stone-100">
            @forelse ($topProducts as $i => $product)
                <li class="flex items-center gap-4 px-5 py-3.5">
                    <span class="w-5 text-center font-display text-xl font-bold text-stone-300">{{ $i + 1 }}</span>
                    <img src="{{ $product->image_url }}" alt="" class="h-11 w-11 rounded-xl object-cover">
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('products.edit', $product) }}" class="block truncate text-sm font-medium hover:text-gold-700">{{ $product->name }}</a>
                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-stone-100">
                            <div class="h-full rounded-full bg-gold-400" style="width: {{ round($product->wa_clicks / $maxClicks * 100) }}%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-semibold tabular-nums">{{ $product->wa_clicks }}<span class="ml-1 text-xs font-normal text-stone-400">klik</span></span>
                </li>
            @empty
                <li class="px-5 py-12 text-center text-sm text-stone-500">
                    Belum ada data. Angka akan muncul saat pengunjung mengirim pesanan lewat WhatsApp.
                </li>
            @endforelse
        </ul>
    </div>

    {{-- Stok menipis --}}
    <div class="card lg:col-span-2">
        <div class="flex items-center justify-between border-b border-stone-100 px-5 py-4">
            <div>
                <p class="font-semibold">Perlu restok</p>
                <p class="text-xs text-stone-500">Produk aktif dengan stok &le; 5</p>
            </div>
            <a href="{{ route('products.index', ['stock' => 'low']) }}" class="text-xs font-semibold text-gold-700 hover:underline">Lihat semua</a>
        </div>
        <ul class="divide-y divide-stone-100">
            @forelse ($lowStock as $product)
                <li class="flex items-center gap-3 px-5 py-3">
                    <img src="{{ $product->image_url }}" alt="" class="h-10 w-10 rounded-xl object-cover">
                    <a href="{{ route('products.edit', $product) }}" class="min-w-0 flex-1 truncate text-sm font-medium hover:text-gold-700">{{ $product->name }}</a>
                    <span class="badge {{ $product->stock > 0 ? 'badge-amber' : 'badge-red' }}">{{ $product->stock > 0 ? 'Sisa ' . $product->stock : 'Habis' }}</span>
                </li>
            @empty
                <li class="flex flex-col items-center gap-2 px-5 py-12 text-center text-sm text-stone-500">
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-emerald-50 text-emerald-600"><x-icon name="check" /></span>
                    Semua stok aman.
                </li>
            @endforelse
        </ul>
    </div>
</div>

{{-- Produk terbaru --}}
<div class="card mt-6">
    <div class="flex items-center justify-between border-b border-stone-100 px-5 py-4">
        <p class="font-semibold">Produk terbaru</p>
        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-gold-700 hover:underline">Kelola produk</a>
    </div>
    <div class="overflow-x-auto">
        <table class="table-base">
            <tbody>
                @forelse ($latestProducts as $product)
                    <tr>
                        <td class="w-14"><img src="{{ $product->image_url }}" alt="" class="h-10 w-10 rounded-lg object-cover"></td>
                        <td><a href="{{ route('products.edit', $product) }}" class="font-medium hover:text-gold-700">{{ $product->name }}</a><p class="text-xs text-stone-500">{{ $product->category?->name }}</p></td>
                        <td class="whitespace-nowrap font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="whitespace-nowrap text-right"><span class="badge {{ $product->is_active ? 'badge-green' : 'badge-gray' }}">{{ $product->is_active ? 'Aktif' : 'Draf' }}</span></td>
                    </tr>
                @empty
                    <tr><td class="py-10 text-center text-stone-500">Belum ada produk. <a href="{{ route('products.create') }}" class="font-semibold text-gold-700">Tambah sekarang</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
