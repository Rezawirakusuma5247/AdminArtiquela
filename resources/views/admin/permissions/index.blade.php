@extends('layouts.app')

@section('content')
    <div class="space-y-6"
         x-data="{
             formOpen: {{ $errors->any() ? 'true' : 'false' }},
             mode: @js(old('_mode', 'create')),
             editId: @js(old('_id', '')),
             name: @js(old('name', '')),
             updateUrl: @js(route('admin.permissions.update', ':id')),
             storeUrl: @js(route('admin.permissions.store')),
             deleteOpen: false,
             deleteUrl: '',
             deleteName: '',
             openCreate() { this.mode = 'create'; this.editId = ''; this.name = ''; this.formOpen = true; },
             openEdit(id, name) { this.mode = 'edit'; this.editId = id; this.name = name; this.formOpen = true; },
         }">

        <x-flash />

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Permissions</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Format nama: <span class="font-mono">modul.aksi</span>, contoh <span class="font-mono">product.view</span>.
                </p>
            </div>
            @can('permission.create')
                <button type="button" @click="openCreate()"
                        class="inline-flex items-center justify-center rounded-xl bg-[#098DEC] px-4 py-2.5 text-xs font-semibold text-white shadow-md shadow-blue-500/20 hover:bg-blue-600 transition duration-150">
                    + Tambah Permission
                </button>
            @endcan
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <form method="GET" action="{{ route('admin.permissions.index') }}" class="p-4 border-b border-gray-100 dark:border-gray-700">
                <input type="search"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari permission..."
                       autocomplete="off"
                       x-init="if ($el.value) { $el.focus(); $el.setSelectionRange($el.value.length, $el.value.length) }"
                       @input.debounce.500ms="$el.form.requestSubmit()"
                       class="w-full sm:w-80 rounded-xl border border-gray-300 dark:border-gray-600 px-3.5 py-2.5 text-sm focus:border-[#098DEC] focus:ring-2 focus:ring-[#098DEC]/30">
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Modul</th>
                            <th class="px-4 py-3">Dipakai Role</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($permissions as $permission)
                            <tr>
                                <td class="px-4 py-3 font-mono text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $permission->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-blue-50 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-semibold text-blue-600 dark:text-blue-300">
                                        {{ str_contains($permission->name, '.') ? \Illuminate\Support\Str::before($permission->name, '.') : 'lainnya' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $permission->roles_count }} role</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end space-x-2">
                                        @can('permission.edit')
                                            <button type="button"
                                                    @click="openEdit({{ $permission->id }}, @js($permission->name))"
                                                    class="rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-600 hover:bg-amber-100 transition">Edit</button>
                                        @endcan
                                        @can('permission.delete')
                                            <button type="button"
                                                    @click="deleteOpen = true; deleteUrl = @js(route('admin.permissions.destroy', $permission)); deleteName = @js($permission->name)"
                                                    class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100 transition">Hapus</button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500">
                                    {{ $search !== '' ? 'Tidak ada permission yang cocok dengan pencarian.' : 'Belum ada permission.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($permissions->hasPages())
                <div class="border-t border-gray-100 dark:border-gray-700 px-4 py-3">
                    {{ $permissions->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Create / Edit -->
        <div x-show="formOpen" x-cloak style="display: none;"
             @keydown.escape.window="formOpen = false"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             role="dialog" aria-modal="true">

            <div x-show="formOpen"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="formOpen = false"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

            <div x-show="formOpen"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-md rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-2xl border border-gray-100 dark:border-gray-700">

                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100"
                    x-text="mode === 'edit' ? 'Edit Permission' : 'Tambah Permission'"></h3>

                <form method="POST"
                      :action="mode === 'edit' ? updateUrl.replace(':id', editId) : storeUrl"
                      class="mt-4">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" :disabled="mode !== 'edit'">
                    <input type="hidden" name="_mode" :value="mode">
                    <input type="hidden" name="_id" :value="editId">

                    <label for="permission_name" class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Nama Permission</label>
                    <input id="permission_name" type="text" name="name" x-model="name" required
                           placeholder="contoh: product.view"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 px-3.5 py-2.5 font-mono text-sm focus:border-[#098DEC] focus:ring-2 focus:ring-[#098DEC]/30">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                    <p class="mt-3 text-xs text-gray-500 dark:text-gray-400" x-show="mode === 'edit'">
                        Perhatian: kalau nama ini dipakai di kode (<span class="font-mono">permission:...</span> atau <span class="font-mono">@can</span>), ikut ubah di kodenya.
                    </p>

                    <div class="mt-6 flex items-center space-x-3">
                        <button type="button" @click="formOpen = false"
                                class="w-1/2 py-2.5 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold text-xs rounded-xl transition duration-150">
                            Batal
                        </button>
                        <button type="submit"
                                class="w-1/2 py-2.5 px-4 bg-[#098DEC] hover:bg-blue-600 text-white font-semibold text-xs rounded-xl shadow-md shadow-blue-500/20 transition duration-150">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <x-delete-modal>
            <p class="mt-2 text-xs text-amber-600 dark:text-amber-400">Permission ini juga akan dicabut dari semua role dan user.</p>
        </x-delete-modal>
    </div>
@endsection
