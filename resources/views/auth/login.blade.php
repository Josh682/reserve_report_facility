@extends('layouts.guest', [
    'title' => 'Masuk — Fasilitas Kampus',
    'header' => 'Masuk ke Sistem',
    'subheader' => 'Sistem Reservasi & Pelaporan Fasilitas Kampus'
])

@section('content')
    <!-- Status Alert Sukses (misal: pesan sukses setelah registrasi / logout) -->
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

    <!-- Status Alert Peringatan (misal: akun pending verifikasi admin) -->
    @if (session('status_warning'))
        <div class="mb-6 rounded-lg bg-yellow-50 dark:bg-yellow-900/30 p-4 border border-yellow-200 dark:border-yellow-800">
            <div class="flex items-start">
                <div class="flex-shrink-0 text-yellow-500 dark:text-yellow-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                        {{ session('status_warning') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Status Alert Error (misal: akun ditolak admin) -->
    @if (session('status_error'))
        <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-800">
            <div class="flex items-start">
                <div class="flex-shrink-0 text-red-500 dark:text-red-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ session('status_error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                Alamat Email
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autocomplete="email" required
                    value="{{ old('email') }}"
                    class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 px-3.5 py-2.5 text-slate-900 dark:text-white placeholder-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/50 dark:bg-slate-900 sm:text-sm"
                    placeholder="nama@kampus.test">
            </div>
            @error('email')
                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                Kata Sandi
            </label>
            <div class="mt-1">
                <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="block w-full rounded-xl border border-slate-300 dark:border-slate-700 px-3.5 py-2.5 text-slate-900 dark:text-white placeholder-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/50 dark:bg-slate-900 sm:text-sm"
                    placeholder="••••••••">
            </div>
            @error('password')
                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox"
                    class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500 dark:border-slate-700 dark:bg-slate-900">
                <label for="remember" class="ml-2 block text-xs font-medium text-slate-600 dark:text-slate-400">
                    Ingat Saya
                </label>
            </div>
        </div>

        <div>
            <button type="submit"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-xs text-sm font-bold text-white bg-teal-700 hover:bg-teal-800 dark:bg-teal-600 dark:hover:bg-teal-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors cursor-pointer">
                Masuk ke Akun
            </button>
        </div>

        <!-- Tombol Masuk Sebagai Guest -->
        <div class="relative my-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200 dark:border-slate-800"></div>
            </div>
            <div class="relative flex justify-center text-[11px] uppercase">
                <span class="px-3 bg-white dark:bg-[#0b171c] text-slate-400 dark:text-slate-500 font-medium tracking-wider">
                    Atau jelajahi tanpa akun
                </span>
            </div>
        </div>

        <div>
            <a href="{{ route('facilities') }}"
                id="guest-login-button"
                class="w-full flex items-center justify-center gap-2.5 py-2.5 px-4 border border-teal-200 dark:border-teal-900/60 rounded-xl shadow-xs text-sm font-bold text-teal-800 dark:text-teal-300 bg-teal-50/70 hover:bg-teal-100/80 dark:bg-teal-950/40 dark:hover:bg-teal-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all">
                <svg class="w-4 h-4 text-teal-600 dark:text-teal-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>Masuk sebagai Guest (Lihat Fasilitas)</span>
            </a>
        </div>

        <div class="text-center pt-2">
            <span class="text-xs text-slate-500 dark:text-slate-400">
                Belum memiliki akun?
            </span>
            <a href="{{ route('register') }}" class="text-xs font-bold text-teal-700 hover:text-teal-800 dark:text-teal-400 ml-1 hover:underline">
                Daftar sekarang
            </a>
        </div>
    </form>
@endsection
