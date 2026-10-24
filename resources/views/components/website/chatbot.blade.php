{{-- Chatbot "Tanya Afa" — hanya dirender bila aktif & API key terpasang --}}
<div x-data="chatbot" @keydown.escape.window="open = false">
    {{-- Tombol pembuka --}}
    <button type="button" x-show="!open" x-transition.opacity @click="toggle()"
        class="fixed bottom-20 right-4 z-30 flex items-center gap-2 rounded-full bg-ink py-2.5 pl-3 pr-4 text-sm font-semibold text-white shadow-lift transition hover:bg-stone-800 lg:bottom-24 lg:right-6 [.has-sticky-actions_&]:bottom-[9.75rem] sm:[.has-sticky-actions_&]:bottom-20 lg:[.has-sticky-actions_&]:bottom-24"
        aria-label="Buka chat Tanya Afa">
        <span class="relative grid h-7 w-7 place-items-center rounded-full bg-gold-500/20 text-gold-300">
            <x-icon name="sparkles" class="h-4 w-4" />
            <span class="absolute -right-0.5 -top-0.5 h-2.5 w-2.5 rounded-full bg-emerald-400 ring-2 ring-ink"></span>
        </span>
        Tanya Afa
    </button>

    {{-- Latar gelap (HP) --}}
    <div x-cloak x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 z-50 bg-ink/40 backdrop-blur-sm sm:hidden"></div>

    {{-- Panel percakapan --}}
    <section x-cloak x-show="open" role="dialog" aria-label="Chat Tanya Afa"
        x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="translate-y-6 opacity-0"
        class="fixed inset-x-0 bottom-0 z-50 flex h-[82vh] flex-col overflow-hidden rounded-t-3xl bg-ivory shadow-lift sm:inset-x-auto sm:bottom-6 sm:right-6 sm:h-[600px] sm:max-h-[calc(100vh-3rem)] sm:w-[400px] sm:rounded-3xl sm:border sm:border-stone-200">

        <header class="flex items-center gap-3 bg-ink px-5 py-4 text-white">
            <span class="relative grid h-10 w-10 place-items-center rounded-full bg-gold-500/20 font-display text-lg font-bold text-gold-300 ring-1 ring-gold-400/30">
                A
                <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full bg-emerald-400 ring-2 ring-ink"></span>
            </span>
            <div class="min-w-0 flex-1">
                <p class="font-semibold leading-tight">Tanya Afa</p>
                <p class="text-xs text-stone-400">Asisten AI · biasanya menjawab dalam hitungan detik</p>
            </div>
            <button type="button" x-show="messages.length > 1" @click="reset()" class="grid h-9 w-9 place-items-center rounded-full text-stone-400 hover:bg-white/10 hover:text-white" title="Mulai ulang" aria-label="Mulai percakapan baru">
                <x-icon name="edit" class="h-4 w-4" />
            </button>
            <button type="button" @click="open = false" class="grid h-9 w-9 place-items-center rounded-full text-stone-400 hover:bg-white/10 hover:text-white" aria-label="Tutup chat">
                <x-icon name="x" class="h-5 w-5" />
            </button>
        </header>

        <div x-ref="scroll" class="flex-1 space-y-4 overflow-y-auto px-4 py-5" aria-live="polite">
            <template x-for="(m, i) in messages" :key="i">
                <div :class="m.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div class="max-w-[85%] space-y-2">
                        <div :class="m.role === 'user' ? 'rounded-br-md bg-ink text-white' : 'rounded-bl-md border border-stone-200/70 bg-white text-stone-700'"
                            class="whitespace-pre-line rounded-2xl px-4 py-2.5 text-sm leading-relaxed shadow-sm" x-text="m.text"></div>

                        <template x-if="m.products && m.products.length">
                            <div class="space-y-2">
                                <template x-for="p in m.products" :key="p.id">
                                    <div class="flex items-center gap-3 rounded-2xl border border-stone-200/70 bg-white p-2.5 shadow-sm">
                                        <a :href="p.url" class="h-14 w-14 shrink-0 overflow-hidden rounded-xl bg-cream">
                                            <img :src="p.image" :alt="p.name" class="h-full w-full object-cover" loading="lazy">
                                        </a>
                                        <div class="min-w-0 flex-1">
                                            <a :href="p.url" class="line-clamp-2 text-xs font-semibold leading-snug hover:text-gold-700" x-text="p.name"></a>
                                            <p class="mt-0.5 text-xs">
                                                <span class="font-semibold" x-text="rupiah(p.price)"></span>
                                                <span x-show="p.compare_price" class="ml-1 text-stone-400 line-through" x-text="rupiah(p.compare_price)"></span>
                                                <span x-show="!p.in_stock" class="ml-1 text-stone-500">· Habis</span>
                                            </p>
                                        </div>
                                        <template x-if="p.in_stock && !p.has_variants">
                                            <button type="button" @click="$store.cart.add(p)" class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-ink text-white hover:bg-stone-800" :aria-label="'Tambah ' + p.name + ' ke pesanan'">
                                                <x-icon name="plus" class="h-4 w-4" stroke="2" />
                                            </button>
                                        </template>
                                        <template x-if="!p.in_stock || p.has_variants">
                                            <a :href="p.url" class="shrink-0 rounded-full border border-stone-300 px-3 py-1.5 text-[11px] font-semibold hover:border-ink">Pilih</a>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="m.handoff">
                            <button type="button" @click="handoff()" class="btn btn-wa btn-sm"><x-icon name="whatsapp" class="h-4 w-4" /> Chat admin di WhatsApp</button>
                        </template>
                    </div>
                </div>
            </template>

            <div x-show="loading" class="flex justify-start">
                <div class="flex items-center gap-1 rounded-2xl rounded-bl-md border border-stone-200/70 bg-white px-4 py-3 shadow-sm" aria-label="Afa sedang mengetik">
                    <span class="h-2 w-2 animate-bounce rounded-full bg-gold-400"></span>
                    <span class="h-2 w-2 animate-bounce rounded-full bg-gold-400 [animation-delay:150ms]"></span>
                    <span class="h-2 w-2 animate-bounce rounded-full bg-gold-400 [animation-delay:300ms]"></span>
                </div>
            </div>

            <div x-show="messages.length === 1 && !loading" class="flex flex-wrap gap-2 pt-1">
                <template x-for="s in suggestions" :key="s">
                    <button type="button" @click="send(s)" class="rounded-full border border-gold-200 bg-gold-50 px-3 py-1.5 text-xs font-medium text-gold-800 transition hover:border-gold-400" x-text="s"></button>
                </template>
            </div>
        </div>

        <form @submit.prevent="send()" class="border-t border-stone-200 bg-white p-3 pb-[max(0.75rem,env(safe-area-inset-bottom))]">
            <div class="flex items-end gap-2">
                <textarea x-ref="input" x-model="draft" @keydown.enter.prevent="if (!$event.shiftKey) send()" rows="1" maxlength="500"
                    class="input max-h-28 min-h-[44px] flex-1 resize-none rounded-2xl" placeholder="Tulis pertanyaan…" aria-label="Pertanyaan untuk Afa"></textarea>
                <button type="submit" :disabled="loading || !draft.trim()" class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-ink text-white transition hover:bg-stone-800 disabled:opacity-40" aria-label="Kirim">
                    <x-icon name="arrow-right" class="h-5 w-5" stroke="2" />
                </button>
            </div>
            <p class="mt-2 text-center text-[10px] text-stone-400">Jawaban AI bisa keliru. Harga & stok final dikonfirmasi admin.</p>
        </form>
    </section>
</div>
