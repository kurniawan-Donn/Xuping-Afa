@extends('layouts.website')

@php
    $siteName = $settings['site_name'] ?? 'Afa Xuping Accessories';
    $heroTitle = $settings['hero_title'] ?? 'Perhiasan Premium, Harga Bersahabat.';
    $heroSubtitle = $settings['hero_subtitle'] ?? 'Koleksi perhiasan Xuping pilihan untuk menemani setiap momen. Pilih model favorit, pesan langsung lewat WhatsApp.';
    $heroImage = ! empty($settings['hero_image']) ? asset('storage/' . $settings['hero_image']) : null;
    $wa = \App\Models\Setting::whatsappNumber();
@endphp

@section('title', $siteName . ' — Perhiasan Xuping Pilihan')

@section('content')

{{-- ===================== HERO ===================== --}}
@if ($banners->isNotEmpty())
    <section x-data="{ i: 0, n: {{ $banners->count() }}, t: null, play() { clearInterval(this.t); if (this.n > 1) this.t = setInterval(() => this.i = (this.i + 1) % this.n, 6000) } }"
        x-init="play()" class="container-page pt-4 lg:pt-6">
        <div class="relative aspect-[4/5] overflow-hidden rounded-3xl bg-ink sm:aspect-[16/9] lg:aspect-[21/9]">
            @foreach ($banners as $idx => $banner)
                <div x-show="i === {{ $idx }}" @if ($idx) x-cloak @endif
                    x-transition:enter="transition duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    class="absolute inset-0">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 h-full w-full object-cover" @if ($idx) loading="lazy" @else fetchpriority="high" @endif>
                    <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/20 to-transparent sm:bg-gradient-to-r sm:from-ink/75 sm:via-ink/30"></div>
                    <div class="absolute inset-x-0 bottom-0 p-6 sm:inset-y-0 sm:flex sm:max-w-xl sm:flex-col sm:justify-center sm:p-12 lg:p-16">
                        <h2 class="font-display text-4xl font-semibold leading-[1.05] text-white sm:text-5xl lg:text-6xl">{{ $banner->title }}</h2>
                        @if ($banner->subtitle)
                            <p class="mt-3 max-w-md text-sm text-stone-200 sm:text-base">{{ $banner->subtitle }}</p>
                        @endif
                        <a href="{{ $banner->link_url ?: route('website.products.index') }}" class="btn btn-gold mt-6 w-fit">
                            Lihat Koleksi <x-icon name="arrow-right" class="h-4 w-4" />
                        </a>
                    </div>
                </div>
            @endforeach

            @if ($banners->count() > 1)
                <div class="absolute bottom-4 right-6 flex gap-2">
                    @foreach ($banners as $idx => $banner)
                        <button type="button" @click="i = {{ $idx }}; play()" :class="i === {{ $idx }} ? 'w-8 bg-white' : 'w-2 bg-white/50'"
                            class="h-2 rounded-full transition-all" aria-label="Banner {{ $idx + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@else
    <section class="relative overflow-hidden">
        <div class="container-page grid items-center gap-10 py-10 lg:min-h-[640px] lg:grid-cols-2 lg:py-16">
            <div class="animate-fade-up">
                <p class="eyebrow">{{ $siteName }}</p>
                <h1 class="heading-display mt-4 text-5xl leading-[1.02] sm:text-6xl lg:text-7xl">{{ $heroTitle }}</h1>
                <p class="mt-6 max-w-lg text-base leading-relaxed text-stone-600 lg:text-lg">{{ $heroSubtitle }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('website.products.index') }}" class="btn btn-dark btn-lg">
                        Jelajahi Katalog <x-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                    @if ($wa)
                        <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="btn btn-outline btn-lg">
                            <x-icon name="whatsapp" class="h-5 w-5 text-wa-dark" /> Tanya Admin
                        </a>
                    @endif
                </div>
                <dl class="mt-10 grid max-w-md grid-cols-3 divide-x divide-stone-200 border-y border-stone-200 py-4 text-center">
                    <div><dt class="text-xs text-stone-500">Koleksi</dt><dd class="font-display text-2xl font-bold">{{ $categories->sum('products_count') }}+</dd></div>
                    <div><dt class="text-xs text-stone-500">Kategori</dt><dd class="font-display text-2xl font-bold">{{ $categories->count() }}</dd></div>
                    <div><dt class="text-xs text-stone-500">Pesan</dt><dd class="font-display text-2xl font-bold">via WA</dd></div>
                </dl>
            </div>
            <div class="relative animate-fade-up [animation-delay:150ms]">
                <div class="absolute -right-10 -top-10 h-72 w-72 rounded-full bg-gold-200/50 blur-3xl"></div>
                <div class="relative aspect-[4/5] overflow-hidden rounded-[2rem] bg-cream shadow-lift lg:rounded-t-full">
                    @if ($heroImage)
                        <img src="{{ $heroImage }}" alt="{{ $heroTitle }}" class="h-full w-full object-cover" fetchpriority="high">
                    @elseif ($featured->first())
                        <img src="{{ $featured->first()->image_url }}" alt="{{ $featured->first()->name }}" class="h-full w-full object-cover" fetchpriority="high">
                    @endif
                </div>
                @if ($featured->first())
                    <a href="{{ route('website.products.show', $featured->first()) }}" class="absolute -bottom-5 left-4 flex items-center gap-3 rounded-2xl bg-white/95 p-3 pr-5 shadow-lift backdrop-blur sm:left-[-1.5rem]">
                        <img src="{{ $featured->first()->image_url }}" alt="" class="h-12 w-12 rounded-xl object-cover">
                        <span>
                            <span class="block text-[11px] font-medium uppercase tracking-wider text-gold-600">Pilihan Kami</span>
                            <span class="block max-w-44 truncate text-sm font-semibold">{{ $featured->first()->name }}</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>
    </section>
@endif

{{-- ===================== KEUNGGULAN ===================== --}}
<section class="container-page mt-12 lg:mt-16">
    <div class="grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-stone-200 bg-stone-200 lg:grid-cols-4">
        @foreach ([
            ['gem', 'Model Pilihan', 'Dikurasi agar cocok dipakai harian maupun acara.'],
            ['shield', 'Kualitas Terjaga', 'Dicek satu per satu sebelum dikirim.'],
            ['whatsapp', 'Pesan Mudah', 'Cukup pilih produk, kirim lewat WhatsApp.'],
            ['chat', 'Admin Responsif', 'Siap membantu memilih ukuran & model.'],
        ] as [$icon, $title, $text])
            <div class="flex flex-col gap-3 bg-white p-5 sm:flex-row sm:items-start lg:p-6">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-gold-50 text-gold-600"><x-icon :name="$icon" /></span>
                <div>
                    <p class="text-sm font-semibold">{{ $title }}</p>
                    <p class="mt-1 text-xs leading-relaxed text-stone-500">{{ $text }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ===================== KATEGORI ===================== --}}
@if ($categories->isNotEmpty())
    <section class="container-page mt-20">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="eyebrow">Kategori</p>
                <h2 class="heading-display mt-2 text-4xl lg:text-5xl">Temukan gaya Anda</h2>
            </div>
        </div>
        <div class="no-scrollbar -mx-4 mt-8 flex snap-x gap-3 overflow-x-auto px-4 pb-2 sm:mx-0 sm:grid sm:grid-cols-3 sm:px-0 {{ [3 => 'lg:grid-cols-3', 4 => 'lg:grid-cols-4', 5 => 'lg:grid-cols-5'][min(max($categories->count(), 3), 5)] }}">
            @foreach ($categories as $category)
                <a href="{{ route('website.products.index', ['category' => $category->slug]) }}"
                    class="group relative flex min-w-40 snap-start flex-col justify-between overflow-hidden rounded-2xl border border-stone-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-gold-300 hover:shadow-soft sm:min-w-0">
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-cream text-gold-600 transition group-hover:bg-ink group-hover:text-gold-300"><x-icon name="gem" class="h-4.5 w-4.5" /></span>
                    <span class="mt-8">
                        <span class="block font-display text-2xl font-semibold leading-tight">{{ $category->name }}</span>
                        <span class="mt-1 flex items-center gap-1 text-xs text-stone-500">{{ $category->products_count }} produk <x-icon name="arrow-right" class="h-3 w-3 transition group-hover:translate-x-1" /></span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>
@endif

{{-- ===================== PRODUK UNGGULAN ===================== --}}
@if ($featured->isNotEmpty())
    <section class="container-page mt-20">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="eyebrow">Koleksi Unggulan</p>
                <h2 class="heading-display mt-2 text-4xl lg:text-5xl">Paling dicari minggu ini</h2>
            </div>
            <a href="{{ route('website.products.index') }}" class="hidden items-center gap-1.5 text-sm font-semibold text-ink hover:text-gold-700 sm:flex">
                Lihat semua <x-icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>
        <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 lg:grid-cols-4 lg:gap-x-6">
            @foreach ($featured as $product)
                <x-website.product-card :product="$product" />
            @endforeach
        </div>
        <div class="mt-10 text-center sm:hidden">
            <a href="{{ route('website.products.index') }}" class="btn btn-outline">Lihat semua produk</a>
        </div>
    </section>
@endif

{{-- ===================== CARA PESAN ===================== --}}
<section class="mt-24 bg-cream/60 py-20">
    <div class="container-page">
        <div class="mx-auto max-w-2xl text-center">
            <p class="eyebrow">Cara Pesan</p>
            <h2 class="heading-display mt-2 text-4xl lg:text-5xl">Tiga langkah, tanpa ribet</h2>
            <p class="mt-4 text-stone-600">Tidak perlu daftar akun. Semua pesanan dikonfirmasi langsung oleh admin kami di WhatsApp.</p>
        </div>
        <ol class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach ([
                ['Pilih produk', 'Tentukan ukuran dan warna, lalu tekan "Tambah ke Pesanan".'],
                ['Kirim lewat WhatsApp', 'Buka Daftar Pesanan dan kirim semuanya dalam satu pesan.'],
                ['Konfirmasi & bayar', 'Admin mengonfirmasi stok, ongkir, dan cara pembayaran.'],
            ] as $i => [$title, $text])
                <li class="relative rounded-2xl bg-white p-7 shadow-soft">
                    <span class="font-display text-6xl font-bold leading-none text-gold-200">0{{ $i + 1 }}</span>
                    <p class="mt-4 text-lg font-semibold">{{ $title }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-stone-500">{{ $text }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ===================== PRODUK TERBARU ===================== --}}
@if ($newArrivals->count() >= 2)
    <section class="container-page mt-20">
        <div class="grid items-center gap-10 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <p class="eyebrow">Baru Datang</p>
                <h2 class="heading-display mt-2 text-4xl lg:text-5xl">Koleksi terbaru kami</h2>
                <p class="mt-4 max-w-sm text-stone-600">Model-model terbaru yang baru saja masuk. Stok terbatas, jangan sampai kehabisan.</p>
                <a href="{{ route('website.products.index') }}" class="btn btn-dark mt-6">Lihat yang terbaru <x-icon name="arrow-right" class="h-4 w-4" /></a>
            </div>
            <div class="grid grid-cols-2 gap-4 lg:col-span-3 lg:gap-6">
                @foreach ($newArrivals->take(2) as $product)
                    <x-website.product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===================== TESTIMONI ===================== --}}
@if ($testimonials->isNotEmpty())
    <section class="container-page mt-24">
        <div class="text-center">
            <p class="eyebrow">Testimoni</p>
            <h2 class="heading-display mt-2 text-4xl lg:text-5xl">Kata mereka</h2>
        </div>
        <div class="no-scrollbar -mx-4 mt-10 flex snap-x gap-4 overflow-x-auto px-4 pb-4 md:mx-0 md:grid md:grid-cols-3 md:px-0">
            @foreach ($testimonials as $t)
                @include('website.partials.testimonial', ['t' => $t])
            @endforeach
        </div>
    </section>
@endif

{{-- ===================== CTA ===================== --}}
<section class="container-page mt-24">
    <div class="relative overflow-hidden rounded-3xl bg-ink px-6 py-14 text-center sm:px-12 lg:py-20">
        <div class="absolute -left-20 -top-20 h-72 w-72 rounded-full bg-gold-500/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-10 h-72 w-72 rounded-full bg-gold-300/10 blur-3xl"></div>
        <div class="relative">
            <p class="eyebrow text-gold-300">Butuh bantuan memilih?</p>
            <h2 class="mx-auto mt-3 max-w-2xl font-display text-4xl font-semibold leading-tight text-white lg:text-5xl">Konsultasikan ukuran & model favorit Anda dengan admin kami</h2>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                @if ($wa)
                    <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="btn btn-wa btn-lg"><x-icon name="whatsapp" class="h-5 w-5" /> Chat WhatsApp</a>
                @endif
                <a href="{{ route('website.products.index') }}" class="btn btn-lg border border-white/20 text-white hover:bg-white/10">Lihat Katalog</a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('meta')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Store',
    'name' => $siteName,
    'url' => url('/'),
    'image' => $heroImage ?? asset('android-chrome-512x512.png'),
    'telephone' => $settings['whatsapp_number'] ?? $settings['contact_phone'] ?? null,
    'address' => $settings['contact_address'] ?? null,
    'sameAs' => array_values(array_filter([$settings['instagram_url'] ?? null, $settings['tiktok_url'] ?? null, $settings['facebook_url'] ?? null, $settings['shopee_url'] ?? null])),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
