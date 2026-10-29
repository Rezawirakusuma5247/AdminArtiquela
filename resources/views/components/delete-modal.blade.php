<div x-show="deleteOpen" x-cloak style="display: none;"
     @keydown.escape.window="deleteOpen = false"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     role="dialog" aria-modal="true">

    <div x-show="deleteOpen"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="deleteOpen = false"
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

    <div x-show="deleteOpen"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-sm rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-2xl border border-gray-100 dark:border-gray-700">

        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
        </div>

        <div class="text-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Konfirmasi Hapus</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Yakin ingin menghapus <span class="font-semibold text-gray-700 dark:text-gray-200" x-text="deleteName"></span>?
                Tindakan ini tidak bisa dibatalkan.
            </p>
            {{ $slot }}
        </div>

        <div class="mt-6 flex items-center space-x-3">
            <button type="button" @click="deleteOpen = false"
                    class="w-1/2 py-2.5 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold text-xs rounded-xl transition duration-150">
                Batal
            </button>
            <form method="POST" :action="deleteUrl" class="w-1/2">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-red-200 dark:shadow-none transition duration-150">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
