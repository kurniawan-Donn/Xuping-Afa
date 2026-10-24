@extends('layouts.website')

@php
    $siteName = $settings['site_name'] ?? 'Afa Xuping Accessories';
    $images = $product->images;
    $imageUrls = $images->isNotEmpty() ? $images->map(fn ($i) => asset('storage/' . $i->image_path))->values() : collect([$product->image_url]);
    $sizes = $product->sizes ?? [];
    $colors = $product->colors ?? [];
    $price = 'Rp ' . number_format($product->price, 0, ',', '.');
@endphp

@section('title', $product->name . ' — ' . $siteName)
@section('meta_description', $product->short_description ?: "{$product->name} — {$price}. Pesan mudah via WhatsApp di {$siteName}.")
@section('meta_image', $imageUrls->first())
@section('og_type', 'product')
{{-- Tombol pesan menempel di bawah layar HP; tombol chatbot dinaikkan agar tidak bertumpuk --}}
@section('body_class', 'has-sticky-actions')

@push('meta')
<meta property="product:price:amount" content="{{ (int) $product->price }}">
<meta property="product:price:currency" content="IDR">
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product->name,
    'image' => $imageUrls->all(),
    'description' => $product->short_description ?: $product->name,
    'sku' => 'AFA-' . $product->id,
    'category' => $product->category?->name,
    'brand' => ['@type' => 'Brand', 'name' => 'Xuping'],
    'offers' => [
        '@type' => 'Offer',
        'url' => url()->current(),
        'priceCurrency' => 'IDR',
        'price' => (int) $product->price,
        'availability' => $product->in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        'seller' => ['@type' => 'Organization', 'name' => $siteName],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
<div class="container-page pb-20 pt-6 sm:pb-0 lg:pt-10">
    <nav class="flex flex-wrap items-center gap-1.5 text-xs text-stone-500" aria-label="Breadcrumb">
        <a href="{{ route('website.index') }}" class="hover:text-ink">Beranda</a> <span>/</span>
        <a href="{{ route('website.products.index') }}" class="hover:text-ink">Katalog</a>
        @if ($product->category)
            <span>/</span> <a href="{{ route('website.products.index', ['category' => $product->category->slug]) }}" class="hover:text-ink">{{ $product->category->name }}</a>
        @endif
    </nav>

    <div class="mt-5 grid gap-8 lg:mt-8 lg:grid-cols-2 lg:gap-14">
        {{-- ============ GALERI ============ --}}
        <div x-data="gallery({{ $imageUrls->count() }})" class="lg:sticky lg:top-28 lg:self-start">
            <div class="relative -mx-4 overflow-hidden bg-cream sm:mx-0 sm:rounded-3xl">
                <div x-ref="track" @scroll.debounce.50ms="onScroll()" class="no-scrollbar flex aspect-square snap-x snap-mandatory overflow-x-auto sm:aspect-[4/5]">
                    @foreach ($imageUrls as $idx => $url)
                        <button type="button" @click="lightbox = true" class="h-full w-full shrink-0 snap-center cursor-zoom-in" aria-label="Perbesar foto {{ $idx + 1 }}">
                            <img src="{{ $url }}" alt="{{ $product->name }} — foto {{ $idx + 1 }}" class="h-full w-full object-cover" @if ($idx) loading="lazy" @else fetchpriority="high" @endif>
                        </button>
                    @endforeach
                </div>

                <div class="absolute left-4 top-4 flex flex-col items-start gap-1.5">
                    @if (! $product->in_stock)
                        <span class="badge bg-white text-stone-700 shadow-sm">Stok Habis</span>
                    @elseif ($product->has_discount)
                        <span class="badge bg-red-600 text-white">Hemat {{ $product->discount_percent }}%</span>
                    @endif
                    @if ($product->badge_label)
                        <span class="badge bg-ink text-gold-200">{{ $product->badge_label }}</span>
                    @endif
                </div>

                @if ($imageUrls->count() > 1)
                    <button type="button" @click="go(active - 1)" class="absolute left-3 top-1/2 hidden h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 shadow-soft hover:bg-white sm:grid" aria-label="Foto sebelumnya"><x-icon name="chevron-left" class="h-4 w-4" /></button>
                    <button type="button" @click="go(active + 1)" class="absolute right-3 top-1/2 hidden h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 shadow-soft hover:bg-white sm:grid" aria-label="Foto berikutnya"><x-icon name="chevron-right" class="h-4 w-4" /></button>
                    <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 gap-1.5 rounded-full bg-ink/30 px-2 py-1.5 backdrop-blur sm:hidden">
                        @foreach ($imageUrls as $idx => $url)
                            <span :class="active === {{ $idx }} ? 'w-5 bg-white' : 'w-1.5 bg-white/60'" class="h-1.5 rounded-full transition-all"></span>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($imageUrls->count() > 1)
                <div class="no-scrollbar mt-3 hidden gap-3 overflow-x-auto sm:flex">
                    @foreach ($imageUrls as $idx => $url)
                        <button type="button" @click="go({{ $idx }})" :class="active === {{ $idx }} ? 'ring-2 ring-ink ring-offset-2 ring-offset-ivory' : 'opacity-70 hover:opacity-100'"
                            class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-cream transition" aria-label="Lihat foto {{ $idx + 1 }}">
                            <img src="{{ $url }}" alt="" class="h-full w-full object-cover" loading="lazy">
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Lightbox --}}
            <template x-teleport="body">
                <div x-cloak x-show="lightbox" x-transition.opacity @keydown.escape.window="lightbox = false"
                    @keydown.arrow-right.window="lightbox && go(active + 1)" @keydown.arrow-left.window="lightbox && go(active - 1)"
                    class="fixed inset-0 z-[70] flex items-center justify-center bg-ink/95 p-4" role="dialog" aria-modal="true">
                    <button type="button" @click="lightbox = false" class="absolute right-4 top-4 grid h-11 w-11 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20" aria-label="Tutup"><x-icon name="x" /></button>
                    @foreach ($imageUrls as $idx => $url)
                        <img x-show="active === {{ $idx }}" src="{{ $url }}" alt="{{ $product->name }}" class="max-h-full max-w-full rounded-xl object-contain" loading="lazy">
                    @endforeach
                    @if ($imageUrls->count() > 1)
                        <button type="button" @click="go(active - 1)" class="absolute left-4 top-1/2 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20" aria-label="Sebelumnya"><x-icon name="chevron-left" /></button>
                        <button type="button" @click="go(active + 1)" class="absolute right-4 top-1/2 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20" aria-label="Berikutnya"><x-icon name="chevron-right" /></button>
                        <p class="absolute bottom-6 left-1/2 -translate-x-1/2 text-sm text-white/70"><span x-text="active + 1"></span> / {{ $imageUrls->count() }}</p>
                    @endif
                </div>
            </template>
        </div>

        {{-- ============ INFO & PESAN ============ --}}
        <div x-data="productOrder(@js($product->cartPayload()), @js($sizes), @js($colors))">
            @if ($product->category)
                <a href="{{ route('website.products.index', ['category' => $product->category->slug]) }}" class="eyebrow hover:text-gold-800">{{ $product->category->name }}</a>
            @endif
            <h1 class="heading-display mt-2 text-4xl leading-tight lg:text-5xl">{{ $product->name }}</h1>

            <div class="mt-4 flex flex-wrap items-baseline gap-3">
                <span class="text-3xl font-semibold tracking-tight">{{ $price }}</span>
                @if ($product->has_discount)
                    <span class="text-lg text-stone-400 line-through">Rp {{ number_format($product->compare_price, 0, ',', '.') }}</span>
                    <span class="badge badge-red">-{{ $product->discount_percent }}%</span>
                @endif
            </div>

            <p class="mt-3 flex items-center gap-2 text-sm font-medium">
                @if ($product->in_stock)
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span><span class="text-emerald-700">Tersedia</span>
                @else
                    <span class="h-2 w-2 rounded-full bg-stone-400"></span><span class="text-stone-500">Stok sedang habis</span>
                @endif
            </p>

            <div class="mt-8 space-y-6 border-t border-stone-200 pt-6">
                @if ($colors)
                    <fieldset>
                        <legend class="label flex items-center gap-2">Warna <span class="font-normal text-stone-500" x-text="color ? '— ' + color : ''"></span></legend>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($colors as $c)
                                <button type="button" @click="color = @js($c)" :class="color === @js($c) && 'option-active'" :aria-pressed="color === @js($c)" class="option">
                                    <span class="h-4 w-4 rounded-full ring-1 ring-black/10" style="background: {{ \App\Models\Product::COLORS[$c] ?? '#ddd' }}"></span>
                                    {{ $c }}
                                </button>
                            @endforeach
                        </div>
                        <p x-cloak x-show="attempted && !color" class="error">Silakan pilih warna.</p>
                    </fieldset>
                @endif

                @if ($sizes)
                    <fieldset>
                        <legend class="label">Ukuran</legend>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($sizes as $s)
                                <button type="button" @click="size = @js($s)" :class="size === @js($s) && 'option-active'" :aria-pressed="size === @js($s)" class="option">{{ $s }}</button>
                            @endforeach
                        </div>
                        <p x-cloak x-show="attempted && !size" class="error">Silakan pilih ukuran.</p>
                        <p class="hint">Bingung ukuran? Tanyakan ke admin, kami bantu pilihkan.</p>
                    </fieldset>
                @endif

                @if ($product->in_stock)
                    <div>
                        <p class="label">Jumlah</p>
                        <div class="inline-flex items-center rounded-full border border-stone-300 bg-white">
                            <button type="button" @click="qty = Math.max(1, qty - 1)" class="grid h-11 w-11 place-items-center text-stone-500 hover:text-ink" aria-label="Kurangi"><x-icon name="minus" class="h-4 w-4" /></button>
                            <input type="number" x-model.number="qty" min="1" max="99" class="w-12 border-0 bg-transparent p-0 text-center font-semibold focus:ring-0" aria-label="Jumlah">
                            <button type="button" @click="qty = Math.min(99, qty + 1)" class="grid h-11 w-11 place-items-center text-stone-500 hover:text-ink" aria-label="Tambah"><x-icon name="plus" class="h-4 w-4" /></button>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Tombol aksi --}}
            <div class="fixed inset-x-0 bottom-16 z-20 border-t border-stone-200 bg-white/95 p-3 backdrop-blur-lg sm:static sm:mt-8 sm:border-0 sm:bg-transparent sm:p-0 sm:backdrop-blur-none">
                @if ($product->in_stock)
                    <div class="grid grid-cols-2 gap-2 sm:gap-3">
                        <button type="button" @click="addToCart()" class="btn btn-outline btn-lg px-3">
                            <x-icon name="bag" class="h-5 w-5" /> <span>Tambah<span class="hidden sm:inline"> ke Pesanan</span></span>
                        </button>
                        <button type="button" @click="orderNow()" class="btn btn-wa btn-lg px-3">
                            <x-icon name="whatsapp" class="h-5 w-5" /> Pesan<span class="hidden sm:inline"> Sekarang</span>
                        </button>
                    </div>
                @else
                    <button type="button" @click="askStock()" class="btn btn-dark btn-lg w-full">
                        <x-icon name="whatsapp" class="h-5 w-5" /> Tanya Ketersediaan
                    </button>
                @endif
            </div>

            <ul class="mt-8 grid grid-cols-3 gap-2 text-center text-[11px] text-stone-600 sm:text-xs">
                <li class="rounded-2xl bg-white p-3"><x-icon name="shield" class="mx-auto h-5 w-5 text-gold-600" /><span class="mt-1.5 block">Dicek sebelum kirim</span></li>
                <li class="rounded-2xl bg-white p-3"><x-icon name="chat" class="mx-auto h-5 w-5 text-gold-600" /><span class="mt-1.5 block">Konsultasi gratis</span></li>
                <li class="rounded-2xl bg-white p-3"><x-icon name="bag" class="mx-auto h-5 w-5 text-gold-600" /><span class="mt-1.5 block">Tanpa daftar akun</span></li>
            </ul>

            @if ($product->description)
                <div x-data="{ open: true }" class="mt-8 border-t border-stone-200">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between py-5 text-left font-semibold">
                        Deskripsi Produk <x-icon name="chevron-down" class="h-4 w-4 transition" x-bind:class="open && 'rotate-180'" />
                    </button>
                    <div x-show="open" x-collapse class="prose-afa pb-6 text-sm">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif

            <div x-data="{ open: false }" class="border-y border-stone-200">
                <button type="button" @click="open = !open" class="flex w-full items-center justify-between py-5 text-left font-semibold">
                    Cara Pemesanan <x-icon name="chevron-down" class="h-4 w-4 transition" x-bind:class="open && 'rotate-180'" />
                </button>
                <ol x-show="open" x-collapse class="list-decimal space-y-2 pb-6 pl-5 text-sm text-stone-600">
                    <li>Pilih warna, ukuran, dan jumlah.</li>
                    <li>Tekan <b>Pesan Sekarang</b> untuk langsung chat, atau <b>Tambah ke Pesanan</b> untuk memesan beberapa produk sekaligus.</li>
                    <li>Admin akan mengonfirmasi stok, ongkos kirim, dan cara pembayaran di WhatsApp.</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- Produk terkait --}}
    @if ($related->isNotEmpty())
        <section class="mt-20">
            <div class="flex items-end justify-between">
                <h2 class="heading-display text-3xl lg:text-4xl">Mungkin Anda juga suka</h2>
                <a href="{{ route('website.products.index', ['category' => $product->category?->slug]) }}" class="hidden items-center gap-1.5 text-sm font-semibold hover:text-gold-700 sm:flex">Lihat semua <x-icon name="arrow-right" class="h-4 w-4" /></a>
            </div>
            <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-4 lg:gap-x-6">
                @foreach ($related as $item)
                    <x-website.product-card :product="$item" />
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
