@php
    $linkBase   = 'flex items-center rounded-xl px-3.5 py-2.5 font-medium transition-all duration-150 whitespace-nowrap';
    $linkActive = 'bg-[#098DEC] text-white shadow-md shadow-blue-500/20';
    $linkIdle   = 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white';
    $groupTitle = 'mb-2 whitespace-nowrap px-3 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500';
@endphp

<!-- Mobile Backdrop -->
<div x-show="sidebarOpen"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-10 bg-gray-900/40 backdrop-blur-sm lg:hidden"></div>

<!-- Sidebar -->
<aside
    :class="sidebarOpen ? 'w-64' : 'w-0 lg:w-20'"
    class="fixed lg:static inset-y-0 left-0 z-20 h-screen flex-shrink-0 overflow-hidden bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700/60 shadow-sm transition-all duration-300 ease-in-out">

    <!-- Inner wrapper -->
    <div class="flex h-full w-64 flex-col">

        <!-- Sidebar Brand Header -->
        <div class="flex items-center border-b border-gray-100 dark:border-gray-700/60 p-5 space-x-3 overflow-hidden">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 flex-shrink-0 object-contain">
            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="whitespace-nowrap">
                <h2 class="text-sm font-bold text-gray-800 dark:text-gray-100 leading-tight">Artiquela</h2>
                <p class="text-[9px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Admin Panel</p>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 space-y-6 no-scrollbar overflow-y-auto overflow-x-hidden p-4 text-sm">

            <!-- Home Group -->
            <div>
                <div x-show="sidebarOpen" class="{{ $groupTitle }}">Home</div>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           title="Dashboard"
                           class="{{ $linkBase }} {{ request()->routeIs('dashboard') ? $linkActive : $linkIdle }}">
                            <svg class="h-5 w-5 flex-shrink-0" :class="sidebarOpen && 'mr-2'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span x-show="sidebarOpen" x-transition>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Akses & Pengguna Group -->
            @canany(['user.view', 'permission.view'])
                <div>
                    <div x-show="sidebarOpen" class="{{ $groupTitle }}">Akses &amp; Pengguna</div>
                    <ul class="space-y-1">
                        @can('user.view')
                            <li>
                                <a href="{{ route('admin.users.index') }}"
                                   title="Admin Users"
                                   class="{{ $linkBase }} {{ request()->routeIs('admin.users.*') ? $linkActive : $linkIdle }}">
                                    <svg class="h-5 w-5 flex-shrink-0" :class="sidebarOpen && 'mr-2'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <span x-show="sidebarOpen" x-transition>Admin Users</span>
                                </a>
                            </li>
                        @endcan

                        @can('permission.view')
                            <li>
                                <a href="{{ route('admin.permissions.index') }}"
                                   title="Permissions"
                                   class="{{ $linkBase }} {{ request()->routeIs('admin.permissions.*') ? $linkActive : $linkIdle }}">
                                    <svg class="h-5 w-5 flex-shrink-0" :class="sidebarOpen && 'mr-2'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <span x-show="sidebarOpen" x-transition>Permissions</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            @endcanany

        </nav>
    </div>
</aside>
