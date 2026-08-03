@extends('layouts.guest')
@section('title', 'Login')
@section('content')
<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-semibold text-gray-800">
            Afa Xuping Accessories
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Panel Manajemen
        </p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-lg font-medium text-gray-700 mb-6">
            Masuk ke Akun Anda
        </h2>
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@gmail.com" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg" required autofocus>
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Password
                </label>
                <input type="password" name="password" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg" required>
            </div>
            <div class="mb-6 flex items-center">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" class="ml-2 text-sm text-gray-500">
                    Ingat saya
                </label>
            </div>
            <button class="w-full bg-gray-800 hover:bg-gray-700 text-white py-2.5 rounded-lg">
                Masuk
            </button>
        </form>
    </div>
    <p class="text-center text-xs text-gray-400 mt-6">
        © {{ date('Y') }} Afa Xuping Accessories
    </p>
</div>
@endsection
