@extends('layouts.petugas')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header / Banner -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-800 text-white shadow-sm">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/30 text-emerald-100 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span>Akun Petugas Aktif</span>
        </div>
        <h2 class="text-2xl font-bold tracking-tight">Selamat Datang, {{ auth()->user()->name ?? 'Petugas' }}!</h2>
        <p class="mt-1 text-emerald-100 text-sm max-w-2xl">
            Ini adalah portal operasional Petugas Fasilitas. Anda dapat memantau ketersediaan fasilitas kampus dan melakukan koordinasi operasional secara langsung.
        </p>
    </div>

    <!-- Overview Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Fasilitas Aktif (Emerald) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Fasilitas Siap Pakai</p>
                <p class="mt-2 text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $stats['aktif'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Status aktif dan dapat dipinjam</p>
            </div>
            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-xl">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Fasilitas Dalam Perbaikan (Yellow / Amber) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Dalam Perbaikan</p>
                <p class="mt-2 text-3xl font-extrabold text-yellow-600 dark:text-yellow-400">{{ $stats['dalam_perbaikan'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Perlu monitoring & perbaikan</p>
            </div>
            <div class="p-3 bg-yellow-50 dark:bg-yellow-950/50 text-yellow-600 dark:text-yellow-400 rounded-xl">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
        </div>

        <!-- Total Fasilitas (Blue/Indigo) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Fasilitas</p>
                <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats['total_facilities'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Terdaftar dalam sistem kampus</p>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-xl">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Informasi Akun & Sesi Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs p-6">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Informasi Sesi Petugas
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Nama Petugas</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Alamat Email</span>
                    <span class="text-sm font-mono text-gray-900 dark:text-white">{{ auth()->user()->email }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Peran Sistem</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 capitalize">
                        {{ auth()->user()->role }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Status Akun</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        Terverifikasi (Verified)
                    </span>
                </div>
            </div>

            <div class="p-4 rounded-lg bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/40 flex flex-col justify-between">
                <div>
                    <h4 class="text-sm font-semibold text-emerald-900 dark:text-emerald-200 mb-1">
                        Informasi Modul Operasional Petugas
                    </h4>
                    <p class="text-xs text-emerald-700 dark:text-emerald-300 leading-relaxed">
                        Fitur manajemen laporan kerusakan, investigasi fasilitas, dan penugasan perbaikan akan aktif pada rilis modul operasional berikutnya. Anda dapat memutuskan sesi kapan saja menggunakan tombol Keluar di pojok kanan atas.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
