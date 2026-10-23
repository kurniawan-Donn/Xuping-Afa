@php
    $siteName = $settings['site_name'] ?? 'Afa Xuping Accessories';
    $links = [
        ['label' => 'Beranda', 'route' => 'website.index', 'active' => 'website.index'],
        ['label' => 'Katalog', 'route' => 'website.products.index', 'active' => 'website.products.*'],
        ['label' => 'Tentang Kami', 'route' => 'website.about', 'active' => 'website.about'],
    ];
@endphp
<div class="bg-ink text-center text-[11px] font-medium tracking-wide text-gold-100">
    <div class="container-page flex h-8 items-center justify-center gap-2">
        <x-icon name="sparkles" class="h-3.5 w-3.5 text-gold-300" />
        Pilih perhiasan favorit &middot; Pesan langsung lewat WhatsApp
    </div>
</div>

<header x-data="{ scrolled: false, search: false }" @scroll.window="scrolled = window.scrollY > 8"
    :class="scrolled ? 'shadow-soft bg-ivory/90' : 'bg-ivory/70'"
    class="sticky top-0 z-40 border-b border-stone-200/60 backdrop-blur-lg transition">
    <div class="container-page flex h-16 items-center justify-between gap-4 lg:h-20">
        <a href="{{ route('website.index') }}" class="flex items-center gap-2.5" aria-label="{{ $siteName }} — Beranda">
            @if (! empty($settings['logo']))
                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="{{ $siteName }}" class="h-9 w-auto lg:h-11">
            @else
                <span class="grid h-9 w-9 place-items-center rounded-full bg-ink font-display text-lg font-bold text-gold-300 lg:h-10 lg:w-10">A</span>
                <span class="leading-none">
                    <span class="block font-display text-xl font-bold tracking-tight lg:text-2xl">Afa Xuping</span>
                    <span class="block text-[10px] font-medium uppercase tracking-[0.25em] text-gold-600">Accessories</span>
                </span>
            @endif
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="Navigasi utama">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                    class="{{ request()->routeIs($link['active']) ? 'text-ink after:scale-x-100' : 'text-stone-500 hover:text-ink after:scale-x-0' }} relative px-4 py-2 text-sm font-medium transition after:absolute after:inset-x-4 after:-bottom-0.5 after:h-px after:origin-left after:bg-gold-500 after:transition">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-1">
            <button type="button" @click="search = true; $nextTick(() => $refs.q.focus())" class="btn-ghost grid h-10 w-10 place-items-center rounded-full" aria-label="Cari produk">
                <x-icon name="search" />
            </button>
            <button type="button" @click="$store.cart.open = true" class="btn-ghost relative grid h-10 w-10 place-items-center rounded-full" aria-label="Buka Daftar Pesanan">
                <x-icon name="bag" />
                <span x-cloak x-show="$store.cart.count" x-text="$store.cart.count"
                    class="absolute -right-0.5 -top-0.5 grid h-5 min-w-5 place-items-center rounded-full bg-gold-500 px-1 text-[10px] font-bold text-white"></span>
            </button>
            <a href="{{ route('website.products.index') }}" class="btn btn-dark ml-2 hidden lg:inline-flex">
                Belanja Sekarang
            </a>
        </div>
    </div>

    {{-- Pencarian --}}
    <div x-cloak x-show="search" x-transition.opacity class="fixed inset-0 z-50 bg-ink/40 backdrop-blur-sm" @click="search = false"></div>
    <div x-cloak x-show="search" x-transition @keydown.escape.window="search = false"
        class="fixed inset-x-0 top-0 z-50 border-b border-stone-200 bg-ivory shadow-lift">
        <form action="{{ route('website.products.index') }}" method="GET" class="container-page flex h-20 items-center gap-3">
            <x-icon name="search" class="h-5 w-5 text-stone-400" />
            <input x-ref="q" type="search" name="search" value="{{ request('search') }}" placeholder="Cari cincin, kalung, gelang…"
                class="h-full flex-1 border-0 bg-transparent font-display text-xl outline-none placeholder:text-stone-400 focus:ring-0 lg:text-2xl">
            <button type="button" @click="search = false" class="btn-ghost grid h-10 w-10 place-items-center rounded-full" aria-label="Tutup pencarian">
                <x-icon name="x" />
            </button>
        </form>
    </div>
</header>
