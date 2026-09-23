@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header / Banner -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-800 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Selamat Datang, {{ auth()->user()->name ?? 'Admin' }}!</h2>
            <p class="mt-1 text-indigo-100 text-sm">
                Kelola master data fasilitas kampus, pantau status operasional, dan tindak lanjuti permintaan reservasi.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.facilities.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-indigo-700 hover:bg-indigo-50 font-semibold text-sm rounded-lg shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Fasilitas
            </a>
        </div>
    </div>

    <!-- Overview Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Fasilitas (Blue) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Fasilitas</p>
                <p class="mt-2 text-3xl font-extrabold text-blue-600 dark:text-blue-400">{{ $stats['total'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Semua unit terdaftar</p>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-xl">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        <!-- Fasilitas Aktif (Green) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Fasilitas Aktif</p>
                <p class="mt-2 text-3xl font-extrabold text-green-600 dark:text-green-400">{{ $stats['aktif'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Siap dipinjam/digunakan</p>
            </div>
            <div class="p-3 bg-green-50 dark:bg-green-950/50 text-green-600 dark:text-green-400 rounded-xl">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Fasilitas Dalam Perbaikan (Yellow) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Dalam Perbaikan</p>
                <p class="mt-2 text-3xl font-extrabold text-yellow-600 dark:text-yellow-400">{{ $stats['dalam_perbaikan'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Sedang pemeliharaan</p>
            </div>
            <div class="p-3 bg-yellow-50 dark:bg-yellow-950/50 text-yellow-600 dark:text-yellow-400 rounded-xl">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
        </div>

        <!-- Fasilitas Nonaktif (Gray/Red) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Fasilitas Nonaktif</p>
                <p class="mt-2 text-3xl font-extrabold text-gray-600 dark:text-gray-400">{{ $stats['nonaktif'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tidak beroperasi</p>
            </div>
            <div class="p-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Shortcuts -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-xs">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Aksi Cepat Manajemen Fasilitas</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('admin.facilities.index') }}"
               class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400 hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20 transition-all group">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-lg group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                        Kelola Fasilitas
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Lihat daftar lengkap fasilitas, filter berdasarkan kategori, ubah status, atau edit detail.
                    </p>
                </div>
            </a>

            <a href="{{ route('admin.facilities.create') }}"
               class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400 hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20 transition-all group">
                <div class="p-3 bg-green-50 dark:bg-green-950/50 text-green-600 dark:text-green-400 rounded-lg group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                        Tambah Fasilitas Baru
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Registrasikan gedung, laboratorium, aula, atau peralatan baru ke dalam sistem reservasi.
                    </p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
