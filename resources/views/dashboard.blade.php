@extends('layouts.app')

@section('title', 'dashboard office')
@section('content')
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Selamat datang kembali, <span class="font-semibold text-gray-700">{{ Auth::user()->name }}</span>!</p>
            </div>
            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                Online
            </span>
        </div>

        <!-- Content Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center space-x-3 text-gray-700">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-base font-medium">
                    {{ __("You're logged in!") }}
                </p>
            </div>
        </div>
    </div>
@endsection
