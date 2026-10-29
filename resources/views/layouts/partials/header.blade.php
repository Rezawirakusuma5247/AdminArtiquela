<header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40 transition-colors duration-200"
        x-data="{ logoutModalOpen: false, profileOpen: false }">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Toggle Button & User Info -->
        <div class="flex items-center space-x-3">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors duration-150">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="text-left">
                <p class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->getRoleNames()->first() ?? 'Administrator' }}</p>
            </div>
        </div>

        <!-- Right Side Actions: Theme Toggle & Profile -->
        <div class="flex items-center space-x-3">

            <!-- Dark / Light Mode Toggle Button -->
            <button @click="toggleTheme()"
                    type="button"
                    class="p-2.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors duration-150 focus:outline-none"
                    :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">

                <!-- Sun Icon (Tampil saat Dark Mode aktif) -->
                <svg x-show="darkMode" x-cloak class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>

                <!-- Moon Icon (Tampil saat Light Mode aktif) -->
                <svg x-show="!darkMode" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>

            <!-- Profile Dropdown -->
            <div class="relative" @click.outside="profileOpen = false" @keydown.escape.window="profileOpen = false">
                <button @click="profileOpen = !profileOpen"
                        type="button"
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#098DEC] text-sm font-bold text-white shadow-md shadow-blue-500/20 hover:bg-blue-600 focus:outline-none transition duration-150"
                        :aria-expanded="profileOpen"
                        aria-haspopup="true"
                        title="Profil">
                    {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                </button>

                <div x-show="profileOpen"
                     x-cloak
                     style="display: none;"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 origin-top-right overflow-hidden rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg">

                    <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-3">
                        <p class="truncate text-sm font-bold text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</p>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="p-1.5">
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profil Saya
                        </a>

                        <button type="button"
                                @click="profileOpen = false; logoutModalOpen = true"
                                class="flex w-full items-center rounded-lg px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors duration-150">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmation Logout -->
    <div x-show="logoutModalOpen"
         x-cloak
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">

        <!-- Backdrop Overlay -->
        <div x-show="logoutModalOpen"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="logoutModalOpen = false"
             class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Content -->
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div x-show="logoutModalOpen"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all w-full max-w-sm p-6 border border-gray-100 dark:border-gray-700">

                <!-- Icon Warning -->
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                </div>

                <!-- Title & Text -->
                <div class="text-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100" id="modal-title">Konfirmasi Logout</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Apakah Anda yakin ingin keluar dari sistem <span class="font-semibold text-gray-700 dark:text-gray-300">Grand Titik Kumpul</span>?</p>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex items-center space-x-3">
                    <button @click="logoutModalOpen = false"
                            type="button"
                            class="w-1/2 py-2.5 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold text-xs rounded-xl transition duration-150">
                        Batal
                    </button>

                    <form method="POST" action="{{ route('logout') }}" class="w-1/2">
                        @csrf
                        <button type="submit"
                                class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-red-200 dark:shadow-none transition duration-150">
                            Ya, Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
