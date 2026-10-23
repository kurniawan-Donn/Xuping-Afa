<div class="mb-5 flex items-center justify-between text-sm text-stone-500">
    <p>
        @if ($products->total())
            Menampilkan <b class="text-ink">{{ $products->firstItem() }}–{{ $products->lastItem() }}</b> dari {{ $products->total() }} produk
        @endif
    </p>
</div>

@if ($products->isEmpty())
    <div class="flex flex-col items-center rounded-3xl border border-dashed border-stone-300 bg-white/60 px-6 py-20 text-center">
        <span class="grid h-16 w-16 place-items-center rounded-full bg-gold-100 text-gold-600"><x-icon name="search" class="h-7 w-7" /></span>
        <p class="mt-5 font-display text-2xl font-semibold">Produk tidak ditemukan</p>
        <p class="mt-2 max-w-sm text-sm text-stone-500">Coba kata kunci lain atau hapus beberapa filter. Bisa juga tanyakan langsung ke admin kami.</p>
        <button type="button" @click="reset()" class="btn btn-outline mt-6">Hapus filter</button>
    </div>
@else
    <div class="grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 lg:gap-x-6 xl:grid-cols-4">
        @foreach ($products as $product)
            <x-website.product-card :product="$product" />
        @endforeach
    </div>

    <div class="mt-12">
        {{ $products->onEachSide(1)->links() }}
    </div>
@endif
