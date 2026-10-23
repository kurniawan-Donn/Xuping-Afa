@php
    $siteName = $settings['site_name'] ?? 'Afa Xuping Accessories';
    $socials = array_filter([
        'instagram' => $settings['instagram_url'] ?? null,
        'tiktok' => $settings['tiktok_url'] ?? null,
        'facebook' => $settings['facebook_url'] ?? null,
    ]);
    $wa = \App\Models\Setting::whatsappNumber();
@endphp
<footer class="mt-24 bg-ink text-stone-400">
    <div class="container-page grid gap-12 py-16 md:grid-cols-2 lg:grid-cols-4">
        <div class="lg:col-span-2">
            <p class="font-display text-3xl font-bold text-white">Afa Xuping</p>
            <p class="mt-1 text-[10px] font-semibold uppercase tracking-[0.3em] text-gold-400">Accessories</p>
            <p class="mt-5 max-w-sm text-sm leading-relaxed">
                {{ $settings['meta_description'] ?? 'Perhiasan Xuping pilihan untuk menemani setiap momen Anda. Pesan mudah, langsung lewat WhatsApp.' }}
            </p>
            @if ($socials || ! empty($settings['shopee_url']))
                <div class="mt-6 flex items-center gap-2">
                    @foreach ($socials as $icon => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($icon) }}"
                            class="grid h-10 w-10 place-items-center rounded-full border border-white/10 text-stone-300 transition hover:border-gold-400 hover:text-gold-300">
                            <x-icon :name="$icon" class="h-4.5 w-4.5" />
                        </a>
                    @endforeach
                    @if (! empty($settings['shopee_url']))
                        <a href="{{ $settings['shopee_url'] }}" target="_blank" rel="noopener"
                            class="rounded-full border border-white/10 px-4 py-2 text-xs font-semibold text-stone-300 transition hover:border-gold-400 hover:text-gold-300">Shopee</a>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <p class="eyebrow text-gold-400">Jelajahi</p>
            <ul class="mt-5 space-y-3 text-sm">
                <li><a href="{{ route('website.index') }}" class="transition hover:text-white">Beranda</a></li>
                <li><a href="{{ route('website.products.index') }}" class="transition hover:text-white">Katalog Produk</a></li>
                <li><a href="{{ route('website.products.index', ['sort' => 'popular']) }}" class="transition hover:text-white">Paling Diminati</a></li>
                <li><a href="{{ route('website.about') }}" class="transition hover:text-white">Tentang Kami</a></li>
            </ul>
        </div>

        <div>
            <p class="eyebrow text-gold-400">Hubungi Kami</p>
            <ul class="mt-5 space-y-3 text-sm">
                @if ($wa)
                    <li><a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="flex items-center gap-2.5 transition hover:text-white"><x-icon name="whatsapp" class="h-4 w-4" /> {{ $settings['whatsapp_number'] ?? $settings['contact_phone'] }}</a></li>
                @endif
                @if (! empty($settings['contact_email']))
                    <li><a href="mailto:{{ $settings['contact_email'] }}" class="flex items-center gap-2.5 transition hover:text-white"><x-icon name="mail" class="h-4 w-4" /> {{ $settings['contact_email'] }}</a></li>
                @endif
                @if (! empty($settings['opening_hours']))
                    <li class="flex items-start gap-2.5"><x-icon name="clock" class="mt-0.5 h-4 w-4" /> {{ $settings['opening_hours'] }}</li>
                @endif
                @if (! empty($settings['contact_address']))
                    <li class="flex items-start gap-2.5"><x-icon name="map-pin" class="mt-0.5 h-4 w-4" /> <span>{{ $settings['contact_address'] }}</span></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="container-page flex flex-col items-center justify-between gap-2 py-6 text-xs sm:flex-row">
            <p>&copy; {{ date('Y') }} {{ $settings['footer_text'] ?? $siteName }}. Semua hak dilindungi.</p>
            <p class="text-stone-500">Harga dan ketersediaan dapat berubah sewaktu-waktu.</p>
        </div>
    </div>
</footer>
