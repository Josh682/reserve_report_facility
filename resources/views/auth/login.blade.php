@extends('layouts.guest', [
    'title' => 'Masuk — Fasilitas Kampus',
    'header' => 'Masuk ke Sistem',
    'subheader' => 'Sistem Reservasi & Pelaporan Fasilitas Kampus'
])

@section('content')
    <!-- Status Alert (misal: pesan sukses setelah registrasi) -->
    @if (session('status'))
        <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
            <div class="flex items-start">
                <div class="flex-shrink-0 text-green-500 dark:text-green-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('status') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ url('/login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Alamat Email
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autocomplete="email" required
                    value="{{ old('email') }}"
                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-gray-900 dark:text-white placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:bg-gray-700 sm:text-sm"
                    placeholder="nama@kampus.test">
            </div>
            @error('email')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Kata Sandi
            </label>
            <div class="mt-1">
                <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-gray-900 dark:text-white placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:bg-gray-700 sm:text-sm"
                    placeholder="••••••••">
            </div>
            @error('password')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Masuk
            </button>
        </div>

        <div class="text-center pt-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">
                Belum memiliki akun?
            </span>
            <a href="{{ route('register') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 ml-1">
                Daftar sekarang
            </a>
        </div>
    </form>
@endsection
