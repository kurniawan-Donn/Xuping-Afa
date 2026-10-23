<div x-data x-cloak x-show="$store.cart.open" class="fixed inset-0 z-50" role="dialog" aria-modal="true" aria-label="Daftar Pesanan"
    @keydown.escape.window="$store.cart.open = false">
    <div x-show="$store.cart.open" x-transition.opacity class="absolute inset-0 bg-ink/40 backdrop-blur-sm" @click="$store.cart.open = false"></div>

    <aside x-show="$store.cart.open"
        x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        class="absolute inset-y-0 right-0 flex w-full max-w-md flex-col bg-ivory shadow-lift">

        <div class="flex items-center justify-between border-b border-stone-200 px-5 py-4">
            <div>
                <p class="font-display text-2xl font-bold">Daftar Pesanan</p>
                <p class="text-xs text-stone-500"><span x-text="$store.cart.count"></span> barang &middot; dikirim lewat WhatsApp</p>
            </div>
            <button type="button" @click="$store.cart.open = false" class="btn-ghost grid h-10 w-10 place-items-center rounded-full" aria-label="Tutup">
                <x-icon name="x" />
            </button>
        </div>

        {{-- Kosong --}}
        <div x-show="!$store.cart.items.length" class="flex flex-1 flex-col items-center justify-center px-8 text-center">
            <span class="grid h-20 w-20 place-items-center rounded-full bg-gold-100 text-gold-600"><x-icon name="bag" class="h-9 w-9" stroke="1.5" /></span>
            <p class="mt-5 font-display text-2xl font-semibold">Belum ada pesanan</p>
            <p class="mt-2 text-sm text-stone-500">Pilih produk dan tekan <b>Tambah ke Pesanan</b>. Semua pesanan dikirim sekaligus dalam satu chat WhatsApp.</p>
            <a href="{{ route('website.products.index') }}" class="btn btn-dark mt-6">Lihat Katalog</a>
        </div>

        {{-- Isi --}}
        <div x-show="$store.cart.items.length" class="flex-1 overflow-y-auto px-5 py-4">
            <ul class="space-y-3">
                <template x-for="item in $store.cart.items" :key="item.key">
                    <li class="flex gap-3 rounded-2xl border border-stone-200/70 bg-white p-3">
                        <a :href="item.url" class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-cream">
                            <img :src="item.image" :alt="item.name" class="h-full w-full object-cover">
                        </a>
                        <div class="flex min-w-0 flex-1 flex-col">
                            <div class="flex items-start justify-between gap-2">
                                <a :href="item.url" class="line-clamp-2 text-sm font-semibold leading-snug" x-text="item.name"></a>
                                <button type="button" @click="$store.cart.remove(item)" class="-m-1 p-1 text-stone-400 hover:text-red-600" aria-label="Hapus">
                                    <x-icon name="trash" class="h-4 w-4" />
                                </button>
                            </div>
                            <p class="mt-0.5 text-xs text-stone-500" x-show="item.size || item.color"
                                x-text="[item.size && 'Ukuran ' + item.size, item.color].filter(Boolean).join(' · ')"></p>
                            <div class="mt-auto flex items-center justify-between pt-2">
                                <div class="flex items-center rounded-full border border-stone-200">
                                    <button type="button" @click="$store.cart.setQty(item, item.qty - 1)" class="grid h-7 w-7 place-items-center text-stone-500 hover:text-ink" aria-label="Kurangi"><x-icon name="minus" class="h-3.5 w-3.5" /></button>
                                    <span class="w-6 text-center text-sm font-semibold" x-text="item.qty"></span>
                                    <button type="button" @click="$store.cart.setQty(item, item.qty + 1)" class="grid h-7 w-7 place-items-center text-stone-500 hover:text-ink" aria-label="Tambah"><x-icon name="plus" class="h-3.5 w-3.5" /></button>
                                </div>
                                <p class="text-sm font-semibold" x-text="rupiah(item.qty * item.price)"></p>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>

            <div x-data="{ open: !!($store.cart.customer.name || $store.cart.customer.address) }" class="mt-5 rounded-2xl border border-stone-200/70 bg-white">
                <button type="button" @click="open = !open" class="flex w-full items-center justify-between px-4 py-3 text-sm font-semibold">
                    <span>Data pengiriman <span class="font-normal text-stone-400">(opsional)</span></span>
                    <x-icon name="chevron-down" class="ml-auto h-4 w-4 transition" x-bind:class="open && 'rotate-180'" />
                </button>
                <div x-show="open" x-collapse class="space-y-3 px-4 pb-4">
                    <input type="text" x-model="$store.cart.customer.name" @change="$store.cart.persist()" class="input" placeholder="Nama penerima" autocomplete="name">
                    <textarea x-model="$store.cart.customer.address" @change="$store.cart.persist()" rows="2" class="input" placeholder="Alamat lengkap" autocomplete="street-address"></textarea>
                    <input type="text" x-model="$store.cart.customer.note" @change="$store.cart.persist()" class="input" placeholder="Catatan (misal: untuk kado)">
                    <p class="hint">Disimpan di perangkat Anda saja dan hanya dikirim di pesan WhatsApp.</p>
                </div>
            </div>
        </div>

        <div x-show="$store.cart.items.length" class="border-t border-stone-200 bg-white px-5 pb-[max(1rem,env(safe-area-inset-bottom))] pt-4">
            <div class="flex items-baseline justify-between">
                <span class="text-sm text-stone-500">Total perkiraan</span>
                <span class="font-display text-2xl font-bold" x-text="rupiah($store.cart.total)"></span>
            </div>
            <p class="mt-1 text-xs text-stone-500">Ongkir dan ketersediaan dikonfirmasi admin di WhatsApp.</p>
            <button type="button" @click="$store.cart.send()" class="btn btn-wa btn-lg mt-4 w-full">
                <x-icon name="whatsapp" class="h-5 w-5" /> Kirim Pesanan via WhatsApp
            </button>
            <button type="button" @click="$store.cart.clear()" class="mt-2 w-full py-2 text-xs font-medium text-stone-400 hover:text-red-600">Kosongkan daftar</button>
        </div>
    </aside>
</div>
