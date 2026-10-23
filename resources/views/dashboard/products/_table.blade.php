<div class="overflow-x-auto">
    <table class="table-base min-w-[760px]">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th class="w-36">Stok</th>
                <th>Status</th>
                <th class="text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->image_url }}" alt="" class="h-12 w-12 shrink-0 rounded-xl object-cover">
                            <div class="min-w-0">
                                <a href="{{ route('products.edit', $product) }}" class="line-clamp-1 font-medium hover:text-gold-700">{{ $product->name }}</a>
                                <div class="mt-0.5 flex flex-wrap items-center gap-1.5 text-xs text-stone-500">
                                    <span>{{ $product->category?->name ?? 'Tanpa kategori' }}</span>
                                    @if ($product->badge_label)<span class="badge badge-gold">{{ $product->badge_label }}</span>@endif
                                    @if ($product->is_featured)<span class="badge badge-dark">Unggulan</span>@endif
                                    @if ($product->has_variants)<span class="text-stone-400">&middot; {{ count($product->sizes ?? []) + count($product->colors ?? []) }} varian</span>@endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap">
                        <p class="font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @if ($product->has_discount)
                            <p class="text-xs text-stone-400 line-through">Rp {{ number_format($product->compare_price, 0, ',', '.') }}</p>
                        @endif
                    </td>
                    <td>
                        <div x-data="{ stock: {{ $product->stock }}, saved: {{ $product->stock }}, saving: false,
                                save() {
                                    if (this.stock === this.saved || this.stock === '' || this.stock < 0) { this.stock = this.saved; return; }
                                    this.saving = true;
                                    fetch('{{ route('products.stock', $product) }}', {
                                        method: 'PATCH',
                                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                                        body: JSON.stringify({ stock: this.stock }),
                                    }).then(r => r.ok ? r.json() : Promise.reject())
                                      .then(d => { this.saved = this.stock = d.stock; Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Stok disimpan', timer: 1500, showConfirmButton: false }); })
                                      .catch(() => { this.stock = this.saved; Swal.fire({ icon: 'error', title: 'Gagal menyimpan stok' }); })
                                      .finally(() => this.saving = false);
                                } }" class="flex items-center gap-2">
                            <input type="number" min="0" x-model.number="stock" @keydown.enter.prevent="$el.blur()" @blur="save()"
                                :class="stock <= 0 ? 'border-red-300 bg-red-50 text-red-700' : (stock <= 5 ? 'border-amber-300 bg-amber-50' : '')"
                                class="input w-20 py-1.5 text-center tabular-nums" aria-label="Stok {{ $product->name }}">
                            <span x-show="saving" class="h-4 w-4 animate-spin rounded-full border-2 border-stone-200 border-t-stone-600"></span>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $product->is_active ? 'badge-green' : 'badge-gray' }}">{{ $product->is_active ? 'Aktif' : 'Draf' }}</span>
                    </td>
                    <td>
                        <div class="flex items-center justify-end gap-1">
                            @if ($product->is_active)
                                <a href="{{ route('website.products.show', $product) }}" target="_blank" class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-stone-100 hover:text-ink" title="Lihat di website"><x-icon name="external" class="h-4 w-4" /></a>
                            @endif
                            <a href="{{ route('products.show', $product) }}" class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-stone-100 hover:text-ink" title="Detail"><x-icon name="eye" class="h-4 w-4" /></a>
                            <a href="{{ route('products.edit', $product) }}" class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-stone-100 hover:text-ink" title="Edit"><x-icon name="edit" class="h-4 w-4" /></a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" data-confirm="Produk &quot;{{ $product->name }}&quot; dan semua fotonya akan dihapus permanen.">
                                @csrf @method('DELETE')
                                <button type="submit" class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-red-50 hover:text-red-600" title="Hapus"><x-icon name="trash" class="h-4 w-4" /></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <p class="font-medium">Tidak ada produk yang cocok.</p>
                        <p class="mt-1 text-sm text-stone-500">Ubah filter atau <a href="{{ route('products.create') }}" class="font-semibold text-gold-700">tambah produk baru</a>.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if ($products->hasPages())
    <div class="border-t border-stone-100 px-4 py-3">{{ $products->links() }}</div>
@endif
