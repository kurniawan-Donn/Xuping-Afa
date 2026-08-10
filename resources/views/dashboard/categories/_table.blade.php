<div class="overflow-x-auto relative" :class="{ 'opacity-50 pointer-events-none': loading }">
    <!-- loading spinner -->
    <div x-show="loading" style="display: none;" class="absolute inset-0 z-10 flex items-center justify-center bg-white/50 backdrop-blur-sm">
        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
    </div>

    <table class="w-full text-left text-sm text-gray-600 whitespace-nowrap">
        <thead class="bg-gray-50/50 text-gray-700 font-medium border-b border-gray-100">
            <tr>
                <th class="px-6 py-4">Nama Kategori</th>
                <th class="px-6 py-4">Slug</th>
                <th class="px-6 py-4">Total Produk</th>
                <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($categories as $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-800">{{ $item->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $item->description ?: 'Tidak ada deskripsi' }}</p>
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $item->slug }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ $item->products_count }} Produk
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button @click="openDetailModal({{ json_encode($item) }})" class="p-1.5 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-md transition-colors" title="Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        <button @click="openEditModal({{ json_encode($item) }})" class="p-1.5 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>

                        <form action="{{ route('categories.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-red-600 bg-red-50 hover:bg-red-100 rounded-md transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-3 border border-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 font-medium">Belum ada kategori</p>
                        <p class="text-sm text-gray-400 mt-1">Silakan tambah kategori baru untuk mulai.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="text-sm text-gray-500">
        Menampilkan <span class="font-medium text-gray-900">{{ $categories->firstItem() ?? 0 }}</span> - <span class="font-medium text-gray-900">{{ $categories->lastItem() ?? 0 }}</span> dari total <span class="font-medium text-gray-900">{{ $categories->total() }}</span> kategori
    </div>
    @if($categories->hasPages())
    <div>
        {{ $categories->links() }}
    </div>
    @endif
</div>
