@props(['product'])
@php
    $url = route('website.products.show', $product);
    $quickAdd = $product->in_stock && ! $product->has_variants;
@endphp
<article class="group relative flex flex-col">
    <a href="{{ $url }}" class="relative block aspect-[4/5] overflow-hidden rounded-2xl bg-cream">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" decoding="async"
            class="h-full w-full object-cover transition duration-700 group-hover:scale-105 {{ $product->in_stock ? '' : 'opacity-60 grayscale-[40%]' }}">

        <div class="absolute left-3 top-3 flex flex-col items-start gap-1.5">
            @if (! $product->in_stock)
                <span class="badge bg-white/95 text-stone-700 shadow-sm">Habis</span>
            @elseif ($product->has_discount)
                <span class="badge bg-red-600 text-white">-{{ $product->discount_percent }}%</span>
            @endif
            @if ($product->badge_label)
                <span class="badge bg-ink/90 text-gold-200 backdrop-blur">{{ $product->badge_label }}</span>
            @endif
        </div>
    </a>

    @if ($quickAdd)
        <button type="button" @click="$store.cart.add(@js($product->cartPayload()))"
            class="absolute right-3 top-3 z-10 grid h-9 w-9 place-items-center rounded-full bg-white/95 text-ink shadow-soft transition hover:bg-ink hover:text-white lg:translate-y-1 lg:opacity-0 lg:group-hover:translate-y-0 lg:group-hover:opacity-100"
            aria-label="Tambah {{ $product->name }} ke pesanan">
            <x-icon name="plus" class="h-4.5 w-4.5" stroke="2" />
        </button>
    @endif

    <div class="flex flex-1 flex-col px-0.5 pt-3">
        @if ($product->category)
            <p class="text-[11px] font-medium uppercase tracking-wider text-stone-400">{{ $product->category->name }}</p>
        @endif
        <h3 class="mt-1 line-clamp-2 text-sm font-medium leading-snug text-ink sm:text-[15px]">
            <a href="{{ $url }}" class="after:absolute after:inset-0 after:content-[''] group-hover:text-gold-700">{{ $product->name }}</a>
        </h3>
        <div class="mt-auto flex flex-wrap items-baseline gap-x-2 pt-1.5">
            <span class="font-semibold text-ink">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            @if ($product->has_discount)
                <span class="text-xs text-stone-400 line-through">Rp {{ number_format($product->compare_price, 0, ',', '.') }}</span>
            @endif
        </div>
        @if ($product->colors)
            <div class="mt-2 flex items-center gap-1">
                @foreach ($product->colors as $color)
                    <span title="{{ $color }}" class="h-3 w-3 rounded-full ring-1 ring-stone-300 ring-offset-1" style="background: {{ \App\Models\Product::COLORS[$color] ?? '#ddd' }}"></span>
                @endforeach
            </div>
        @endif
    </div>
</article>
