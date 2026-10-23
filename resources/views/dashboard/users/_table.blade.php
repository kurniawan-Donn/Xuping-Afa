@php
    $me = auth()->user();
    $roleBadge = ['superadmin' => 'badge-dark', 'owner' => 'badge-gold', 'admin' => 'badge-gray'];
@endphp
<div class="overflow-x-auto">
    <table class="table-base min-w-[640px]">
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Peran</th>
                <th>Status</th>
                <th class="text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                @php
                    $role = $user->getRoleNames()->first();
                    $isSelf = $user->id === $me->id;
                    $canManage = ! $isSelf && ! ($me->hasRole('owner') && $user->hasRole(['superadmin', 'owner']));
                @endphp
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar_url }}" alt="" class="h-10 w-10 rounded-full object-cover">
                            <div class="min-w-0">
                                <p class="font-medium">{{ $user->name }} @if ($isSelf)<span class="badge badge-gold ml-1">Anda</span>@endif</p>
                                <p class="truncate text-xs text-stone-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge {{ $roleBadge[$role] ?? 'badge-gray' }} capitalize">{{ $role ?? '—' }}</span></td>
                    <td>
                        @if ($canManage)
                            <button type="button" x-data="{ active: {{ $user->is_active ? 'true' : 'false' }}, busy: false }"
                                @click="busy = true; fetch('{{ route('users.toggle-active', $user) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' } })
                                    .then(r => r.json()).then(d => { if (d.success) { active = d.is_active; } else { Swal.fire({ icon: 'error', title: 'Gagal', text: d.message }); } })
                                    .catch(() => Swal.fire({ icon: 'error', title: 'Gagal mengubah status' })).finally(() => busy = false)"
                                :disabled="busy" class="inline-flex items-center gap-2 text-xs font-medium" :aria-pressed="active">
                                <span :class="active ? 'bg-emerald-500' : 'bg-stone-200'" class="relative h-5 w-9 rounded-full transition">
                                    <span :class="active ? 'translate-x-4' : 'translate-x-0'" class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white shadow transition"></span>
                                </span>
                                <span x-text="active ? 'Aktif' : 'Nonaktif'" :class="active ? 'text-emerald-700' : 'text-stone-500'"></span>
                            </button>
                        @else
                            <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-gray' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex items-center justify-end gap-1">
                            @if ($isSelf || $canManage)
                                <a href="{{ $isSelf ? route('profile.edit') : route('users.edit', $user) }}" class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-stone-100 hover:text-ink" title="Edit"><x-icon name="edit" class="h-4 w-4" /></a>
                            @endif
                            @if ($canManage)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" data-confirm="Akun {{ $user->name }} akan dihapus dan tidak bisa login lagi.">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="grid h-9 w-9 place-items-center rounded-lg text-stone-400 hover:bg-red-50 hover:text-red-600" title="Hapus"><x-icon name="trash" class="h-4 w-4" /></button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-16 text-center text-stone-500">Tidak ada pengguna yang cocok.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if ($users->hasPages())
    <div class="border-t border-stone-100 px-4 py-3">{{ $users->links() }}</div>
@endif
