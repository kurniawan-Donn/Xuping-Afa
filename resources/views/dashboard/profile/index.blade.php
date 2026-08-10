@extends('layouts.dashboard')
@section('title', 'Profil Saya - Admin Afa Xuping')
@section('header_title', 'Profil Saya')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Profil Pengguna</h2>
        <p class="text-sm text-gray-500">Informasi detail akun Anda</p>
    </div>
    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Edit Profil
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Header/Cover -->
    <div class="h-32 bg-linear-to-r from-indigo-500 to-purple-600"></div>

    <div class="px-6 sm:px-10 pb-10 relative">
        <!-- Avatar -->
        <div class="flex justify-between items-end -mt-16 mb-8" x-data="{ openImage: false }">
            <div class="relative">
                <div @click="openImage = true" class="w-32 h-32 rounded-2xl border-4 border-white shadow-md overflow-hidden bg-gray-50 cursor-pointer group">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </div>
                </div>

                <template x-teleport="body">
                    <div x-show="openImage" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-sm" @keydown.escape.window="openImage = false">
                        <button type="button" @click="openImage = false" class="absolute top-6 right-6 text-white/70 hover:text-white p-2 bg-white/10 hover:bg-white/20 rounded-full transition-colors z-[110]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="max-h-[85vh] max-w-[90vw] object-contain rounded-lg shadow-2xl" @click.outside="openImage = false">
                    </div>
                </template>
            </div>

            <div class="pb-2">
                @if($user->is_active)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aktif
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                    <span class="w-2 h-2 rounded-full bg-gray-400"></span> Nonaktif
                </span>
                @endif
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $user->name }}</h1>
                <p class="text-sm text-gray-500 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    {{ $user->email }}
                </p>
            </div>

            <div class="space-y-4 pt-2 md:pt-0">
                <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Peran Akses</p>
                    <p class="font-medium text-gray-800">
                        @if($user->roles->count() > 0)
                        {{ $user->roles->pluck('name')->implode(', ') }}
                        @else
                        Administrator
                        @endif
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Bergabung Sejak</p>
                    <p class="font-medium text-gray-800">{{ $user->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
