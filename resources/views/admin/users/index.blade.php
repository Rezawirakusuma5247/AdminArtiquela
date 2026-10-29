@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="{ deleteOpen: false, deleteUrl: '', deleteName: '' }">
        <x-flash />

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Admin Users</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola akun admin beserta role dan hak aksesnya.</p>
            </div>
            @can('user.create')
                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-[#098DEC] px-4 py-2.5 text-xs font-semibold text-white shadow-md shadow-blue-500/20 hover:bg-blue-600 transition duration-150">
                    + Tambah Admin
                </a>
            @endcan
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <form method="GET" action="{{ route('admin.users.index') }}" class="p-4 border-b border-gray-100 dark:border-gray-700">
                <input type="search"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama atau email..."
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
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Dibuat</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-800 dark:text-gray-100">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($user->roles as $role)
                                            <span class="rounded-full bg-blue-50 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-semibold text-blue-600 dark:text-blue-300">{{ $role->name }}</span>
                                        @empty
                                            <span class="text-xs text-gray-400">-</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $user->created_at?->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end space-x-2">
                                        @can('user.edit')
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-600 hover:bg-amber-100 transition">Edit</a>
                                        @endcan
                                        @can('user.delete')
                                            @if (! $user->is(auth()->user()))
                                                <button type="button"
                                                        @click="deleteOpen = true; deleteUrl = @js(route('admin.users.destroy', $user)); deleteName = @js($user->name)"
                                                        class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100 transition">Hapus</button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">
                                    {{ $search !== '' ? 'Tidak ada user yang cocok dengan pencarian.' : 'Belum ada data user.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="border-t border-gray-100 dark:border-gray-700 px-4 py-3">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <x-delete-modal />
    </div>
@endsection
