<div class="overflow-x-auto">
    <table class="w-full text-left text-sm text-gray-500 whitespace-nowrap">
        <thead class="bg-gray-50/50 text-gray-600 font-medium border-b border-gray-100">
            <tr>
                <th class="px-6 py-4">Pengguna</th>
                <th class="px-6 py-4">Peran</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Bergabung Sejak</th>
                <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition-colors group">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                        <div>
                            <div class="font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    @foreach($user->roles as $role)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700 capitalize">
                        {{ $role->name }}
                    </span>
                    @endforeach
                </td>
                <td class="px-6 py-4">
                    @if($user->is_active)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Nonaktif
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-500">
                    {{ $user->created_at->locale('id')->translatedFormat('d F Y') }}
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        @if($user->id !== auth()->id() && !(auth()->user()->hasRole('owner') && $user->hasRole(['superadmin', 'owner'])))
                        <button type="button" @click="toggleActive('{{ route('users.toggle-active', $user) }}')" class="p-1.5 {{ $user->is_active ? 'text-orange-600 bg-orange-50 hover:bg-orange-100' : 'text-emerald-600 bg-emerald-50 hover:bg-emerald-100' }} rounded-md transition-colors" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                            @if($user->is_active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </button>
                        @endif

                        @if(!(auth()->user()->hasRole('owner') && $user->hasRole(['superadmin', 'owner']) && $user->id !== auth()->id()))
                        <a href="{{ route('users.edit', $user) }}" class="p-1.5 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        @endif

                        @if($user->id !== auth()->id() && !(auth()->user()->hasRole('owner') && $user->hasRole(['superadmin', 'owner'])))
                        <button type="button" @click="confirmDelete('{{ route('users.destroy', $user) }}', '{{ addslashes($user->name) }}')" class="p-1.5 text-red-600 bg-red-50 hover:bg-red-100 rounded-md transition-colors" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-medium text-gray-900 mb-1">Tidak ada pengguna</h3>
                        <p class="text-sm text-gray-500">Belum ada data pengguna yang sesuai dengan pencarian Anda.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($users->hasPages() || $users->total() > 0)
<div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="text-sm text-gray-500">
            Menampilkan <span class="font-medium text-gray-900">{{ $users->firstItem() ?? 0 }}</span> - <span class="font-medium text-gray-900">{{ $users->lastItem() ?? 0 }}</span> dari <span class="font-medium text-gray-900">{{ $users->total() }}</span> data
        </div>
        <div class="w-full sm:w-auto">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endif
