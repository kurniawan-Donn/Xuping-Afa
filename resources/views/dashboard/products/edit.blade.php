@extends('layouts.dashboard')
@section('title', 'Edit Produk - Admin Afa Xuping')
@section('header_title', 'Edit Produk')

@section('content')
<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('products.index') }}" class="p-2 text-gray-400 hover:text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h2 class="text-xl font-bold text-gray-800">Edit Produk</h2>
        <p class="text-sm text-gray-500">Ubah detail produk: {{ $product->name }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Dasar -->
                <div class="space-y-4">
                    <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-2">Informasi Dasar</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors" placeholder="Contoh: Cincin Berlian Xuping">
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                            <select id="category_id" name="category_id" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors appearance-none">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" id="price" name="price" value="{{ old('price', (int)$product->price) }}" min="0" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors" placeholder="0">
                            @error('price') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                        <textarea id="description" name="description" rows="5" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors" placeholder="Deskripsi lengkap produk...">{{ old('description', $product->description) }}</textarea>
                        @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Media -->
                <div x-data="editImageManager({{ $product->images->toJson() }}, '{{ asset('storage') }}', '{{ csrf_token() }}')" class="space-y-4">
                    <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-2">Media & Status</h3>
                    
                    <template x-if="images.length > 0">
                        <div>
                            <p class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini (<span x-text="images.length"></span>)</p>
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <template x-for="(image, index) in images" :key="image.id">
                                    <div @click="openLightbox(index)" class="aspect-square rounded-lg border border-gray-200 overflow-hidden relative cursor-pointer group">
                                        <img :src="storageUrl + '/' + image.image_path" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                        </div>
                                        <template x-if="image.is_primary">
                                            <div class="absolute top-1 left-1 bg-indigo-600 text-white text-[10px] px-1.5 py-0.5 rounded font-medium shadow-sm uppercase tracking-wider">Utama</div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Lightbox Modal for Existing Images -->
                    <template x-teleport="body">
                        <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-100 flex items-center justify-center bg-black/90 backdrop-blur-sm" @keydown.escape.window="lightboxOpen = false">
                            <button type="button" @click="lightboxOpen = false" class="absolute top-6 right-6 text-white/70 hover:text-white p-2 bg-white/10 hover:bg-white/20 rounded-full transition-colors z-50">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>

                            <button type="button" x-show="images.length > 1" @click.stop="prevImage" class="absolute left-6 text-white/70 hover:text-white p-3 bg-white/10 hover:bg-white/20 rounded-full transition-colors z-50">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            
                            <button type="button" x-show="images.length > 1" @click.stop="nextImage" class="absolute right-6 text-white/70 hover:text-white p-3 bg-white/10 hover:bg-white/20 rounded-full transition-colors z-50">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>

                            <img :src="images[currentIndex] ? storageUrl + '/' + images[currentIndex].image_path : ''" class="max-h-[85vh] max-w-[90vw] object-contain rounded-lg shadow-2xl" @click.outside="lightboxOpen = false">
                            
                            <div class="absolute bottom-6 left-0 right-0 text-center text-white text-sm bg-black/60 py-4 backdrop-blur-md flex flex-col items-center gap-3">
                                <div>
                                    Foto <span x-text="currentIndex + 1"></span> dari <span x-text="images.length"></span>
                                    <template x-if="images[currentIndex]?.is_primary">
                                        <span class="ml-2 px-2 py-0.5 bg-indigo-600 text-white rounded text-xs font-medium">Utama</span>
                                    </template>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button type="button" @click="setPrimary()" x-show="!images[currentIndex]?.is_primary" class="text-xs bg-white text-gray-900 px-3 py-1.5 rounded hover:bg-gray-100 font-medium transition-colors">
                                        Jadikan Utama
                                    </button>
                                    <button type="button" @click="deleteImage()" class="text-xs bg-red-600 text-white px-3 py-1.5 rounded hover:bg-red-700 font-medium transition-colors">
                                        Hapus Foto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tambah Foto Baru</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors relative cursor-pointer" @click="$refs.newFiles.click()">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <span class="relative font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                        <span>Unggah foto tambahan</span>
                                        <input x-ref="newFiles" id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" @change="handleNewFiles">
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, WEBP hingga 2MB</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2" x-text="newFilesText"></p>
                        @error('images') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        @error('images.*') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100" x-data="{ isActive: {{ old('is_active', $product->is_active) ? 'true' : 'false' }} }">
                        <input type="hidden" name="is_active" :value="isActive ? '1' : '0'">
                        <button type="button" @click="isActive = !isActive" class="flex items-center gap-3 focus:outline-none w-fit">
                            <div class="relative w-11 h-6 rounded-full transition-colors duration-200 ease-in-out"
                                 :class="isActive ? 'bg-indigo-600' : 'bg-gray-200'">
                                <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 ease-in-out"
                                     :class="isActive ? 'translate-x-5' : 'translate-x-0'"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Aktifkan Produk</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="{{ route('products.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg border border-gray-200 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('editImageManager', (initialImages, storageUrl, csrf) => ({
        images: initialImages,
        storageUrl: storageUrl,
        csrf: csrf,
        lightboxOpen: false,
        currentIndex: 0,
        newFilesText: 'Belum ada file baru yang dipilih',
        
        handleNewFiles(e) {
            if (e.target.files && e.target.files.length > 0) {
                this.newFilesText = e.target.files.length + ' foto baru dipilih';
            } else {
                this.newFilesText = 'Belum ada file baru yang dipilih';
            }
        },
        openLightbox(index) {
            this.currentIndex = index;
            this.lightboxOpen = true;
        },
        prevImage() {
            if(this.images.length === 0) return;
            this.currentIndex = this.currentIndex === 0 ? this.images.length - 1 : this.currentIndex - 1;
        },
        nextImage() {
            if(this.images.length === 0) return;
            this.currentIndex = this.currentIndex === this.images.length - 1 ? 0 : this.currentIndex + 1;
        },
        async setPrimary() {
            if(this.images.length === 0) return;
            const img = this.images[this.currentIndex];
            try {
                const res = await fetch(`/dashboard/product-images/${img.id}/set-primary`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json'
                    }
                });
                if(res.ok) {
                    this.images = this.images.map((imgItem, i) => {
                        imgItem.is_primary = (i === this.currentIndex) ? 1 : 0;
                        return imgItem;
                    });
                }
            } catch(e) { console.error(e); }
        },
        async deleteImage() {
            if(this.images.length === 0) return;
            if(!confirm('Apakah Anda yakin ingin menghapus foto ini?')) return;
            const img = this.images[this.currentIndex];
            try {
                const res = await fetch(`/dashboard/product-images/${img.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json'
                    }
                });
                if(res.ok) {
                    this.images.splice(this.currentIndex, 1);
                    if(this.images.length === 0) {
                        this.lightboxOpen = false;
                    } else {
                        if(img.is_primary && this.images.length > 0) {
                            this.images[0].is_primary = 1;
                        }
                        if(this.currentIndex >= this.images.length) {
                            this.currentIndex = this.images.length - 1;
                        }
                    }
                }
            } catch(e) { console.error(e); }
        }
    }));
});
</script>
@endsection
