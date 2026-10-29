@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
         class="flex items-center justify-between rounded-xl border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 px-4 py-3 text-sm text-green-700 dark:text-green-300">
        <span>{{ session('success') }}</span>
        <button type="button" @click="show = false" class="ml-4 text-green-500 hover:text-green-700">&times;</button>
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition
         class="flex items-center justify-between rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-sm text-red-700 dark:text-red-300">
        <span>{{ session('error') }}</span>
        <button type="button" @click="show = false" class="ml-4 text-red-500 hover:text-red-700">&times;</button>
    </div>
@endif
