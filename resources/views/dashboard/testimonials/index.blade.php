@extends('layouts.dashboard')
@section('title', 'Testimoni — Admin Afa Xuping')
@section('header_title', 'Testimoni')

@section('content')
<x-dash.header title="Testimoni" subtitle="Ulasan pelanggan yang tampil di beranda dan halaman Tentang.">
    <a href="{{ route('testimonials.create') }}" class="btn btn-dark"><x-icon name="plus" class="h-4 w-4" /> Tambah Testimoni</a>
</x-dash.header>

@if ($testimonials->isEmpty())
    <div class="card flex flex-col items-center px-6 py-16 text-center">
        <span class="grid h-14 w-14 place-items-center rounded-full bg-gold-50 text-gold-600"><x-icon name="quote" class="h-6 w-6" /></span>
        <p class="mt-4 font-semibold">Belum ada testimoni</p>
        <p class="mt-1 max-w-sm text-sm text-stone-500">Salin ulasan pelanggan dari chat WhatsApp (dengan izin mereka) untuk menambah kepercayaan calon pembeli.</p>
        <a href="{{ route('testimonials.create') }}" class="btn btn-dark mt-5">Tambah testimoni</a>
    </div>
@else
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($testimonials as $t)
            <div class="card flex flex-col p-5 {{ $t->is_active ? '' : 'opacity-60' }}">
                <div class="flex items-center justify-between">
                    <span class="text-sm tracking-wider text-gold-500" aria-label="Rating {{ $t->rating }}">{{ str_repeat('★', $t->rating) }}<span class="text-stone-200">{{ str_repeat('★', 5 - $t->rating) }}</span></span>
                    <span class="badge {{ $t->is_active ? 'badge-green' : 'badge-gray' }}">{{ $t->is_active ? 'Tampil' : 'Disembunyikan' }}</span>
                </div>
                <p class="mt-3 line-clamp-4 flex-1 text-sm leading-relaxed text-stone-600">&ldquo;{{ $t->content }}&rdquo;</p>
                <div class="mt-4 flex items-center justify-between border-t border-stone-100 pt-3">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold">{{ $t->name }}</p>
                        <p class="truncate text-xs text-stone-500">{{ $t->location ?: '—' }}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('testimonials.edit', $t) }}" class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-stone-100 hover:text-ink" title="Edit"><x-icon name="edit" class="h-4 w-4" /></a>
                        <form action="{{ route('testimonials.destroy', $t) }}" method="POST" data-confirm="Testimoni dari {{ $t->name }} akan dihapus.">
                            @csrf @method('DELETE')
                            <button type="submit" class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-red-50 hover:text-red-600" title="Hapus"><x-icon name="trash" class="h-4 w-4" /></button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $testimonials->links() }}</div>
@endif
@endsection
