@php
    $wa = \App\Models\Setting::whatsappNumber();
    $items = [
        ['label' => 'Beranda', 'icon' => 'home', 'route' => 'website.index', 'active' => 'website.index'],
        ['label' => 'Katalog', 'icon' => 'grid', 'route' => 'website.products.index', 'active' => 'website.products.*'],
        ['label' => 'Tentang', 'icon' => 'info', 'route' => 'website.about', 'active' => 'website.about'],
    ];
@endphp

{{-- Tombol WhatsApp melayang (desktop) --}}
@if ($wa)
    <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" aria-label="Chat WhatsApp"
        class="group fixed bottom-6 right-6 z-30 hidden items-center gap-2 rounded-full bg-wa py-3 pl-3 pr-3 text-white shadow-lift transition-all hover:bg-wa-dark hover:pr-5 lg:flex">
        <x-icon name="whatsapp" class="h-7 w-7" />
        <span class="max-w-0 overflow-hidden whitespace-nowrap text-sm font-semibold transition-all duration-300 group-hover:max-w-40">Tanya via WhatsApp</span>
    </a>
@endif

{{-- Navigasi bawah ala aplikasi (HP) --}}
<nav class="fixed inset-x-0 bottom-0 z-30 border-t border-stone-200 bg-white/95 pb-[env(safe-area-inset-bottom)] backdrop-blur-lg lg:hidden" aria-label="Navigasi bawah">
    <div class="grid h-16 grid-cols-5">
        @foreach ($items as $i => $item)
            <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['active']) ? 'text-ink' : 'text-stone-400' }} flex flex-col items-center justify-center gap-1 text-[10px] font-medium">
                <x-icon :name="$item['icon']" class="h-5.5 w-5.5" :stroke="request()->routeIs($item['active']) ? 2.1 : 1.75" />
                {{ $item['label'] }}
            </a>
            @if ($i === 1)
                <button type="button" @click="$store.cart.open = true" class="relative flex flex-col items-center justify-center gap-1 text-[10px] font-medium text-stone-400">
                    <span class="relative -mt-7 grid h-13 w-13 place-items-center rounded-full bg-ink text-white shadow-lift ring-4 ring-ivory">
                        <x-icon name="bag" class="h-5.5 w-5.5" />
                        <span x-cloak x-show="$store.cart.count" x-text="$store.cart.count"
                            class="absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full bg-gold-500 px-1 text-[10px] font-bold"></span>
                    </span>
                    Pesanan
                </button>
            @endif
        @endforeach
        @if ($wa)
            <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="flex flex-col items-center justify-center gap-1 text-[10px] font-medium text-wa-dark">
                <x-icon name="whatsapp" class="h-5.5 w-5.5" />
                Chat
            </a>
        @endif
    </div>
</nav>
