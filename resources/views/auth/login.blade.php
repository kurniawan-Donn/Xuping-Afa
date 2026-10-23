@extends('layouts.guest')
@section('title', 'Masuk — Admin Afa Xuping')

@section('content')
<div class="w-full max-w-sm">
    <div class="mb-8 text-center lg:text-left">
        <span class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-ink font-display text-xl font-bold text-gold-300 lg:hidden">A</span>
        <h1 class="mt-4 font-display text-4xl font-bold lg:mt-0">Selamat datang</h1>
        <p class="mt-1 text-sm text-stone-500">Masuk ke panel admin Afa Xuping.</p>
    </div>

    <form method="POST" action="{{ route('login.store') }}" class="space-y-4" x-data="{ show: false, busy: false }" @submit="busy = true">
        @csrf
        <div>
            <label for="email" class="label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="input py-3" placeholder="nama@email.com">
        </div>
        <div>
            <label for="password" class="label">Kata sandi</label>
            <div class="relative">
                <input id="password" :type="show ? 'text' : 'password'" type="password" name="password" required autocomplete="current-password" class="input py-3 pr-12">
                <button type="button" @click="show = !show" class="absolute right-2 top-1/2 grid h-8 w-8 -translate-y-1/2 place-items-center rounded-lg text-stone-400 hover:text-ink" :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                    <x-icon name="eye" class="h-4.5 w-4.5" />
                </button>
            </div>
        </div>
        <label class="flex items-center gap-2 text-sm text-stone-600">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-stone-300 text-ink focus:ring-gold-500">
            Ingat saya
        </label>
        <button type="submit" :disabled="busy" class="btn btn-dark btn-lg w-full">
            <span x-show="!busy">Masuk</span>
            <span x-show="busy" x-cloak class="h-5 w-5 animate-spin rounded-full border-2 border-white/30 border-t-white"></span>
        </button>
    </form>

    <a href="{{ route('website.index') }}" class="mt-8 flex items-center justify-center gap-1.5 text-sm text-stone-500 hover:text-ink lg:justify-start">
        <x-icon name="arrow-left" class="h-4 w-4" /> Kembali ke website
    </a>
</div>
@endsection
