@extends('layouts.dashboard')
@section('title', 'Banner — Admin Afa Xuping')
@section('header_title', 'Banner')

@section('content')
<x-dash.header title="Banner beranda" subtitle="Gambar besar yang bergeser otomatis di bagian atas beranda.">
    <a href="{{ route('banners.create') }}" class="btn btn-dark"><x-icon name="plus" class="h-4 w-4" /> Tambah Banner</a>
</x-dash.header>

@if ($banners->isEmpty())
    <div class="card flex flex-col items-center px-6 py-16 text-center">
        <span class="grid h-14 w-14 place-items-center rounded-full bg-gold-50 text-gold-600"><x-icon name="image" class="h-6 w-6" /></span>
        <p class="mt-4 font-semibold">Belum ada banner</p>
        <p class="mt-1 max-w-sm text-sm text-stone-500">Tanpa banner, beranda menampilkan judul & gambar hero dari menu Pengaturan Website.</p>
        <a href="{{ route('banners.create') }}" class="btn btn-dark mt-5">Buat banner pertama</a>
    </div>
@else
    <div class="grid gap-4 md:grid-cols-2">
        @foreach ($banners as $banner)
            <div class="card overflow-hidden">
                <div class="relative aspect-[21/9] bg-stone-100">
                    <img src="{{ $banner->image_url }}" alt="" class="h-full w-full object-cover {{ $banner->is_active ? '' : 'opacity-50 grayscale' }}">
                    <div class="absolute inset-0 bg-gradient-to-r from-ink/70 to-transparent"></div>
                    <div class="absolute inset-y-0 left-0 flex max-w-[70%] flex-col justify-center p-5">
                        <p class="font-display text-2xl font-semibold leading-tight text-white">{{ $banner->title }}</p>
                        @if ($banner->subtitle)<p class="mt-1 line-clamp-2 text-xs text-stone-200">{{ $banner->subtitle }}</p>@endif
                    </div>
                    <span class="badge absolute right-3 top-3 {{ $banner->is_active ? 'badge-green' : 'badge-gray' }}">{{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    @if ($banner->mobile_image_url)
                        <img src="{{ $banner->mobile_image_url }}" alt="Gambar HP" title="Punya gambar khusus HP" class="absolute bottom-3 right-3 aspect-[4/5] w-12 rounded-lg border-2 border-white object-cover shadow">
                    @else
                        <span class="badge absolute bottom-3 right-3 bg-white/90 text-stone-600">Tanpa gambar HP</span>
                    @endif
                </div>
                <div class="flex items-center justify-between gap-3 px-4 py-3">
                    <p class="min-w-0 truncate text-xs text-stone-500">Urutan {{ $banner->sort_order }} &middot; {{ $banner->link_url ?: 'Ke katalog' }}</p>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('banners.edit', $banner) }}" class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-stone-100 hover:text-ink" title="Edit"><x-icon name="edit" class="h-4 w-4" /></a>
                        <form action="{{ route('banners.destroy', $banner) }}" method="POST" data-confirm="Banner &quot;{{ $banner->title }}&quot; akan dihapus.">
                            @csrf @method('DELETE')
                            <button type="submit" class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-red-50 hover:text-red-600" title="Hapus"><x-icon name="trash" class="h-4 w-4" /></button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
