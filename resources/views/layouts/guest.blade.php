@extends('layouts.app')

@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('body')
<div class="grid min-h-screen lg:grid-cols-2">
    <div class="relative hidden overflow-hidden bg-ink lg:block">
        <div class="absolute -left-24 top-1/4 h-96 w-96 rounded-full bg-gold-500/20 blur-3xl"></div>
        <div class="absolute -bottom-20 right-0 h-80 w-80 rounded-full bg-gold-300/10 blur-3xl"></div>
        <div class="relative flex h-full flex-col justify-between p-12">
            <a href="{{ route('website.index') }}" class="flex items-center gap-2.5">
                <span class="grid h-10 w-10 place-items-center rounded-full bg-gold-500/15 font-display text-xl font-bold text-gold-300 ring-1 ring-gold-400/30">A</span>
                <span class="font-display text-2xl font-bold text-white">Afa Xuping</span>
            </a>
            <div>
                <p class="font-display text-5xl font-semibold leading-tight text-white">Kelola katalog,<br><span class="italic text-gold-300">pantau minat pembeli.</span></p>
                <p class="mt-4 max-w-sm text-sm text-stone-400">Panel admin Afa Xuping Accessories untuk mengatur produk, stok, dan tampilan website.</p>
            </div>
            <p class="text-xs text-stone-500">&copy; {{ date('Y') }} Afa Xuping Accessories</p>
        </div>
    </div>
    <div class="flex items-center justify-center bg-ivory px-4 py-12">
        @yield('content')
    </div>
</div>
@endsection
