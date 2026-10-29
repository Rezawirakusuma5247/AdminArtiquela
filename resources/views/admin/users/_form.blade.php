@php
    $isEdit = isset($user) && $user->exists;

    $input = 'w-full rounded-xl border border-gray-300 dark:border-gray-600 px-3.5 py-2.5 text-sm focus:border-[#098DEC] focus:ring-2 focus:ring-[#098DEC]/30';
    $label = 'block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5';

    $selectedRoles = session()->hasOldInput()
        ? old('roles', [])
        : ($isEdit ? $user->roles->pluck('name')->all() : []);

    $selectedPermissions = session()->hasOldInput()
        ? old('permissions', [])
        : ($isEdit ? $user->getDirectPermissions()->pluck('name')->all() : []);
@endphp

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="name" class="{{ $label }}">Nama</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user?->name) }}" required class="{{ $input }}">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email" class="{{ $label }}">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user?->email) }}" required class="{{ $input }}">
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="{{ $label }}">
            Password @if ($isEdit) <span class="font-normal text-gray-400">(kosongkan jika tidak diubah)</span> @endif
        </label>
        <input id="password" type="password" name="password" autocomplete="new-password" @if(! $isEdit) required @endif class="{{ $input }}">
        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password_confirmation" class="{{ $label }}">Konfirmasi Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" @if(! $isEdit) required @endif class="{{ $input }}">
    </div>
</div>

<!-- Roles -->
<div class="mt-6">
    <p class="{{ $label }}">Role</p>
    <div class="flex flex-wrap gap-3">
        @forelse ($roles as $role)
            <label class="inline-flex items-center space-x-2 rounded-xl border border-gray-200 dark:border-gray-700 px-3.5 py-2 text-sm text-gray-700 dark:text-gray-200 cursor-pointer">
                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                       @checked(in_array($role->name, $selectedRoles))
                       class="rounded border-gray-300 text-[#098DEC] focus:ring-[#098DEC]">
                <span>{{ $role->name }}</span>
            </label>
        @empty
            <p class="text-xs text-gray-400">Belum ada role.</p>
        @endforelse
    </div>
    @error('roles.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

<!-- Direct Permissions -->
<div class="mt-6">
    <p class="{{ $label }}">Permission Langsung <span class="font-normal text-gray-400">(tambahan di luar role)</span></p>
    <div class="space-y-4">
        @forelse ($permissionGroups as $group => $items)
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <p class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-400">{{ $group }}</p>
                <div class="flex flex-wrap gap-x-5 gap-y-2">
                    @foreach ($items as $permission)
                        <label class="inline-flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-200 cursor-pointer">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                   @checked(in_array($permission->name, $selectedPermissions))
                                   class="rounded border-gray-300 text-[#098DEC] focus:ring-[#098DEC]">
                            <span>{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-xs text-gray-400">Belum ada permission.</p>
        @endforelse
    </div>
    @error('permissions.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

<div class="mt-8 flex items-center justify-end space-x-3">
    <a href="{{ route('admin.users.index') }}"
       class="rounded-xl bg-gray-100 dark:bg-gray-700 px-4 py-2.5 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition">Batal</a>
    <button type="submit"
            class="rounded-xl bg-[#098DEC] px-5 py-2.5 text-xs font-semibold text-white shadow-md shadow-blue-500/20 hover:bg-blue-600 transition">
        {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
    </button>
</div>
