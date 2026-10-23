@php
    $existingImages = $product->exists
        ? $product->images->map(fn ($i) => ['id' => $i->id, 'url' => asset('storage/' . $i->image_path), 'is_primary' => (bool) $i->is_primary])->values()
        : collect();
    $selectedColors = old('colors', $product->colors ?? []);
    $sizesText = old('sizes', implode(', ', $product->sizes ?? []));
@endphp

<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        {{-- Informasi --}}
        <section class="card p-5 lg:p-6">
            <h2 class="font-semibold">Informasi produk</h2>
            <div class="mt-5 space-y-4">
                <div>
                    <label for="name" class="label">Nama produk <span class="text-red-500">*</span></label>
                    <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required maxlength="255" class="input" placeholder="Contoh: Cincin Xuping Permata Bunga">
                    @error('name')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="category_id" class="label">Kategori <span class="text-red-500">*</span></label>
                    <select id="category_id" name="category_id" required class="input">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @if ($categories->isEmpty())
                        <p class="hint">Belum ada kategori. <a href="{{ route('categories.index') }}" class="font-semibold text-gold-700">Buat kategori dulu</a>.</p>
                    @endif
                    @error('category_id')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="description" class="label">Deskripsi</label>
                    <textarea id="description" name="description" rows="6" class="input" placeholder="Bahan, ukuran detail, tips perawatan, cocok untuk acara apa…">{{ old('description', $product->description) }}</textarea>
                    <p class="hint">Baris baru akan tampil sebagai paragraf baru di website.</p>
                </div>
            </div>
        </section>

        {{-- Foto --}}
        <section class="card p-5 lg:p-6" x-data="imageManager(@js($existingImages))">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold">Foto produk</h2>
                <span class="text-xs text-stone-500">Otomatis dikompres ke WebP</span>
            </div>

            <template x-for="id in deleted" :key="'d' + id"><input type="hidden" name="deleted_image_ids[]" :value="id"></template>
            <input type="hidden" name="primary_image_id" :value="primaryId ?? ''">

            <div class="mt-5 grid grid-cols-3 gap-3 sm:grid-cols-4">
                <template x-for="img in existing" :key="img.id">
                    <div class="group relative aspect-square overflow-hidden rounded-xl border-2 bg-stone-100 transition"
                        :class="deleted.includes(img.id) ? 'border-red-300 opacity-40' : (primaryId === img.id ? 'border-gold-500' : 'border-transparent')">
                        <img :src="img.url" class="h-full w-full object-cover" alt="">
                        <span x-show="primaryId === img.id && !deleted.includes(img.id)" class="badge badge-dark absolute left-1.5 top-1.5">Utama</span>
                        <div class="absolute inset-x-1.5 bottom-1.5 flex gap-1 opacity-100 transition lg:opacity-0 lg:group-hover:opacity-100">
                            <button type="button" x-show="!deleted.includes(img.id) && primaryId !== img.id" @click="primaryId = img.id" class="flex-1 rounded-lg bg-white/95 py-1 text-[11px] font-semibold shadow">Jadikan utama</button>
                            <button type="button" @click="toggleDelete(img.id)" class="grid h-7 w-7 place-items-center rounded-lg bg-white/95 shadow"
                                :class="deleted.includes(img.id) ? 'text-ink' : 'text-red-600'" :title="deleted.includes(img.id) ? 'Batal hapus' : 'Hapus'">
                                <x-icon name="trash" class="h-3.5 w-3.5" x-show="!deleted.includes(img.id)" />
                                <x-icon name="arrow-left" class="h-3.5 w-3.5" x-show="deleted.includes(img.id)" />
                            </button>
                        </div>
                    </div>
                </template>

                <template x-for="(p, idx) in previews" :key="p.url">
                    <div class="relative aspect-square overflow-hidden rounded-xl border-2 border-dashed border-gold-300 bg-stone-100">
                        <img :src="p.url" class="h-full w-full object-cover" alt="">
                        <span class="badge badge-gold absolute left-1.5 top-1.5">Baru</span>
                        <button type="button" @click="removePreview(idx)" class="absolute right-1.5 top-1.5 grid h-7 w-7 place-items-center rounded-lg bg-white/95 text-red-600 shadow" aria-label="Batalkan foto"><x-icon name="x" class="h-3.5 w-3.5" /></button>
                    </div>
                </template>

                <label @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; pick($event.dataTransfer.files)"
                    :class="dragging ? 'border-gold-500 bg-gold-50' : 'border-stone-300 hover:border-gold-400 hover:bg-gold-50/50'"
                    class="flex aspect-square cursor-pointer flex-col items-center justify-center gap-1.5 rounded-xl border-2 border-dashed text-center text-stone-500 transition">
                    <x-icon name="upload" class="h-6 w-6" />
                    <span class="px-2 text-xs font-medium">Tambah foto</span>
                    <input x-ref="input" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple class="sr-only" @change="pick($event.target.files)">
                </label>
            </div>
            <p class="hint mt-3">JPG, PNG, atau WebP, maks. 5 MB per foto. Foto pertama/utama dipakai sebagai sampul.</p>
            @error('images.*')<p class="error">{{ $message }}</p>@enderror
        </section>

        {{-- Varian --}}
        <section class="card p-5 lg:p-6" x-data="{ sizes: @js($sizesText) }">
            <h2 class="font-semibold">Varian</h2>
            <p class="mt-1 text-sm text-stone-500">Harga sama untuk semua varian. Pembeli wajib memilih varian yang diisi.</p>

            <div class="mt-5 space-y-5">
                <div>
                    <p class="label">Pilihan warna</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach (\App\Models\Product::COLORS as $color => $hex)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="colors[]" value="{{ $color }}" class="peer sr-only" @checked(in_array($color, $selectedColors))>
                                <span class="option peer-checked:border-ink peer-checked:bg-ink peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-gold-500">
                                    <span class="h-4 w-4 rounded-full ring-1 ring-black/10" style="background: {{ $hex }}"></span> {{ $color }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <p class="hint">Kosongkan semua jika produk hanya satu warna.</p>
                </div>

                <div>
                    <label for="sizes" class="label">Pilihan ukuran</label>
                    <input id="sizes" name="sizes" type="text" x-model="sizes" class="input" placeholder="Contoh cincin: 6, 7, 8, 9 — Contoh kalung: 40 cm, 45 cm, 50 cm">
                    <div class="mt-2 flex flex-wrap gap-1.5" x-show="sizes.trim()">
                        <template x-for="s in sizes.split(/[,;\n]+/).map(s => s.trim()).filter(Boolean)" :key="s">
                            <span class="badge badge-gray px-3 py-1 text-xs" x-text="s"></span>
                        </template>
                    </div>
                    <p class="hint">Pisahkan dengan koma. Kosongkan jika tidak ada pilihan ukuran.</p>
                    @error('sizes')<p class="error">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>
    </div>

    <div class="space-y-6">
        {{-- Harga & stok --}}
        <section class="card p-5 lg:p-6">
            <h2 class="font-semibold">Harga & stok</h2>
            <div class="mt-5 space-y-4">
                <div>
                    <label for="price" class="label">Harga jual <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-stone-400">Rp</span>
                        <input id="price" name="price" type="number" min="0" step="1" inputmode="numeric" value="{{ old('price', $product->price !== null ? (int) $product->price : '') }}" required class="input pl-10">
                    </div>
                    @error('price')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="compare_price" class="label">Harga coret <span class="font-normal text-stone-400">(opsional)</span></label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-stone-400">Rp</span>
                        <input id="compare_price" name="compare_price" type="number" min="0" step="1" inputmode="numeric" value="{{ old('compare_price', $product->compare_price ? (int) $product->compare_price : '') }}" class="input pl-10">
                    </div>
                    <p class="hint">Isi harga sebelum diskon agar muncul label hemat %.</p>
                    @error('compare_price')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="stock" class="label">Jumlah stok <span class="text-red-500">*</span></label>
                    <input id="stock" name="stock" type="number" min="0" step="1" inputmode="numeric" value="{{ old('stock', $product->stock ?? 0) }}" required class="input">
                    <p class="hint">Website hanya menampilkan "Tersedia" atau "Habis" (stok 0).</p>
                    @error('stock')<p class="error">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        {{-- Tampilan --}}
        <section class="card p-5 lg:p-6">
            <h2 class="font-semibold">Tampilan di website</h2>
            <div class="mt-5 space-y-4">
                <label class="flex cursor-pointer items-start justify-between gap-4">
                    <span><span class="block text-sm font-medium">Aktif</span><span class="block text-xs text-stone-500">Tampilkan produk di website.</span></span>
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active)) class="peer sr-only">
                    <span class="relative h-6 w-11 shrink-0 rounded-full bg-stone-200 transition after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:bg-ink peer-checked:after:translate-x-5 peer-focus-visible:ring-2 peer-focus-visible:ring-gold-500"></span>
                </label>
                <label class="flex cursor-pointer items-start justify-between gap-4">
                    <span><span class="block text-sm font-medium">Unggulan</span><span class="block text-xs text-stone-500">Prioritaskan di beranda & katalog.</span></span>
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured)) class="peer sr-only">
                    <span class="relative h-6 w-11 shrink-0 rounded-full bg-stone-200 transition after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:bg-ink peer-checked:after:translate-x-5 peer-focus-visible:ring-2 peer-focus-visible:ring-gold-500"></span>
                </label>
                <div>
                    <label for="badge" class="label">Label</label>
                    <select id="badge" name="badge" class="input">
                        <option value="">Tanpa label</option>
                        @foreach (\App\Models\Product::BADGES as $value => $label)
                            <option value="{{ $value }}" @selected(old('badge', $product->badge) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="sort_order" class="label">Urutan</label>
                    <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $product->sort_order ?? 0) }}" class="input">
                    <p class="hint">Angka lebih kecil tampil lebih dulu.</p>
                </div>
            </div>
        </section>

        <div class="sticky bottom-4 flex gap-2 rounded-2xl border border-stone-200 bg-white/95 p-3 shadow-lift backdrop-blur lg:static lg:border-0 lg:bg-transparent lg:p-0 lg:shadow-none">
            <a href="{{ route('products.index') }}" class="btn btn-outline flex-1">Batal</a>
            <button type="submit" class="btn btn-dark flex-[2]">{{ $product->exists ? 'Simpan Perubahan' : 'Simpan Produk' }}</button>
        </div>
    </div>
</div>
