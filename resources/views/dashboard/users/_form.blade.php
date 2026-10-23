@php $isEdit = $user->exists; @endphp
<div class="grid gap-6 lg:grid-cols-3">
    <section class="card p-5 lg:p-6" x-data="imagePreview(@js($isEdit ? $user->avatar_url : null))">
        <p class="label">Foto profil</p>
        <label class="group relative mx-auto block h-32 w-32 cursor-pointer overflow-hidden rounded-full border-2 border-dashed border-stone-300 bg-stone-100 transition hover:border-gold-400">
            <img x-show="url" :src="url" alt="" class="h-full w-full object-cover">
            <span x-show="!url" class="absolute inset-0 grid place-items-center text-stone-400"><x-icon name="user" class="h-10 w-10" stroke="1.25" /></span>
            <span class="absolute inset-x-0 bottom-0 bg-ink/60 py-1 text-center text-[11px] font-medium text-white opacity-0 transition group-hover:opacity-100">Ganti</span>
            <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="pick($event)">
        </label>
        <p class="hint mt-3 text-center">Opsional. Maks. 2 MB.</p>
        @error('avatar')<p class="error text-center">{{ $message }}</p>@enderror
    </section>

    <section class="card space-y-4 p-5 lg:col-span-2 lg:p-6">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="label">Nama <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required maxlength="255" class="input" autocomplete="name">
                @error('name')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="label">Email <span class="text-red-500">*</span></label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required maxlength="255" class="input" autocomplete="email">
                @error('email')<p class="error">{{ $message }}</p>@enderror
            </div>
        </div>

        @unless ($isEdit)
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="label">Kata sandi <span class="text-red-500">*</span></label>
                    <input id="password" name="password" type="password" required minlength="8" class="input" autocomplete="new-password">
                    <p class="hint">Minimal 8 karakter.</p>
                    @error('password')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="label">Ulangi kata sandi <span class="text-red-500">*</span></label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" class="input" autocomplete="new-password">
                </div>
            </div>
        @endunless

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="role" class="label">Peran <span class="text-red-500">*</span></label>
                <select id="role" name="role" required class="input capitalize">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @selected(old('role', $isEdit ? $user->getRoleNames()->first() : 'admin') === $role->name)>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                <p class="hint">Admin: kelola katalog. Owner: + pengguna & pengaturan.</p>
                @error('role')<p class="error">{{ $message }}</p>@enderror
            </div>
            <label class="flex cursor-pointer flex-col">
                <span class="label">Akun aktif</span>
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $isEdit ? $user->is_active : true)) class="peer sr-only">
                <span class="relative mt-1.5 h-6 w-11 rounded-full bg-stone-200 transition after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:bg-ink peer-checked:after:translate-x-5"></span>
                <span class="hint">Akun nonaktif tidak bisa login.</span>
            </label>
        </div>

        <div class="flex gap-2 pt-2">
            <a href="{{ route('users.index') }}" class="btn btn-outline flex-1">Batal</a>
            <button type="submit" class="btn btn-dark flex-[2]">{{ $isEdit ? 'Simpan Perubahan' : 'Buat Akun' }}</button>
        </div>
    </section>
</div>
