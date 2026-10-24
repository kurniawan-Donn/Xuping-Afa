@extends('layouts.dashboard')
@section('title', 'Pengaturan Website — Admin Afa Xuping')
@section('header_title', 'Pengaturan Website')

@php
    $s = fn ($key, $default = '') => old($key, $settings[$key] ?? $default);
    $img = fn ($key) => ! empty($settings[$key]) ? asset('storage/' . $settings[$key]) : null;
    $tabs = ['umum' => 'Umum', 'whatsapp' => 'WhatsApp & Kontak', 'beranda' => 'Beranda & Tentang', 'seo' => 'Sosial & SEO', 'chatbot' => 'Chatbot AI'];
    $hasGeminiKey = filled(config('services.gemini.key'));
@endphp

@section('content')
<x-dash.header title="Pengaturan website" subtitle="Semua teks dan gambar di sini langsung tampil di website.">
    <a href="{{ route('website.index') }}" target="_blank" class="btn btn-outline"><x-icon name="external" class="h-4 w-4" /> Lihat website</a>
</x-dash.header>

<form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data"
    x-data="{ tab: (location.hash || '#umum').slice(1), wa: @js($s('whatsapp_number', $settings['contact_phone'] ?? '')), greeting: @js($s('whatsapp_greeting')) }"
    x-init="$watch('tab', t => history.replaceState(null, '', '#' + t))">
    @csrf

    <div class="no-scrollbar -mx-4 mb-6 flex gap-1 overflow-x-auto border-b border-stone-200 px-4 sm:mx-0 sm:px-0">
        @foreach ($tabs as $key => $label)
            <button type="button" @click="tab = '{{ $key }}'" :class="tab === '{{ $key }}' ? 'border-ink text-ink' : 'border-transparent text-stone-500 hover:text-ink'"
                class="-mb-px shrink-0 border-b-2 px-4 py-3 text-sm font-semibold transition">{{ $label }}</button>
        @endforeach
    </div>

    {{-- ============ UMUM ============ --}}
    <div x-show="tab === 'umum'" class="grid gap-6 lg:grid-cols-3">
        <section class="card space-y-4 p-5 lg:col-span-2 lg:p-6">
            <h2 class="font-semibold">Identitas toko</h2>
            <div>
                <label for="site_name" class="label">Nama toko</label>
                <input id="site_name" name="site_name" type="text" value="{{ $s('site_name', 'Afa Xuping Accessories') }}" maxlength="100" class="input">
            </div>
            <div>
                <label for="footer_text" class="label">Teks hak cipta (footer)</label>
                <input id="footer_text" name="footer_text" type="text" value="{{ $s('footer_text') }}" maxlength="150" class="input" placeholder="Afa Xuping Accessories">
            </div>
        </section>
        <section class="card p-5 lg:p-6" x-data="imagePreview(@js($img('logo')))">
            <p class="label">Logo</p>
            <label class="group relative grid h-32 cursor-pointer place-items-center overflow-hidden rounded-2xl border-2 border-dashed border-stone-300 bg-stone-50 transition hover:border-gold-400">
                <img x-show="url" :src="url" alt="" class="max-h-24 max-w-[80%] object-contain">
                <span x-show="!url" class="text-center text-xs text-stone-500"><x-icon name="upload" class="mx-auto mb-1 h-6 w-6" />Unggah logo</span>
                <input type="file" name="logo" accept="image/png,image/webp,image/jpeg" class="sr-only" @change="pick($event)">
            </label>
            <p class="hint mt-2">PNG transparan, tinggi ±120 px. Tanpa logo, nama toko ditampilkan sebagai teks.</p>
        </section>
    </div>

    {{-- ============ WHATSAPP & KONTAK ============ --}}
    <div x-show="tab === 'whatsapp'" x-cloak class="grid gap-6 lg:grid-cols-3">
        <section class="card space-y-4 p-5 lg:col-span-2 lg:p-6">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-wa/10 text-wa-dark"><x-icon name="whatsapp" /></span>
                <div>
                    <h2 class="font-semibold">WhatsApp pemesanan</h2>
                    <p class="text-xs text-stone-500">Semua tombol pesan di website mengarah ke nomor ini.</p>
                </div>
            </div>
            <div>
                <label for="whatsapp_number" class="label">Nomor WhatsApp Business</label>
                <input id="whatsapp_number" name="whatsapp_number" type="tel" x-model="wa" maxlength="20" class="input" placeholder="0812xxxxxxxx">
                <p class="hint">Boleh diawali 0 atau 62. Akan diubah otomatis ke format internasional.</p>
                @error('whatsapp_number')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="whatsapp_greeting" class="label">Kalimat pembuka pesan</label>
                <input id="whatsapp_greeting" name="whatsapp_greeting" type="text" x-model="greeting" maxlength="200" class="input" placeholder="Halo Afa Xuping Accessories, saya ingin memesan:">
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="contact_email" class="label">Email</label>
                    <input id="contact_email" name="contact_email" type="email" value="{{ $s('contact_email') }}" class="input">
                    @error('contact_email')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="opening_hours" class="label">Jam layanan</label>
                    <input id="opening_hours" name="opening_hours" type="text" value="{{ $s('opening_hours') }}" maxlength="100" class="input" placeholder="Setiap hari, 08.00–21.00 WITA">
                </div>
            </div>
            <div>
                <label for="contact_address" class="label">Alamat</label>
                <textarea id="contact_address" name="contact_address" rows="2" class="input">{{ $s('contact_address') }}</textarea>
            </div>
            <div>
                <label for="maps_embed_url" class="label">Link embed Google Maps <span class="font-normal text-stone-400">(opsional)</span></label>
                <textarea id="maps_embed_url" name="maps_embed_url" rows="2" class="input text-xs" placeholder='<iframe src="https://www.google.com/maps/embed?pb=…"></iframe>'>{{ $s('maps_embed_url') }}</textarea>
                <p class="hint">Di Google Maps: cari lokasi toko → <b>Bagikan</b> → tab <b>Sematkan peta</b> → <b>Salin HTML</b>, lalu tempel di sini. Link berbagi (<code>maps.app.goo.gl/…</code>) tidak bisa dipakai.</p>
                @error('maps_embed_url')<p class="error">{{ $message }}</p>@enderror
            </div>
        </section>

        {{-- Pratinjau pesan --}}
        <aside class="lg:sticky lg:top-24 lg:self-start">
            <p class="label">Pratinjau pesan</p>
            <div class="overflow-hidden rounded-2xl bg-[#efe7dd] p-4 shadow-soft">
                <div class="ml-auto max-w-[90%] rounded-xl rounded-tr-sm bg-[#d9fdd3] px-3 py-2 text-[13px] leading-relaxed text-stone-800 shadow-sm">
                    <p x-text="greeting || 'Halo {{ $settings['site_name'] ?? 'Afa Xuping Accessories' }}, saya ingin memesan:'"></p>
                    <p class="mt-2"><b>1. Cincin Permata Bunga</b><br>&nbsp;&nbsp;Ukuran: 7 | Warna: Gold<br>&nbsp;&nbsp;1 x Rp 150.000 = Rp 150.000</p>
                    <p class="mt-2"><b>Total: Rp 150.000</b></p>
                </div>
            </div>
            <p class="hint mt-2">Dikirim ke: <b x-text="wa ? wa.replace(/\D/g, '').replace(/^0/, '62') : '— belum diisi —'"></b></p>
        </aside>
    </div>

    {{-- ============ BERANDA & TENTANG ============ --}}
    <div x-show="tab === 'beranda'" x-cloak class="space-y-6">
        <section class="card grid gap-6 p-5 lg:grid-cols-3 lg:p-6">
            <div class="space-y-4 lg:col-span-2">
                <div>
                    <h2 class="font-semibold">Hero beranda</h2>
                    <p class="text-xs text-stone-500">Tampil jika belum ada banner aktif.</p>
                </div>
                <div>
                    <label for="hero_title" class="label">Judul utama</label>
                    <input id="hero_title" name="hero_title" type="text" value="{{ $s('hero_title') }}" maxlength="120" class="input" placeholder="Perhiasan Premium, Harga Bersahabat.">
                </div>
                <div>
                    <label for="hero_subtitle" class="label">Subjudul</label>
                    <textarea id="hero_subtitle" name="hero_subtitle" rows="3" maxlength="300" class="input">{{ $s('hero_subtitle') }}</textarea>
                </div>
            </div>
            <div x-data="imagePreview(@js($img('hero_image')))">
                <p class="label">Gambar hero</p>
                <label class="relative block aspect-[4/5] cursor-pointer overflow-hidden rounded-2xl border-2 border-dashed border-stone-300 bg-stone-50 hover:border-gold-400">
                    <img x-show="url" :src="url" alt="" class="h-full w-full object-cover">
                    <span x-show="!url" class="absolute inset-0 grid place-items-center text-xs text-stone-500">Unggah gambar</span>
                    <input type="file" name="hero_image" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="pick($event)">
                </label>
                <p class="hint mt-2">Potret (4:5) paling pas.</p>
            </div>
        </section>

        <section class="card grid gap-6 p-5 lg:grid-cols-3 lg:p-6">
            <div class="space-y-4 lg:col-span-2">
                <h2 class="font-semibold">Halaman Tentang Kami</h2>
                <div>
                    <label for="about_title" class="label">Judul</label>
                    <input id="about_title" name="about_title" type="text" value="{{ $s('about_title') }}" maxlength="120" class="input">
                </div>
                <div>
                    <label for="about_content" class="label">Cerita toko</label>
                    <textarea id="about_content" name="about_content" rows="8" class="input" placeholder="Ceritakan awal mula toko, nilai yang Anda pegang, dan kenapa pelanggan bisa percaya.">{{ $s('about_content') }}</textarea>
                </div>
            </div>
            <div x-data="imagePreview(@js($img('about_image')))">
                <p class="label">Foto</p>
                <label class="relative block aspect-[4/5] cursor-pointer overflow-hidden rounded-2xl border-2 border-dashed border-stone-300 bg-stone-50 hover:border-gold-400">
                    <img x-show="url" :src="url" alt="" class="h-full w-full object-cover">
                    <span x-show="!url" class="absolute inset-0 grid place-items-center text-xs text-stone-500">Unggah foto</span>
                    <input type="file" name="about_image" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="pick($event)">
                </label>
            </div>
        </section>
    </div>

    {{-- ============ SOSIAL & SEO ============ --}}
    <div x-show="tab === 'seo'" x-cloak class="grid gap-6 lg:grid-cols-3">
        <section class="card space-y-4 p-5 lg:col-span-2 lg:p-6">
            <h2 class="font-semibold">Media sosial & marketplace</h2>
            @foreach ([
                'instagram_url' => ['Instagram', 'instagram', 'https://instagram.com/afaxuping'],
                'tiktok_url' => ['TikTok', 'tiktok', 'https://tiktok.com/@afaxuping'],
                'facebook_url' => ['Facebook', 'facebook', 'https://facebook.com/afaxuping'],
                'shopee_url' => ['Shopee', 'store', 'https://shopee.co.id/afaxuping'],
            ] as $key => [$label, $icon, $placeholder])
                <div>
                    <label for="{{ $key }}" class="label">{{ $label }}</label>
                    <div class="relative">
                        <x-icon :name="$icon" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" />
                        <input id="{{ $key }}" name="{{ $key }}" type="url" value="{{ $s($key) }}" class="input pl-9" placeholder="{{ $placeholder }}">
                    </div>
                    @error($key)<p class="error">{{ $message }}</p>@enderror
                </div>
            @endforeach

            <div class="border-t border-stone-100 pt-4">
                <label for="meta_description" class="label">Deskripsi untuk Google</label>
                <textarea id="meta_description" name="meta_description" rows="3" maxlength="300" class="input" placeholder="Toko perhiasan Xuping di Bali. Cincin, kalung, gelang model terbaru, pesan mudah via WhatsApp.">{{ $s('meta_description') }}</textarea>
                <p class="hint">Idealnya 120–160 karakter. Juga tampil di footer.</p>
            </div>
        </section>
        <section class="card p-5 lg:p-6" x-data="imagePreview(@js($img('og_image')))">
            <p class="label">Gambar saat link dibagikan</p>
            <label class="relative block aspect-[1200/630] cursor-pointer overflow-hidden rounded-2xl border-2 border-dashed border-stone-300 bg-stone-50 hover:border-gold-400">
                <img x-show="url" :src="url" alt="" class="h-full w-full object-cover">
                <span x-show="!url" class="absolute inset-0 grid place-items-center px-4 text-center text-xs text-stone-500">Unggah gambar 1200×630</span>
                <input type="file" name="og_image" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="pick($event)">
            </label>
            <p class="hint mt-2">Muncul saat link beranda dibagikan ke WhatsApp/Facebook. Halaman produk otomatis memakai foto produk.</p>
        </section>
    </div>

    {{-- ============ CHATBOT ============ --}}
    <div x-show="tab === 'chatbot'" x-cloak class="grid gap-6 lg:grid-cols-3">
        <section class="card space-y-5 p-5 lg:col-span-2 lg:p-6">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-gold-50 text-gold-600"><x-icon name="sparkles" /></span>
                <div>
                    <h2 class="font-semibold">Asisten "Tanya Afa"</h2>
                    <p class="text-xs text-stone-500">Menjawab pertanyaan pengunjung & merekomendasikan produk dari katalog.</p>
                </div>
            </div>

            @unless ($hasGeminiKey)
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                    <b>API key belum terpasang.</b> Tambahkan baris <code>GEMINI_API_KEY=...</code> di file <code>.env</code> server. Chatbot tidak akan tampil di website sebelum key diisi.
                </div>
            @endunless

            <label class="flex cursor-pointer items-start justify-between gap-4 rounded-xl border border-stone-200 p-4">
                <span><span class="block text-sm font-medium">Tampilkan chatbot di website</span><span class="block text-xs text-stone-500">Matikan kapan saja bila biaya ingin dihentikan.</span></span>
                <input type="hidden" name="chatbot_enabled" value="0">
                <input type="checkbox" name="chatbot_enabled" value="1" @checked($s('chatbot_enabled')) class="peer sr-only">
                <span class="relative h-6 w-11 shrink-0 rounded-full bg-stone-200 transition after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:bg-ink peer-checked:after:translate-x-5"></span>
            </label>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="chatbot_daily_limit" class="label">Batas pesan per hari (semua pengunjung)</label>
                    <input id="chatbot_daily_limit" name="chatbot_daily_limit" type="number" min="0" max="5000" value="{{ $s('chatbot_daily_limit', \App\Support\Chatbot::DEFAULT_DAILY_LIMIT) }}" class="input">
                    <p class="hint">100 pesan/hari ≈ maks. Rp 50.000/bulan.</p>
                </div>
                <div>
                    <label for="chatbot_visitor_limit" class="label">Batas pesan per pengunjung per hari</label>
                    <input id="chatbot_visitor_limit" name="chatbot_visitor_limit" type="number" min="0" max="200" value="{{ $s('chatbot_visitor_limit', \App\Support\Chatbot::DEFAULT_VISITOR_LIMIT) }}" class="input">
                    <p class="hint">Mencegah satu orang menghabiskan kuota.</p>
                </div>
            </div>
            <p class="text-xs text-stone-500">Bila batas tercapai, pengunjung diarahkan ke WhatsApp admin. Model: <code>{{ config('services.gemini.model') }}</code>.</p>
        </section>

        <aside class="card space-y-3 p-5 text-sm lg:p-6">
            <p class="font-semibold">Pemakaian & riwayat</p>
            <p class="text-stone-500">Lihat jumlah pesan, perkiraan biaya bulan ini, dan pertanyaan yang paling sering ditanyakan pengunjung.</p>
            <a href="{{ route('chatbot.index') }}" class="btn btn-outline w-full">Buka riwayat chatbot</a>
            <p class="hint">Pasang juga <b>budget alert</b> di Google Cloud Billing sebagai pengaman kedua.</p>
        </aside>
    </div>

    <div class="sticky bottom-4 z-10 mt-6 flex justify-end">
        <button type="submit" class="btn btn-dark btn-lg shadow-lift">Simpan Pengaturan</button>
    </div>
</form>
@endsection
