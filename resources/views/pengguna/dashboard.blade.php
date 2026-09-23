@extends('layouts.pengguna')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header / Banner -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-800 text-white shadow-sm">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/30 text-blue-100 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Akun Terverifikasi — {{ ucfirst(auth()->user()->tipe_pengguna ?? 'Mahasiswa') }}</span>
        </div>
        <h2 class="text-2xl font-bold tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p class="mt-1 text-blue-100 text-sm max-w-2xl">
            Selamat datang di Portal Fasilitas Kampus. Anda dapat memantau status sarana prasarana, mengecek fasilitas yang siap digunakan, dan mempersiapkan pengajuan reservasi atau pelaporan kendala.
        </p>
    </div>

    <!-- Overview Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Fasilitas Siap Pakai (Emerald) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Fasilitas Siap Pakai</p>
                <p class="mt-2 text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $stats['aktif'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Dapat digunakan / dipinjam</p>
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
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Dalam Pemeliharaan</p>
                <p class="mt-2 text-3xl font-extrabold text-yellow-600 dark:text-yellow-400">{{ $stats['dalam_perbaikan'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Sedang diperbaiki teknisi</p>
            </div>
            <div class="p-3 bg-yellow-50 dark:bg-yellow-950/50 text-yellow-600 dark:text-yellow-400 rounded-xl">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
        </div>

        <!-- Total Fasilitas (Blue) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Sarana Prasarana</p>
                <p class="mt-2 text-3xl font-extrabold text-blue-600 dark:text-blue-400">{{ $stats['total_facilities'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Fasilitas terdaftar di kampus</p>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-xl">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Informasi Akun & Layanan Pengguna -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs p-6">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profil Akun & Status Layanan
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Nama Lengkap</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Email Akun</span>
                    <span class="text-sm font-mono text-gray-900 dark:text-white">{{ auth()->user()->email }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700/60">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Kategori Sivitas Akademika</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 capitalize">
                        {{ auth()->user()->tipe_pengguna ?? 'Mahasiswa' }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Status Verifikasi</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        Terverifikasi (Verified)
                    </span>
                </div>
            </div>

            <div class="p-4 rounded-lg bg-blue-50/60 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/40 flex flex-col justify-between">
                <div>
                    <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-1">
                        Layanan Reservasi & Pelaporan Mandiri
                    </h4>
                    <p class="text-xs text-blue-700 dark:text-blue-300 leading-relaxed">
                        Fitur formulir peminjaman fasilitas (reservasi ruangan/alat) dan pelaporan kerusakan fasilitas secara mandiri akan segera diaktifkan pada rilis modul layanan berikutnya.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
