@extends('layouts.dashboard')
@section('title', 'Produk - Admin Afa Xuping')
@section('header_title', 'Kelola Produk')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Daftar Produk</h2>
        <p class="text-sm text-gray-500">Kelola katalog produk Anda</p>
    </div>
    <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Produk
    </a>
</div>

<div x-data="productTable()" x-init="initPagination()">
    <!-- Filters -->
    <div class="mb-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <label for="per_page" class="text-sm text-gray-600 whitespace-nowrap">Tampilkan</label>
            <select x-model="per_page" class="px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-600">data</span>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <select x-model="category_id" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 w-full sm:w-40">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            
            <select x-model="status" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 w-full sm:w-36">
                <option value="">Semua Status</option>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>

            <div class="w-full sm:w-64 relative">
                <input type="text" x-model.debounce.500ms="search" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-ref="tableContainer">
        @include('dashboard.products._table')
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productTable', () => ({
        search: '{{ request('search') }}',
        per_page: '{{ request('per_page', 10) }}',
        category_id: '{{ request('category_id') }}',
        status: '{{ request('status') }}',
        loading: false,

        init() {
            this.$watch('search', () => this.fetchData());
            this.$watch('per_page', () => this.fetchData());
            this.$watch('category_id', () => this.fetchData());
            this.$watch('status', () => this.fetchData());
        },

        fetchData(url = null) {
            this.loading = true;
            let targetUrl = url || '{{ route('products.index') }}';
            let params = new URLSearchParams({
                search: this.search,
                per_page: this.per_page,
                category_id: this.category_id,
                status: this.status
            });

            if (!url) {
                targetUrl += '?' + params.toString();
            }

            window.history.pushState({}, '', targetUrl);

            fetch(targetUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                this.$refs.tableContainer.innerHTML = html;
                this.loading = false;
                this.initPagination();
            });
        },

        initPagination() {
            const links = this.$refs.tableContainer.querySelectorAll('nav a');
            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.fetchData(link.href);
                });
            });
        }
    }));
});
</script>


@endsection
