@extends('layouts.dashboard')
@section('title', 'Edit Profil - Admin Afa Xuping')
@section('header_title', 'Edit Profil')

@section('content')
<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('profile.index') }}" class="p-2 text-gray-400 hover:text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h2 class="text-xl font-bold text-gray-800">Edit Profil</h2>
        <p class="text-sm text-gray-500">Ubah informasi akun dan kata sandi</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="xl:col-span-1">
                <h3 class="text-base font-semibold text-gray-800 mb-1">Foto Profil</h3>
                <p class="text-sm text-gray-500 mb-4">Pilih foto profil yang merepresentasikan Anda.</p>
                
                <div x-data="avatarPreview('{{ $user->avatar_url }}')" class="flex flex-col items-center">
                    <div class="relative w-40 h-40 rounded-2xl border-4 border-gray-50 shadow-sm overflow-hidden bg-gray-100 group mb-4">
                        <img :src="previewUrl" class="w-full h-full object-cover">
                        <label for="avatar" class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer backdrop-blur-sm">
                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-xs font-medium">Ubah Foto</span>
                        </label>
                    </div>
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="sr-only" @change="updatePreview">
                    <p class="text-xs text-gray-400 text-center">Format: JPG, PNG, WEBP (Maks: 2MB)</p>
                    @error('avatar') <span class="text-xs text-red-500 mt-2 block">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="xl:col-span-2 space-y-8">
                <!-- Informasi Akun -->
                <div>
                    <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Informasi Akun</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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

                <!-- Ubah Password -->
                <div>
                    <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Ubah Kata Sandi</h3>
                    <p class="text-xs text-gray-500 mb-4">Kosongkan bagian ini jika Anda tidak ingin mengubah kata sandi.</p>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" autocomplete="new-password" class="w-full md:w-2/3 px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                            @error('current_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi Baru</label>
                                <input type="password" id="password" name="password" autocomplete="new-password" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('profile.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg border border-gray-200 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('avatarPreview', (initialUrl) => ({
        previewUrl: initialUrl,
        updatePreview(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewUrl = URL.createObjectURL(file);
            }
        }
    }));
});
</script>
@endsection
