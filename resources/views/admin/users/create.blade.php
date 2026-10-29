@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Admin</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat akun admin baru dan atur hak aksesnya.</p>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}"
              class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            @csrf
            @include('admin.users._form', ['user' => null])
        </form>
    </div>
@endsection
