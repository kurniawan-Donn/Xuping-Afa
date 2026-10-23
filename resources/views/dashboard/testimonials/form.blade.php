@extends('layouts.dashboard')
@section('title', ($testimonial->exists ? 'Edit' : 'Tambah') . ' Testimoni — Admin Afa Xuping')
@section('header_title', 'Testimoni')

@section('content')
<x-dash.header :title="$testimonial->exists ? 'Edit testimoni' : 'Testimoni baru'" :back="route('testimonials.index')" />

<form action="{{ $testimonial->exists ? route('testimonials.update', $testimonial) : route('testimonials.store') }}" method="POST" class="max-w-2xl"
    x-data="{ rating: {{ (int) old('rating', $testimonial->rating) }}, hover: 0 }">
    @csrf
    @if ($testimonial->exists) @method('PUT') @endif

    <section class="card space-y-4 p-5 lg:p-6">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="label">Nama pelanggan <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', $testimonial->name) }}" required maxlength="100" class="input" placeholder="Rina">
                @error('name')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="location" class="label">Kota</label>
                <input id="location" name="location" type="text" value="{{ old('location', $testimonial->location) }}" maxlength="100" class="input" placeholder="Denpasar">
            </div>
        </div>
        <div>
            <p class="label">Rating <span class="text-red-500">*</span></p>
            <input type="hidden" name="rating" :value="rating">
            <div class="flex gap-1" @mouseleave="hover = 0">
                @for ($s = 1; $s <= 5; $s++)
                    <button type="button" @click="rating = {{ $s }}" @mouseenter="hover = {{ $s }}" :class="(hover || rating) >= {{ $s }} ? 'text-gold-500' : 'text-stone-200'"
                        class="text-3xl leading-none transition" aria-label="{{ $s }} bintang">★</button>
                @endfor
            </div>
        </div>
        <div>
            <label for="content" class="label">Isi testimoni <span class="text-red-500">*</span></label>
            <textarea id="content" name="content" rows="5" required maxlength="1000" class="input" placeholder="Cincinnya cantik banget, sesuai foto dan pengiriman cepat!">{{ old('content', $testimonial->content) }}</textarea>
            @error('content')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="sort_order" class="label">Urutan</label>
                <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" class="input">
            </div>
            <label class="flex cursor-pointer flex-col">
                <span class="label">Tampilkan</span>
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $testimonial->is_active)) class="peer sr-only">
                <span class="relative mt-1.5 h-6 w-11 rounded-full bg-stone-200 transition after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:bg-ink peer-checked:after:translate-x-5"></span>
            </label>
        </div>
    </section>

    <div class="mt-6 flex gap-2">
        <a href="{{ route('testimonials.index') }}" class="btn btn-outline flex-1">Batal</a>
        <button type="submit" class="btn btn-dark flex-[2]">Simpan Testimoni</button>
    </div>
</form>
@endsection
