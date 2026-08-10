@extends('layouts.dashboard')
@section('title', 'Edit Pengguna - Admin Afa Xuping')
@section('header_title', 'Edit Pengguna')

@section('content')
<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('users.index') }}" class="p-2 text-gray-400 hover:text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h2 class="text-xl font-bold text-gray-800">Edit Pengguna</h2>
        <p class="text-sm text-gray-500">Ubah informasi dan pengaturan akses pengguna</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
        @csrf
        @method('PUT')
        
        <div class="space-y-8">
            <!-- Informasi Akun -->
            <div>
                <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Informasi Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="col-span-1 md:col-span-2">
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1.5">Foto Profil</label>
                        <div class="flex items-center gap-4">
                            @if($user->avatar)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border border-gray-200">
                            @endif
                            <input type="file" id="avatar" name="avatar" accept="image/*" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Biarkan kosong jika tidak ingin mengubah foto profil.</p>
                        @error('avatar') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                        @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>


            <!-- Pengaturan Peran & Status -->
            <div>
                <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Peran & Status Akses</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1.5">Peran Akses <span class="text-red-500">*</span></label>
                        <select id="role" name="role" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors cursor-pointer appearance-none" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <option value="" disabled>Pilih peran pengguna</option>
                            @php
                                $userRole = $user->roles->first()?->name;
                            @endphp
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $userRole) == $role->name ? 'selected' : '' }} class="capitalize">
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @if($user->id === auth()->id())
                            <input type="hidden" name="role" value="{{ $userRole }}">
                            <p class="text-xs text-gray-500 mt-2">Anda tidak dapat mengubah peran Anda sendiri.</p>
                        @endif
                        @error('role') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Status Pengguna</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            @if($user->id === auth()->id())
                                <input type="checkbox" checked disabled class="sr-only peer">
                                <input type="hidden" name="is_active" value="1">
                            @else
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            @endif
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 {{ $user->id === auth()->id() ? 'opacity-50 cursor-not-allowed' : '' }}"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Aktif</span>
                        </label>
                        @if($user->id === auth()->id())
                            <p class="text-xs text-gray-500 mt-2">Anda tidak dapat menonaktifkan akun Anda sendiri.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('users.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg border border-gray-200 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
