@extends('layouts.dashboard')
@section('title', 'Detail Produk - Admin Afa Xuping')
@section('header_title', 'Detail Produk')

@section('content')
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('products.index') }}" class="p-2 text-gray-400 hover:text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Detail Produk</h2>
            <p class="text-sm text-gray-500">{{ $product->slug }}</p>
        </div>
    </div>
    
    <div class="flex items-center gap-3">
        <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit
        </a>
        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Gambar Produk -->
    <div class="lg:col-span-1 space-y-4" x-data="showImageManager({{ $product->images->toJson() }}, '{{ asset('storage') }}', '{{ csrf_token() }}')">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="aspect-square bg-gray-50 relative cursor-pointer group" @click="openLightbox(0)">
                <template x-if="images.length > 0">
                    <img :src="storageUrl + '/' + (images.find(i => i.is_primary)?.image_path || images[0]?.image_path)" class="w-full h-full object-cover">
                </template>
                <template x-if="images.length === 0">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&background=e2e8f0&color=64748b" class="w-full h-full object-cover">
                </template>
                <div x-show="images.length > 0" style="display: none;" class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                </div>
            </div>
            
            <template x-if="images.length > 1">
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    <div class="grid grid-cols-4 gap-2">
                        <template x-for="(image, index) in images" :key="image.id">
                            <div @click="openLightbox(index)" class="aspect-square rounded-md border border-gray-200 overflow-hidden bg-white relative cursor-pointer group">
                                <img :src="storageUrl + '/' + image.image_path" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                                <template x-if="image.is_primary">
                                    <div class="absolute bottom-0 left-0 right-0 bg-indigo-600/80 backdrop-blur text-white text-[9px] text-center py-0.5 font-medium uppercase tracking-wider">Utama</div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Lightbox Modal -->
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
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-4">Informasi Sistem</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-400 mb-1">Dibuat Oleh</p>
                    <p class="text-sm font-medium text-gray-900">{{ $product->creator->name ?? 'Admin' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Dibuat Pada</p>
                    <p class="text-sm font-medium text-gray-900">{{ $product->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Terakhir Diperbarui</p>
                    <p class="text-sm font-medium text-gray-900">{{ $product->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Produk -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                            {{ $product->category->name ?? 'Tanpa Kategori' }}
                        </span>
                        @if($product->is_active)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-sm text-gray-500 mb-1">Harga Produk</p>
                    <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Deskripsi Produk</h3>
                @if($product->description)
                <div class="prose prose-sm max-w-none text-gray-600 bg-gray-50 rounded-xl p-5 border border-gray-100">
                    {!! nl2br(e($product->description)) !!}
                </div>
                @else
                <p class="text-gray-400 italic text-sm">Tidak ada deskripsi yang ditambahkan untuk produk ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('showImageManager', (initialImages, storageUrl, csrf) => {
        // Sort initially so primary is always first
        initialImages.sort((a, b) => {
            if (a.is_primary && !b.is_primary) return -1;
            if (!a.is_primary && b.is_primary) return 1;
            return a.id - b.id;
        });

        return {
            images: initialImages,
            storageUrl: storageUrl,
            csrf: csrf,
            lightboxOpen: false,
            currentIndex: 0,
            
            openLightbox(index) {
                if(this.images.length === 0) return;
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
                        headers: { 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json' }
                    });
                    if(res.ok) {
                        this.images = this.images.map((imgItem) => {
                            imgItem.is_primary = (imgItem.id === img.id) ? 1 : 0;
                            return imgItem;
                        });
                        
                        // Resort to keep primary at the left
                        this.images.sort((a, b) => {
                            if (a.is_primary && !b.is_primary) return -1;
                            if (!a.is_primary && b.is_primary) return 1;
                            return a.id - b.id;
                        });
                        
                        // Update currentIndex so the lightbox stays on the same image
                        this.currentIndex = this.images.findIndex(i => i.id === img.id);
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
                        headers: { 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json' }
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
        };
    });
});
</script>
@endsection
