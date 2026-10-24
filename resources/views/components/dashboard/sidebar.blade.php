@php
    $groups = [
        'Utama' => [
            ['Dashboard', 'layout', 'dashboard', 'dashboard'],
        ],
        'Katalog' => [
            ['Produk', 'box', 'products.index', 'products.*'],
            ['Kategori', 'tag', 'categories.index', 'categories.*'],
        ],
        'Konten Website' => [
            ['Banner', 'image', 'banners.index', 'banners.*'],
            ['Testimoni', 'quote', 'testimonials.index', 'testimonials.*'],
        ],
    ];

    if (auth()->user()->hasAnyRole(['owner', 'superadmin'])) {
        $groups['Pengaturan'] = [
            ['Pengguna', 'users', 'users.index', 'users.*'],
            ['Chatbot AI', 'sparkles', 'chatbot.index', 'chatbot.*'],
            ['Website & WhatsApp', 'settings', 'settings.index', 'settings.*'],
        ];
    }
@endphp
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col bg-ink transition-transform duration-300 lg:translate-x-0">
    <div class="flex h-16 shrink-0 items-center justify-between px-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <span class="grid h-9 w-9 place-items-center rounded-full bg-gold-500/15 font-display text-lg font-bold text-gold-300 ring-1 ring-gold-400/30">A</span>
            <span class="leading-none">
                <span class="block font-display text-lg font-bold text-white">Afa Xuping</span>
                <span class="block text-[10px] font-medium uppercase tracking-[0.2em] text-gold-400">Panel Admin</span>
            </span>
        </a>
        <button type="button" @click="sidebarOpen = false" class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-white/5 hover:text-white lg:hidden" aria-label="Tutup menu">
            <x-icon name="x" />
        </button>
    </div>

    <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-4" aria-label="Menu admin">
        @foreach ($groups as $label => $items)
            <div>
                <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[0.18em] text-stone-500">{{ $label }}</p>
                <div class="space-y-0.5">
                    @foreach ($items as [$name, $icon, $route, $active])
                        <a href="{{ route($route) }}" class="nav-link {{ request()->routeIs($active) ? 'nav-link-active' : '' }}">
                            <x-icon :name="$icon" class="h-[18px] w-[18px]" />
                            {{ $name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="shrink-0 border-t border-white/10 p-3">
        <a href="{{ route('website.index') }}" target="_blank" class="nav-link">
            <x-icon name="external" class="h-[18px] w-[18px]" /> Lihat Website
        </a>
        <a href="{{ route('profile.index') }}" class="mt-1 flex items-center gap-3 rounded-xl p-2 transition hover:bg-white/5">
            <img src="{{ auth()->user()->avatar_url }}" alt="" class="h-9 w-9 rounded-full object-cover ring-1 ring-white/10">
            <span class="min-w-0 flex-1">
                <span class="block truncate text-sm font-medium text-white">{{ auth()->user()->name }}</span>
                <span class="block truncate text-xs capitalize text-stone-500">{{ auth()->user()->getRoleNames()->first() ?? 'admin' }}</span>
            </span>
        </a>
    </div>
</aside>
