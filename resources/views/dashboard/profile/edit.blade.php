@extends('layouts.dashboard')
@section('title', 'Edit Profil — Admin Afa Xuping')
@section('header_title', 'Profil Saya')

@section('content')
<x-dash.header title="Edit profil" :back="route('profile.index')" />

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="grid max-w-5xl gap-6 lg:grid-cols-3">
    @csrf
    @method('PUT')

    <section class="card p-5 lg:p-6" x-data="imagePreview(@js($user->avatar_url))">
        <p class="label">Foto profil</p>
        <label class="group relative mx-auto block h-32 w-32 cursor-pointer overflow-hidden rounded-full bg-stone-100">
            <img :src="url" alt="" class="h-full w-full object-cover">
            <span class="absolute inset-x-0 bottom-0 bg-ink/60 py-1 text-center text-[11px] font-medium text-white opacity-0 transition group-hover:opacity-100">Ganti</span>
            <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="pick($event)">
        </label>
        @error('avatar')<p class="error text-center">{{ $message }}</p>@enderror
    </section>

    <div class="space-y-6 lg:col-span-2">
        <section class="card space-y-4 p-5 lg:p-6">
            <h2 class="font-semibold">Data akun</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="label">Nama</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required maxlength="255" class="input" autocomplete="name">
                    @error('name')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="label">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required maxlength="255" class="input" autocomplete="email">
                    @error('email')<p class="error">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <section class="card space-y-4 p-5 lg:p-6">
            <div>
                <h2 class="font-semibold">Ganti kata sandi</h2>
                <p class="text-sm text-stone-500">Kosongkan jika tidak ingin mengganti.</p>
            </div>
            <div>
                <label for="current_password" class="label">Kata sandi saat ini</label>
                <input id="current_password" name="current_password" type="password" class="input" autocomplete="off">
                @error('current_password')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="label">Kata sandi baru</label>
                    <input id="password" name="password" type="password" minlength="8" class="input" autocomplete="new-password">
                    @error('password')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="label">Ulangi kata sandi baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" class="input" autocomplete="new-password">
                </div>
            </div>
        </section>

        <div class="flex gap-2">
            <a href="{{ route('profile.index') }}" class="btn btn-outline flex-1">Batal</a>
            <button type="submit" class="btn btn-dark flex-[2]">Simpan Profil</button>
        </div>
    </div>
</form>
@endsection
