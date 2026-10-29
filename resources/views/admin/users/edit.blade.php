@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Admin</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Perbarui data <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $user->name }}</span>.</p>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}"
              class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            @csrf
            @method('PUT')
            @include('admin.users._form', ['user' => $user])
        </form>
    </div>
@endsection
