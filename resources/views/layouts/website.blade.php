@extends('layouts.app')

@php
    $siteName = $settings['site_name'] ?? 'Afa Xuping Accessories';
    $metaTitle = trim($__env->yieldContent('meta_title')) ?: (trim($__env->yieldContent('title')) ?: $siteName);
    $metaDescription = trim($__env->yieldContent('meta_description'))
        ?: ($settings['meta_description'] ?? 'Perhiasan Xuping berkualitas dengan harga bersahabat. Pilih model favorit Anda dan pesan langsung lewat WhatsApp.');
    $metaImage = trim($__env->yieldContent('meta_image'))
        ?: (! empty($settings['og_image']) ? asset('storage/' . $settings['og_image'])
        : (! empty($settings['hero_image']) ? asset('storage/' . $settings['hero_image']) : asset('android-chrome-512x512.png')));
    $afaConfig = [
        'wa' => \App\Models\Setting::whatsappNumber(),
        'greeting' => $settings['whatsapp_greeting'] ?? "Halo {$siteName}, saya ingin memesan:",
        'clickUrl' => route('website.wa-click'),
        'chatUrl' => route('website.chat'),
        'csrf' => csrf_token(),
    ];
@endphp

@section('title', $metaTitle)

@push('meta')
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:locale" content="id_ID">
    <meta name="twitter:card" content="summary_large_image">
    <script>
        window.AFA = @json($afaConfig);
    </script>
@endpush

@section('body')
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full focus:bg-ink focus:px-4 focus:py-2 focus:text-white">Lewati ke konten</a>

    @include('components.website.navbar')

    <main id="konten" class="min-h-screen pb-20 lg:pb-0">
        @yield('content')
    </main>

    @include('components.website.footer')
    @include('components.website.cart-drawer')
    @include('components.website.bottom-nav')
    @if (\App\Support\Chatbot::enabled())
        @include('components.website.chatbot')
    @endif
    @include('components.website.toast')
@endsection
