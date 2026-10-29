<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('images/logo.webp')}}" class="">

    <title>Login - Office Artiquela</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-slate-50 text-gray-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="bg-white p-8 rounded-2xl shadow-xl  border border-gray-300">

            <!-- Session Status Alert -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-blue-50 border border-blue-100 text-[#098DEC] text-xs font-semibold rounded-xl">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Email Address
                    </label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="username"
                           placeholder="nama@domain.com"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#098DEC] focus:ring-2 focus:ring-blue-100 transition duration-150">

                    @if ($errors->has('email'))
                        <p class="mt-2 text-xs text-red-500 font-medium">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Password
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-xs text-[#098DEC] font-semibold hover:underline" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#098DEC] focus:ring-2 focus:ring-blue-100 transition duration-150">

                    @if ($errors->has('password'))
                        <p class="mt-2 text-xs text-red-500 font-medium">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me"
                           type="checkbox"
                           name="remember"
                           class="w-4 h-4 rounded border-gray-300 text-[#098DEC] focus:ring-[#098DEC] transition">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600 font-medium">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-3 px-4 bg-[#098DEC] hover:bg-blue-600 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-200 hover:shadow-lg transition duration-200">
                    Log In
                </button>
            </form>
        </div>

        <!-- Footer Text -->
        <p class="text-center text-xs text-gray-400 mt-8">
            &copy; {{ date('Y') }} Artiquela. All rights reserved.
        </p>
    </div>

</body>
</html>
