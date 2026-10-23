@extends('layouts.dashboard')
@section('title', 'Kategori — Admin Afa Xuping')
@section('header_title', 'Kategori')

@section('content')
<div x-data="{
        modal: false,
        form: { id: null, name: '', description: '' },
        storeUrl: @js(route('categories.store')),
        baseUrl: @js(url('dashboard/categories')),
        openCreate() { this.form = { id: null, name: '', description: '' }; this.modal = true; this.$nextTick(() => this.$refs.name.focus()); },
        openEdit(c) { this.form = { ...c }; this.modal = true; this.$nextTick(() => this.$refs.name.focus()); },
    }" @edit-category.window="openEdit($event.detail)" @keydown.escape.window="modal = false">

    <x-dash.header title="Kategori" subtitle="Kelompokkan produk agar mudah dicari pembeli.">
        <button type="button" @click="openCreate()" class="btn btn-dark"><x-icon name="plus" class="h-4 w-4" /> Tambah Kategori</button>
    </x-dash.header>

    <div x-data="liveFilter({ search: @js(request('search', '')), per_page: @js((string) $perPage) })">
        <div class="card mb-4 flex gap-3 p-3">
            <label class="relative flex-1">
                <span class="sr-only">Cari kategori</span>
                <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" />
                <input type="search" x-model.debounce.400ms="search" class="input pl-9" placeholder="Cari kategori…">
            </label>
            <select x-model="per_page" class="input w-28" aria-label="Per halaman">
                @foreach ([10, 25, 50] as $n)
                    <option value="{{ $n }}">{{ $n }} / hal</option>
                @endforeach
            </select>
        </div>

        <div class="card relative overflow-hidden">
            <div x-cloak x-show="loading" class="absolute inset-0 z-10 grid place-items-center bg-white/60">
                <span class="h-7 w-7 animate-spin rounded-full border-2 border-gold-200 border-t-gold-600"></span>
            </div>
            <div x-ref="results">
                @include('dashboard.categories._table')
            </div>
        </div>
    </div>

    {{-- Modal tambah / edit --}}
    <div x-cloak x-show="modal" class="fixed inset-0 z-50 flex items-end justify-center p-0 sm:items-center sm:p-4" role="dialog" aria-modal="true">
        <div x-show="modal" x-transition.opacity class="absolute inset-0 bg-ink/50 backdrop-blur-sm" @click="modal = false"></div>
        <form x-show="modal" x-transition method="POST" :action="form.id ? baseUrl + '/' + form.id : storeUrl"
            class="relative w-full max-w-lg rounded-t-3xl bg-white p-6 shadow-lift sm:rounded-3xl">
            @csrf
            <template x-if="form.id"><input type="hidden" name="_method" value="PUT"></template>

            <div class="flex items-center justify-between">
                <h2 class="font-display text-2xl font-bold" x-text="form.id ? 'Edit kategori' : 'Kategori baru'"></h2>
                <button type="button" @click="modal = false" class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-stone-100" aria-label="Tutup"><x-icon name="x" /></button>
            </div>
            <div class="mt-5 space-y-4">
                <div>
                    <label for="cat-name" class="label">Nama kategori <span class="text-red-500">*</span></label>
                    <input id="cat-name" x-ref="name" type="text" name="name" x-model="form.name" required maxlength="255" class="input" placeholder="Contoh: Cincin">
                </div>
                <div>
                    <label for="cat-desc" class="label">Deskripsi <span class="font-normal text-stone-400">(opsional)</span></label>
                    <textarea id="cat-desc" name="description" x-model="form.description" rows="3" class="input" placeholder="Muncul sebagai deskripsi halaman kategori di Google."></textarea>
                </div>
            </div>
            <div class="mt-6 flex gap-2">
                <button type="button" @click="modal = false" class="btn btn-outline flex-1">Batal</button>
                <button type="submit" class="btn btn-dark flex-[2]">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
