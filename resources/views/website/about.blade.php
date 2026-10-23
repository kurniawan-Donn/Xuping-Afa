@extends('layouts.website')

@php
    $siteName = $settings['site_name'] ?? 'Afa Xuping Accessories';
    $aboutTitle = $settings['about_title'] ?? "Tentang {$siteName}";
    $aboutContent = $settings['about_content'] ?? '';
    $aboutImage = ! empty($settings['about_image']) ? asset('storage/' . $settings['about_image']) : null;
    $wa = \App\Models\Setting::whatsappNumber();
@endphp

@section('title', 'Tentang Kami — ' . $siteName)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($aboutContent), 155) ?: "Kenali {$siteName}, toko perhiasan Xuping pilihan dengan pemesanan mudah lewat WhatsApp.")

@section('content')
<section class="container-page pt-8 lg:pt-14">
    <nav class="text-xs text-stone-500" aria-label="Breadcrumb">
        <a href="{{ route('website.index') }}" class="hover:text-ink">Beranda</a> <span class="mx-1.5">/</span> <span class="text-ink">Tentang Kami</span>
    </nav>

    <div class="mt-6 grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
        <div class="animate-fade-up">
            <p class="eyebrow">Tentang Kami</p>
            <h1 class="heading-display mt-3 text-5xl leading-[1.05] lg:text-6xl">{{ $aboutTitle }}</h1>
            @if ($aboutContent)
                <div class="prose-afa mt-6 text-base lg:text-lg">{!! nl2br(e($aboutContent)) !!}</div>
            @endif
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('website.products.index') }}" class="btn btn-dark btn-lg">Lihat Katalog</a>
                @if ($wa)
                    <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="btn btn-outline btn-lg"><x-icon name="whatsapp" class="h-5 w-5 text-wa-dark" /> Hubungi Kami</a>
                @endif
            </div>
        </div>
        <div class="relative">
            <div class="absolute -bottom-6 -left-6 h-40 w-40 rounded-full bg-gold-200/60 blur-2xl"></div>
            <div class="relative aspect-[4/5] overflow-hidden rounded-[2rem] bg-cream shadow-lift lg:rounded-t-full">
                @if ($aboutImage)
                    <img src="{{ $aboutImage }}" alt="{{ $aboutTitle }}" class="h-full w-full object-cover">
                @else
                    <div class="grid h-full place-items-center text-gold-300"><x-icon name="gem" class="h-24 w-24" stroke="1" /></div>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="container-page mt-24">
    <div class="grid gap-4 md:grid-cols-3">
        @foreach ([
            ['gem', 'Kurasi Model', 'Setiap model kami pilih dengan teliti agar tetap cantik dipakai sehari-hari maupun acara spesial.'],
            ['shield', 'Dicek Sebelum Kirim', 'Produk diperiksa satu per satu dan dikemas rapi sebelum dikirim kepada Anda.'],
            ['chat', 'Pelayanan Personal', 'Admin kami siap membantu memilih ukuran, warna, sampai ide kado lewat WhatsApp.'],
        ] as [$icon, $title, $text])
            <div class="card p-7">
                <span class="grid h-12 w-12 place-items-center rounded-full bg-gold-50 text-gold-600"><x-icon :name="$icon" /></span>
                <p class="mt-5 font-display text-2xl font-semibold">{{ $title }}</p>
                <p class="mt-2 text-sm leading-relaxed text-stone-500">{{ $text }}</p>
            </div>
        @endforeach
    </div>
</section>

@if ($testimonials->isNotEmpty())
    <section class="container-page mt-24">
        <h2 class="heading-display text-center text-4xl lg:text-5xl">Kata pelanggan kami</h2>
        <div class="no-scrollbar -mx-4 mt-10 flex snap-x gap-4 overflow-x-auto px-4 pb-4 md:mx-0 md:grid md:grid-cols-3 md:px-0">
            @foreach ($testimonials as $t)
                @include('website.partials.testimonial', ['t' => $t])
            @endforeach
        </div>
    </section>
@endif

<section class="container-page mt-24">
    <div class="grid overflow-hidden rounded-3xl border border-stone-200 bg-white lg:grid-cols-2">
        <div class="p-8 lg:p-12">
            <p class="eyebrow">Kontak</p>
            <h2 class="heading-display mt-2 text-4xl">Mari terhubung</h2>
            <ul class="mt-8 space-y-5 text-sm">
                @if ($wa)
                    <li class="flex gap-4">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-wa/10 text-wa-dark"><x-icon name="whatsapp" /></span>
                        <span><span class="block font-semibold">WhatsApp</span><a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="text-stone-600 hover:text-ink">{{ $settings['whatsapp_number'] ?? $settings['contact_phone'] }}</a></span>
                    </li>
                @endif
                @if (! empty($settings['contact_email']))
                    <li class="flex gap-4">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-gold-50 text-gold-600"><x-icon name="mail" /></span>
                        <span><span class="block font-semibold">Email</span><a href="mailto:{{ $settings['contact_email'] }}" class="text-stone-600 hover:text-ink">{{ $settings['contact_email'] }}</a></span>
                    </li>
                @endif
                @if (! empty($settings['opening_hours']))
                    <li class="flex gap-4">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-gold-50 text-gold-600"><x-icon name="clock" /></span>
                        <span><span class="block font-semibold">Jam Layanan</span><span class="text-stone-600">{{ $settings['opening_hours'] }}</span></span>
                    </li>
                @endif
                @if (! empty($settings['contact_address']))
                    <li class="flex gap-4">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-gold-50 text-gold-600"><x-icon name="map-pin" /></span>
                        <span><span class="block font-semibold">Alamat</span><span class="text-stone-600">{{ $settings['contact_address'] }}</span></span>
                    </li>
                @endif
            </ul>
        </div>
        <div class="min-h-72 bg-cream">
            @if (! empty($settings['maps_embed_url']))
                <iframe src="{{ $settings['maps_embed_url'] }}" class="h-full min-h-72 w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi {{ $siteName }}"></iframe>
            @elseif ($aboutImage)
                <img src="{{ $aboutImage }}" alt="" class="h-full w-full object-cover" loading="lazy">
            @endif
        </div>
    </div>
</section>
@endsection
