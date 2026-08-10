@extends('layouts.dashboard')
@section('title', 'Manajemen Pengguna - Admin Afa Xuping')
@section('header_title', 'Manajemen Pengguna')

@section('content')
<div x-data="userManager()" class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Daftar Pengguna</h2>
            <p class="text-sm text-gray-500">Kelola akses dan data pengguna sistem</p>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Pengguna
        </a>
    </div>

    <!-- Filter & Search Section -->
    <div class="mb-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <label for="per_page" class="text-sm text-gray-600 whitespace-nowrap">Tampilkan</label>
            <select x-model="perPage" class="px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-600">data</span>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <select x-model="role" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 w-full sm:w-36">
                <option value="">Semua Peran</option>
                <option value="owner">Owner</option>
                <option value="admin">Admin</option>
            </select>

            <select x-model="status" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 w-full sm:w-36">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
            
            <div class="w-full sm:w-64 relative">
                <input type="text" x-model.debounce.500ms="search" placeholder="Cari pengguna..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
        
        <!-- Loading Overlay -->
        <div x-show="loading" class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 flex items-center justify-center" style="display: none;">
            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <div id="table-container">
            @include('dashboard.users._table')
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('userManager', () => ({
        search: '',
        status: '',
        role: '',
        perPage: '10',
        loading: false,

        init() {
            this.$watch('search', () => this.fetchUsers());
            this.$watch('status', () => this.fetchUsers());
            this.$watch('role', () => this.fetchUsers());
            this.$watch('perPage', () => this.fetchUsers());
            
            // Handle pagination clicks
            document.getElementById('table-container').addEventListener('click', (e) => {
                const link = e.target.closest('a.page-link');
                if (link) {
                    e.preventDefault();
                    this.fetchUsers(link.href);
                }
            });
        },

        fetchUsers(url = null) {
            this.loading = true;
            
            let targetUrl = url;
            if (!targetUrl || typeof targetUrl !== 'string') {
                targetUrl = '{{ route('users.index') }}';
            }
            
            const params = new URLSearchParams(targetUrl.includes('?') ? targetUrl.split('?')[1] : '');
            if (this.search) params.set('search', this.search);
            if (this.status) params.set('status', this.status);
            if (this.role) params.set('role', this.role);
            if (this.perPage !== '10') params.set('per_page', this.perPage);
            
            const finalUrl = `${targetUrl.split('?')[0]}?${params.toString()}`;

            fetch(finalUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('table-container').innerHTML = html;
                
                // Re-initialize Alpine in the new HTML
                const tableContainer = document.getElementById('table-container');
                if (window.Alpine) {
                    window.Alpine.initTree(tableContainer);
                }
            })
            .finally(() => {
                this.loading = false;
            });
        },

        confirmDelete(url, name) {
            Swal.fire({
                title: 'Hapus Pengguna?',
                text: `Apakah Anda yakin ingin menghapus "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.action = url;
                    form.method = 'POST';
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        },

        toggleActive(url) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.fetchUsers();
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
            });
        }
    }));
});
</script>
@endsection
