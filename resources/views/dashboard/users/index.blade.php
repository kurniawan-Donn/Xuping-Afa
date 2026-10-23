@extends('layouts.dashboard')
@section('title', 'Pengguna — Admin Afa Xuping')
@section('header_title', 'Pengguna')

@section('content')
<x-dash.header title="Pengguna" subtitle="Atur siapa saja yang bisa masuk ke panel admin.">
    <a href="{{ route('users.create') }}" class="btn btn-dark"><x-icon name="plus" class="h-4 w-4" /> Tambah Pengguna</a>
</x-dash.header>

<div x-data="liveFilter({
        search: @js(request('search', '')),
        role: @js(request('role', '')),
        status: @js(request('status', '')),
        per_page: @js((string) request('per_page', 10)),
    })">
    <div class="card mb-4 grid gap-3 p-3 sm:grid-cols-2 lg:grid-cols-[1fr_160px_160px_110px]">
        <label class="relative">
            <span class="sr-only">Cari pengguna</span>
            <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" />
            <input type="search" x-model.debounce.400ms="search" class="input pl-9" placeholder="Cari nama atau email…">
        </label>
        <select x-model="role" class="input" aria-label="Peran">
            <option value="">Semua peran</option>
            @hasrole('superadmin')<option value="superadmin">Superadmin</option>@endhasrole
            <option value="owner">Owner</option>
            <option value="admin">Admin</option>
        </select>
        <select x-model="status" class="input" aria-label="Status">
            <option value="">Semua status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Nonaktif</option>
        </select>
        <select x-model="per_page" class="input" aria-label="Per halaman">
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
            @include('dashboard.users._table')
        </div>
    </div>
</div>
@endsection
