@extends('layouts.dashboard')
@section('title', ($banner->exists ? 'Edit' : 'Tambah') . ' Banner — Admin Afa Xuping')
@section('header_title', 'Banner')

@section('content')
<x-dash.header :title="$banner->exists ? 'Edit banner' : 'Banner baru'" subtitle="Siapkan gambar lebar untuk desktop, dan bila perlu gambar potret khusus HP." :back="route('banners.index')" />

<form action="{{ $banner->exists ? route('banners.update', $banner) : route('banners.store') }}" method="POST" enctype="multipart/form-data"
    x-data="{
        title: @js(old('title', $banner->title ?? '')),
        subtitle: @js(old('subtitle', $banner->subtitle ?? '')),
        hideMobile: @js((bool) old('hide_text_mobile', $banner->hide_text_mobile)),
        desktop: @js($banner->exists ? $banner->image_url : null),
        mobile: @js($banner->mobile_image_url),
        removeMobile: false,
        pick(e, key) { const f = e.target.files[0]; if (f) { this[key] = URL.createObjectURL(f); if (key === 'mobile') this.removeMobile = false; } },
        get mobileShown() { return this.removeMobile ? null : this.mobile; },
    }"
    class="grid gap-6 xl:grid-cols-5">
    @csrf
    @if ($banner->exists) @method('PUT') @endif
    <input type="hidden" name="remove_image_mobile" :value="removeMobile ? 1 : 0">

    <div class="space-y-6 xl:col-span-3">
        {{-- Gambar desktop --}}
        <section class="card p-5 lg:p-6">
            <div class="mb-3 flex items-center justify-between gap-3">
                <div>
                    <p class="font-semibold">Gambar desktop @unless ($banner->exists)<span class="text-red-500">*</span>@endunless</p>
                    <p class="text-xs text-stone-500">Lanskap, disarankan <b>1920×820 px</b> (21:9). Juga dipakai di tablet.</p>
                </div>
                <span class="badge badge-gray">Laptop & tablet</span>
            </div>
            <label class="group relative block aspect-[21/9] cursor-pointer overflow-hidden rounded-2xl border-2 border-dashed border-stone-300 bg-stone-100 transition hover:border-gold-400">
                <img x-show="desktop" :src="desktop" alt="" class="absolute inset-0 h-full w-full object-cover">
                <div x-show="desktop" class="absolute inset-0 bg-gradient-to-r from-ink/70 to-transparent"></div>
                <div x-show="desktop" class="absolute inset-y-0 left-0 flex max-w-[60%] flex-col justify-center p-5 sm:p-8">
                    <p class="font-display text-2xl font-semibold leading-tight text-white sm:text-4xl" x-text="title || 'Judul banner'"></p>
                    <p class="mt-1 text-xs text-stone-200 sm:text-sm" x-text="subtitle"></p>
                    <span class="mt-3 w-fit rounded-full bg-gold-500 px-3 py-1 text-[11px] font-semibold text-white">Lihat Koleksi →</span>
                </div>
                <div x-show="!desktop" class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-stone-500">
                    <x-icon name="upload" class="h-7 w-7" />
                    <span class="text-sm font-medium">Pilih gambar desktop</span>
                </div>
                <span x-show="desktop" class="absolute bottom-3 right-3 rounded-full bg-white/95 px-3 py-1.5 text-xs font-semibold opacity-0 shadow transition group-hover:opacity-100">Ganti gambar</span>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="pick($event, 'desktop')" @unless ($banner->exists) required @endunless>
            </label>
            @error('image')<p class="error">{{ $message }}</p>@enderror
        </section>

        {{-- Gambar HP --}}
        <section class="card p-5 lg:p-6">
            <div class="grid gap-6 sm:grid-cols-[220px_1fr] sm:items-start">
                <div class="mx-auto w-[220px]">
                    <div class="rounded-[2rem] bg-ink p-2 shadow-lift">
                        <label class="group relative block aspect-[4/5] cursor-pointer overflow-hidden rounded-[1.5rem] bg-stone-100">
                            <img x-show="mobileShown || desktop" :src="mobileShown || desktop" alt="" class="absolute inset-0 h-full w-full object-cover">
                            <div x-show="(mobileShown || desktop) && !hideMobile" class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/20 to-transparent"></div>
                            <div x-show="(mobileShown || desktop) && !hideMobile" class="absolute inset-x-0 bottom-0 p-4">
                                <p class="font-display text-xl font-semibold leading-tight text-white" x-text="title || 'Judul banner'"></p>
                                <p class="mt-1 text-[10px] text-stone-200" x-text="subtitle"></p>
                                <span class="mt-2 inline-block rounded-full bg-gold-500 px-2.5 py-1 text-[9px] font-semibold text-white">Lihat Koleksi →</span>
                            </div>
                            <div x-show="!mobileShown && !desktop" class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-center text-stone-500">
                                <x-icon name="upload" class="h-6 w-6" />
                                <span class="px-4 text-xs font-medium">Pilih gambar HP</span>
                            </div>
                            <span x-show="!mobileShown && desktop" class="absolute left-2 top-2 rounded-full bg-amber-400 px-2 py-0.5 text-[9px] font-bold text-ink">Dari gambar desktop</span>
                            <input x-ref="mobileInput" type="file" name="image_mobile" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="pick($event, 'mobile')">
                        </label>
                    </div>
                    <p class="mt-2 text-center text-[11px] text-stone-400">Pratinjau di HP</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="font-semibold">Gambar HP <span class="font-normal text-stone-400">(opsional)</span></p>
                        <p class="mt-1 text-sm text-stone-500">Potret, disarankan <b>1080×1350 px</b> (4:5). Klik bingkai ponsel untuk memilih gambar.</p>
                    </div>
                    <p class="rounded-xl bg-stone-50 p-3 text-xs leading-relaxed text-stone-600">
                        Bila dikosongkan, layar HP memakai gambar desktop yang dipotong bagian tengahnya (lihat label kuning pada pratinjau).
                        Pengunjung HP hanya mengunduh gambar HP, jadi halaman tetap ringan.
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="$refs.mobileInput.click()" class="btn btn-outline btn-sm">
                            <x-icon name="upload" class="h-4 w-4" /> <span x-text="mobileShown ? 'Ganti gambar HP' : 'Pilih gambar HP'"></span>
                        </button>
                        <button type="button" x-show="mobileShown" @click="removeMobile = true; $refs.mobileInput.value = ''" class="btn btn-ghost btn-sm text-red-600">
                            <x-icon name="trash" class="h-4 w-4" /> Hapus gambar HP
                        </button>
                    </div>
                    @error('image_mobile')<p class="error">{{ $message }}</p>@enderror

                    <label class="flex cursor-pointer items-start justify-between gap-4 rounded-xl border border-stone-200 p-4">
                        <span>
                            <span class="block text-sm font-medium">Sembunyikan teks di HP</span>
                            <span class="block text-xs text-stone-500">Nyalakan bila gambar HP sudah berisi tulisan sendiri. Seluruh banner tetap bisa diklik.</span>
                        </span>
                        <input type="checkbox" name="hide_text_mobile" value="1" x-model="hideMobile" class="peer sr-only">
                        <span class="relative h-6 w-11 shrink-0 rounded-full bg-stone-200 transition after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:bg-ink peer-checked:after:translate-x-5"></span>
                    </label>
                </div>
            </div>
        </section>
    </div>

    <div class="space-y-6 xl:col-span-2">
        <section class="card space-y-4 p-5 lg:p-6">
            <div>
                <label for="title" class="label">Judul <span class="text-red-500">*</span></label>
                <input id="title" name="title" type="text" x-model="title" required maxlength="255" class="input" placeholder="Koleksi Cincin Terbaru">
                @error('title')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="subtitle" class="label">Subjudul</label>
                <input id="subtitle" name="subtitle" type="text" x-model="subtitle" maxlength="255" class="input" placeholder="Diskon hingga 20% minggu ini">
            </div>
            <div>
                <label for="link_url" class="label">Link tombol</label>
                <input id="link_url" name="link_url" type="text" value="{{ old('link_url', $banner->link_url) }}" maxlength="255" class="input" placeholder="/products?category=cincin">
                <p class="hint">Kosongkan untuk mengarah ke katalog.</p>
                @error('link_url')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="sort_order" class="label">Urutan</label>
                    <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="input">
                </div>
                <label class="flex cursor-pointer flex-col">
                    <span class="label">Aktif</span>
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active)) class="peer sr-only">
                    <span class="relative mt-1.5 h-6 w-11 rounded-full bg-stone-200 transition after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:bg-ink peer-checked:after:translate-x-5"></span>
                </label>
            </div>
        </section>
        <div class="flex gap-2">
            <a href="{{ route('banners.index') }}" class="btn btn-outline flex-1">Batal</a>
            <button type="submit" class="btn btn-dark flex-[2]">Simpan Banner</button>
        </div>
    </div>
</form>
@endsection
