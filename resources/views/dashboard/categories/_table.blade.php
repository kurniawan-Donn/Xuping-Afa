<div class="overflow-x-auto">
    <table class="table-base min-w-[560px]">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th class="text-center">Produk</th>
                <th class="text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>
                        <p class="font-medium">{{ $category->name }}</p>
                        <p class="text-xs text-stone-400">/{{ $category->slug }}</p>
                    </td>
                    <td class="max-w-xs text-stone-500"><span class="line-clamp-2">{{ $category->description ?: '—' }}</span></td>
                    <td class="text-center">
                        <a href="{{ route('products.index', ['category_id' => $category->id]) }}" class="badge badge-gray hover:bg-stone-200">{{ $category->products_count }}</a>
                    </td>
                    <td>
                        <div class="flex items-center justify-end gap-1">
                            <button type="button" @click="$dispatch('edit-category', @js(['id' => $category->id, 'name' => $category->name, 'description' => $category->description ?? '']))"
                                class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-stone-100 hover:text-ink" title="Edit"><x-icon name="edit" class="h-4 w-4" /></button>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" data-confirm="Kategori &quot;{{ $category->name }}&quot; akan dihapus.">
                                @csrf @method('DELETE')
                                <button type="submit" @disabled($category->products_count > 0) class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-red-50 hover:text-red-600 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-transparent disabled:hover:text-stone-400"
                                    title="{{ $category->products_count > 0 ? 'Masih ada produk di kategori ini' : 'Hapus' }}"><x-icon name="trash" class="h-4 w-4" /></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-16 text-center text-stone-500">Belum ada kategori.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if ($categories->hasPages())
    <div class="border-t border-stone-100 px-4 py-3">{{ $categories->links() }}</div>
@endif
