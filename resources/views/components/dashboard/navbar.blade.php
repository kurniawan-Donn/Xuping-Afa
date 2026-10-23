<header class="sticky top-0 z-30 flex h-16 items-center justify-between gap-4 border-b border-stone-200/70 bg-white/85 px-4 backdrop-blur-lg sm:px-6 lg:px-8">
    <div class="flex min-w-0 items-center gap-3">
        <button type="button" @click="sidebarOpen = true" class="grid h-10 w-10 place-items-center rounded-xl text-stone-500 hover:bg-stone-100 lg:hidden" aria-label="Buka menu">
            <x-icon name="menu" />
        </button>
        <p class="truncate text-sm font-semibold text-stone-800">@yield('header_title', 'Dashboard')</p>
    </div>

    <div class="flex items-center gap-2">
        <a href="{{ route('products.create') }}" class="btn btn-dark btn-sm hidden sm:inline-flex">
            <x-icon name="plus" class="h-4 w-4" /> Produk
        </a>
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button type="button" @click="open = !open" class="flex items-center gap-2 rounded-full p-1 pr-2 transition hover:bg-stone-100" :aria-expanded="open">
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="h-8 w-8 rounded-full object-cover">
                <x-icon name="chevron-down" class="h-4 w-4 text-stone-400 transition" x-bind:class="open && 'rotate-180'" />
            </button>
            <div x-cloak x-show="open" x-transition.origin.top.right class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-stone-200 bg-white py-1.5 shadow-lift">
                <div class="border-b border-stone-100 px-4 pb-2.5 pt-1.5">
                    <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-stone-500">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('profile.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-stone-700 hover:bg-stone-50"><x-icon name="user" class="h-4 w-4 text-stone-400" /> Profil Saya</a>
                <a href="{{ route('website.index') }}" target="_blank" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-stone-700 hover:bg-stone-50"><x-icon name="external" class="h-4 w-4 text-stone-400" /> Lihat Website</a>
                <form method="POST" action="{{ route('logout') }}" class="border-t border-stone-100 pt-1">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50"><x-icon name="logout" class="h-4 w-4" /> Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>
