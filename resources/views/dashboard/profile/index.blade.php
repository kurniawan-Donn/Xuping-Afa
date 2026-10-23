@extends('layouts.dashboard')
@section('title', 'Profil — Admin Afa Xuping')
@section('header_title', 'Profil Saya')

@section('content')
<x-dash.header title="Profil saya">
    <a href="{{ route('profile.edit') }}" class="btn btn-dark"><x-icon name="edit" class="h-4 w-4" /> Edit Profil</a>
</x-dash.header>

<div class="card max-w-3xl overflow-hidden">
    <div class="h-28 bg-gradient-to-r from-ink via-stone-800 to-gold-700"></div>
    <div class="px-6 pb-6">
        <img src="{{ $user->avatar_url }}" alt="" class="-mt-12 h-24 w-24 rounded-full border-4 border-white object-cover shadow-soft">
        <h2 class="mt-3 font-display text-3xl font-bold">{{ $user->name }}</h2>
        <p class="text-sm text-stone-500">{{ $user->email }}</p>
        <div class="mt-3 flex flex-wrap gap-2">
            @foreach ($user->getRoleNames() as $role)
                <span class="badge badge-gold capitalize">{{ $role }}</span>
            @endforeach
            <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-gray' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
        </div>
    </div>
    <dl class="grid border-t border-stone-100 sm:grid-cols-2">
        <div class="border-b border-stone-100 px-6 py-4 sm:border-r">
            <dt class="text-xs text-stone-500">Bergabung</dt>
            <dd class="mt-1 font-medium">{{ $user->created_at?->translatedFormat('d F Y') }}</dd>
        </div>
        <div class="border-b border-stone-100 px-6 py-4">
            <dt class="text-xs text-stone-500">Terakhir diperbarui</dt>
            <dd class="mt-1 font-medium">{{ $user->updated_at?->diffForHumans() }}</dd>
        </div>
    </dl>
</div>
@endsection
