<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggleTheme() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
          }
      }"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.webp') }}">

    <title>Admin - Office Artiquela</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 h-screen overflow-hidden transition-colors duration-200"
      x-data="{ sidebarOpen: true }">
    <div class="h-screen flex overflow-hidden">
        <!-- Sidebar Full Height (Kiri) -->
        @include('layouts.partials.sidebar')

        <!-- Main Wrapper (Header + Content + Footer) -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden transition-all duration-300">
            <!-- Header (Kanan) -->
            @include('layouts.partials.header')

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('layouts.partials.footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
