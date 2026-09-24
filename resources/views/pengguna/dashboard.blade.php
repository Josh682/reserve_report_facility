@extends('layouts.pengguna')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header / Banner -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-800 text-white shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/30 text-blue-100 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Akun Terverifikasi — {{ ucfirst(auth()->user()->tipe_pengguna ?? 'Mahasiswa') }}</span>
            </div>
            <h2 class="text-2xl font-bold tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h2>
            <p class="mt-1 text-blue-100 text-sm max-w-2xl">
                Cek fasilitas kampus yang siap digunakan, ajukan peminjaman ruangan, dan pantau status persetujuan secara mandiri.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <a href="{{ route('facilities') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-white text-blue-700 hover:bg-blue-50 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Lihat Katalog</span>
            </a>
            <a href="{{ route('reservation') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-blue-500 hover:bg-blue-400 text-white shadow-sm transition-all border border-blue-400/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Pinjam Fasilitas</span>
            </a>
        </div>
    </div>

    <!-- Overview Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Fasilitas Siap Pakai (Emerald) -->
        <a href="{{ route('facilities') }}" class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between hover:border-emerald-500/50 transition-all group">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Fasilitas Siap Pakai</p>
                <p class="mt-2 text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $stats['aktif'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Klik untuk jelajahi katalog &rarr;</p>
            </div>
            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-xl group-hover:scale-105 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </a>

        <!-- Reservasi Saya Disetujui (Blue) -->
        <a href="{{ route('reservation') }}" class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between hover:border-blue-500/50 transition-all group">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Peminjaman Disetujui</p>
                <p class="mt-2 text-3xl font-extrabold text-blue-600 dark:text-blue-400">{{ $stats['my_approved'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Peminjaman aktif disetujui</p>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-xl group-hover:scale-105 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </a>

        <!-- Menunggu Persetujuan (Amber) -->
        <a href="{{ route('reservation') }}" class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-xs flex items-center justify-between hover:border-amber-500/50 transition-all group">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Menunggu Persetujuan</p>
                <p class="mt-2 text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ $stats['my_pending'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Sedang ditinjau petugas</p>
            </div>
            <div class="p-3 bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 rounded-xl group-hover:scale-105 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </a>
    </div>

    <!-- Fasilitas Pilihan yang Siap Digunakan -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Fasilitas Kampus Siap Digunakan
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pilih ruangan yang ingin Anda ajukan untuk kegiatan perkuliahan, praktikum, atau organisasi.</p>
            </div>
            <a href="{{ route('facilities') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse ($availableFacilities as $facility)
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 flex flex-col justify-between hover:shadow-md transition-shadow bg-gray-50/50 dark:bg-gray-700/30">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 capitalize">
                                {{ str_replace('_', ' ', $facility->tipe) }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Aktif
                            </span>
                        </div>
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $facility->nama }}</h4>
                        <div class="mt-2 space-y-1 text-xs text-gray-500 dark:text-gray-400">
                            <p class="flex items-center gap-1.5">
                                <span>⌖</span>
                                <span class="truncate">{{ $facility->lokasi }}</span>
                            </p>
                            @if ($facility->kapasitas)
                                <p class="flex items-center gap-1.5">
                                    <span>♟</span>
                                    <span>Kapasitas {{ $facility->kapasitas }} orang</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between gap-2">
                        <a href="{{ route('facilities') }}" class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 font-medium">
                            Jadwal
                        </a>
                        <a href="{{ route('reservation', ['facility_id' => $facility->id]) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-xs transition-colors">
                            <span>+ Pinjam</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada fasilitas aktif yang dapat ditampilkan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Riwayat Pengajuan Reservasi Terakhir Saya -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Riwayat Pengajuan Reservasi Terakhir Saya
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Daftar peminjaman ruangan yang telah Anda ajukan ke sistem.</p>
            </div>
            <a href="{{ route('reservation') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                Kelola Semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="py-3 px-4">Fasilitas / Ruangan</th>
                        <th class="py-3 px-4">Tanggal & Jam</th>
                        <th class="py-3 px-4">Tujuan</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($recentReservations as $res)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                            <td class="py-3 px-4 font-semibold text-gray-900 dark:text-white">
                                {{ $res->facility->nama ?? 'Fasilitas #' . $res->facility_id }}
                                <span class="block text-xs font-normal text-gray-500 dark:text-gray-400">{{ $res->facility->lokasi ?? '' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-medium text-gray-800 dark:text-gray-200">
                                    {{ \Illuminate\Support\Carbon::parse($res->tanggal)->translatedFormat('d M Y') }}
                                </span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400">
                                    {{ substr($res->start_time, 0, 5) }} - {{ substr($res->end_time, 0, 5) }} WIB
                                </span>
                            </td>
                            <td class="py-3 px-4 text-xs text-gray-600 dark:text-gray-300 max-w-xs truncate" title="{{ $res->tujuan_penggunaan }}">
                                {{ $res->tujuan_penggunaan }}
                            </td>
                            <td class="py-3 px-4">
                                @if ($res->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                        Disetujui
                                    </span>
                                @elseif ($res->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">
                                        Menunggu
                                    </span>
                                @elseif ($res->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                Anda belum memiliki riwayat pengajuan reservasi.
                                <a href="{{ route('reservation') }}" class="text-blue-600 dark:text-blue-400 font-semibold ml-1 hover:underline">
                                    Ajukan peminjaman sekarang &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
