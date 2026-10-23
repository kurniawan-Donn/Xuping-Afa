@extends('layouts.dashboard')
@section('title', ($banner->exists ? 'Edit' : 'Tambah') . ' Banner — Admin Afa Xuping')
@section('header_title', 'Banner')

@section('content')
<x-dash.header :title="$banner->exists ? 'Edit banner' : 'Banner baru'" subtitle="Gunakan foto lebar (disarankan 1920×820 px)." :back="route('banners.index')" />

<form action="{{ $banner->exists ? route('banners.update', $banner) : route('banners.store') }}" method="POST" enctype="multipart/form-data"
    x-data="{ title: @js(old('title', $banner->title ?? '')), subtitle: @js(old('subtitle', $banner->subtitle ?? '')) }"
    class="grid gap-6 lg:grid-cols-5">
    @csrf
    @if ($banner->exists) @method('PUT') @endif

    <div class="space-y-6 lg:col-span-3">
        <section class="card p-5 lg:p-6" x-data="imagePreview(@js($banner->exists ? $banner->image_url : null))">
            <p class="label">Gambar banner @unless ($banner->exists)<span class="text-red-500">*</span>@endunless</p>
            <label class="group relative block aspect-[21/9] cursor-pointer overflow-hidden rounded-2xl border-2 border-dashed border-stone-300 bg-stone-100 transition hover:border-gold-400">
                <img x-show="url" :src="url" alt="" class="absolute inset-0 h-full w-full object-cover">
                <div x-show="url" class="absolute inset-0 bg-gradient-to-r from-ink/70 to-transparent"></div>
                <div x-show="url" class="absolute inset-y-0 left-0 flex max-w-[70%] flex-col justify-center p-5 sm:p-8">
                    <p class="font-display text-2xl font-semibold leading-tight text-white sm:text-4xl" x-text="title || 'Judul banner'"></p>
                    <p class="mt-1 text-xs text-stone-200 sm:text-sm" x-text="subtitle"></p>
                </div>
                <div x-show="!url" class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-stone-500">
                    <x-icon name="upload" class="h-7 w-7" />
                    <span class="text-sm font-medium">Pilih gambar</span>
                </div>
                <span x-show="url" class="absolute bottom-3 right-3 rounded-full bg-white/95 px-3 py-1.5 text-xs font-semibold opacity-0 shadow transition group-hover:opacity-100">Ganti gambar</span>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="pick($event)" @unless ($banner->exists) required @endunless>
            </label>
            <p class="hint mt-2">Pratinjau di atas mirip tampilan di website. JPG/PNG/WebP, maks. 5 MB.</p>
            @error('image')<p class="error">{{ $message }}</p>@enderror
        </section>
    </div>

    <div class="space-y-6 lg:col-span-2">
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
