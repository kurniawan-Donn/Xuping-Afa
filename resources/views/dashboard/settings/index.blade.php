@extends('layouts.dashboard')
@section('title', 'Pengaturan Halaman Utama - Admin Afa Xuping')
@section('header_title', 'Pengaturan Halaman Utama')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800">Pengaturan Konten Website</h2>
    <p class="text-sm text-gray-500">Kelola teks dan gambar untuk halaman depan website Anda.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
        @csrf
        
        <div class="space-y-8">
            <!-- Hero Section -->
            <div>
                <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Bagian Header (Hero)</h3>
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label for="hero_title" class="block text-sm font-medium text-gray-700 mb-1.5">Judul Utama (Headline)</label>
                        <input type="text" id="hero_title" name="hero_title" value="{{ $settings['hero_title'] ?? '' }}" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                    </div>
                    
                    <div>
                        <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 mb-1.5">Sub Judul</label>
                        <textarea id="hero_subtitle" name="hero_subtitle" rows="2" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                    </div>

                    <div x-data="{ previewUrl: '{{ isset($settings['hero_image']) && $settings['hero_image'] ? Storage::url($settings['hero_image']) : '' }}' }">
                        <label for="hero_image" class="block text-sm font-medium text-gray-700 mb-1.5">Gambar Latar Header</label>
                        <div class="mb-3" x-show="previewUrl" style="display: none;">
                            <img :src="previewUrl" class="h-32 rounded-lg border border-gray-200 object-cover">
                        </div>
                        <input type="file" id="hero_image" name="hero_image" accept="image/*" 
                            @change="previewUrl = URL.createObjectURL($event.target.files[0])"
                            class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <div>
                <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Bagian Tentang Kami</h3>
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label for="about_title" class="block text-sm font-medium text-gray-700 mb-1.5">Judul Tentang Kami</label>
                        <input type="text" id="about_title" name="about_title" value="{{ $settings['about_title'] ?? '' }}" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                    </div>
                    
                    <div>
                        <label for="about_content" class="block text-sm font-medium text-gray-700 mb-1.5">Isi Tentang Kami</label>
                        <textarea id="about_content" name="about_content" rows="4" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">{{ $settings['about_content'] ?? '' }}</textarea>
                    </div>

                    <div x-data="{ previewUrl: '{{ isset($settings['about_image']) && $settings['about_image'] ? Storage::url($settings['about_image']) : '' }}' }">
                        <label for="about_image" class="block text-sm font-medium text-gray-700 mb-1.5">Gambar Tentang Kami</label>
                        <div class="mb-3" x-show="previewUrl" style="display: none;">
                            <img :src="previewUrl" class="h-32 rounded-lg border border-gray-200 object-cover">
                        </div>
                        <input type="file" id="about_image" name="about_image" accept="image/*" 
                            @change="previewUrl = URL.createObjectURL($event.target.files[0])"
                            class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>
            </div>

            <!-- Kontak & Footer Section -->
            <div>
                <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Bagian Kontak & Footer</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon/WA</label>
                        <input type="text" id="contact_phone" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                    </div>
                    
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email" id="contact_email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                    </div>
                    
                    <div class="col-span-1 md:col-span-2">
                        <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap</label>
                        <textarea id="contact_address" name="contact_address" rows="2" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">{{ $settings['contact_address'] ?? '' }}</textarea>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label for="footer_text" class="block text-sm font-medium text-gray-700 mb-1.5">Teks Footer (Hak Cipta)</label>
                        <input type="text" id="footer_text" name="footer_text" value="{{ $settings['footer_text'] ?? '' }}" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm shadow-indigo-600/20">
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
