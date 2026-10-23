@extends('layouts.dashboard')
@section('title', $product->name . ' — Admin Afa Xuping')
@section('header_title', 'Detail Produk')

@section('content')
<x-dash.header :title="$product->name" :subtitle="$product->category?->name" :back="route('products.index')">
    @if ($product->is_active)
        <a href="{{ route('website.products.show', $product) }}" target="_blank" class="btn btn-outline"><x-icon name="external" class="h-4 w-4" /> Lihat di website</a>
    @endif
    <a href="{{ route('products.edit', $product) }}" class="btn btn-dark"><x-icon name="edit" class="h-4 w-4" /> Edit</a>
</x-dash.header>

<div class="grid gap-6 lg:grid-cols-5">
    <div class="card p-4 lg:col-span-2" x-data="{ active: @js($product->image_url) }">
        <div class="aspect-square overflow-hidden rounded-xl bg-stone-100">
            <img :src="active" alt="{{ $product->name }}" class="h-full w-full object-cover">
        </div>
        @if ($product->images->count() > 1)
            <div class="mt-3 grid grid-cols-5 gap-2">
                @foreach ($product->images as $image)
                    @php $url = asset('storage/' . $image->image_path); @endphp
                    <button type="button" @click="active = @js($url)" :class="active === @js($url) ? 'ring-2 ring-ink' : 'opacity-70 hover:opacity-100'" class="aspect-square overflow-hidden rounded-lg">
                        <img src="{{ $url }}" alt="" class="h-full w-full object-cover">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <div class="space-y-6 lg:col-span-3">
        <div class="card grid grid-cols-2 gap-px overflow-hidden bg-stone-100 sm:grid-cols-4">
            @foreach ([
                ['Harga', 'Rp ' . number_format($product->price, 0, ',', '.')],
                ['Stok', $product->stock],
                ['Klik WA', $product->wa_clicks],
                ['Status', $product->is_active ? 'Aktif' : 'Draf'],
            ] as [$label, $value])
                <div class="bg-white p-4">
                    <p class="text-xs text-stone-500">{{ $label }}</p>
                    <p class="mt-1 font-semibold">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="card divide-y divide-stone-100">
            @foreach ([
                ['Harga coret', $product->has_discount ? 'Rp ' . number_format($product->compare_price, 0, ',', '.') . ' (hemat ' . $product->discount_percent . '%)' : '—'],
                ['Warna', $product->colors ? implode(', ', $product->colors) : '—'],
                ['Ukuran', $product->sizes ? implode(', ', $product->sizes) : '—'],
                ['Label', $product->badge_label ?? '—'],
                ['Unggulan', $product->is_featured ? 'Ya' : 'Tidak'],
                ['Dibuat oleh', ($product->creator?->name ?? '—') . ' · ' . $product->created_at?->translatedFormat('d M Y H:i')],
                ['Terakhir diubah', $product->updated_at?->diffForHumans()],
            ] as [$label, $value])
                <div class="flex justify-between gap-4 px-5 py-3 text-sm">
                    <span class="text-stone-500">{{ $label }}</span>
                    <span class="text-right font-medium">{{ $value }}</span>
                </div>
            @endforeach
        </div>

        <div class="card p-5">
            <p class="font-semibold">Deskripsi</p>
            <div class="prose-afa mt-3 text-sm">{!! $product->description ? nl2br(e($product->description)) : '<span class="text-stone-400">Belum ada deskripsi.</span>' !!}</div>
        </div>
    </div>
</div>
@endsection
