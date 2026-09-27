@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')
@section('header_title', 'Dashboard Operasional')
@section('header_subtitle', 'Tinjau permohonan reservasi, ketersediaan fasilitas, dan status operasional kampus secara realtime')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- ==========================================
         1. WELCOME BANNER (FROSTED HERO)
         ========================================== --}}
    <div class="bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-60 h-60 rounded-full bg-emerald-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 border border-emerald-500/20 text-[#0F5143] dark:text-[#34D399] mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Akun Petugas Aktif</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">
                Selamat Datang, {{ auth()->user()->name ?? 'Petugas' }}!
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 max-w-2xl mt-1.5 leading-relaxed">
                Ini adalah portal operasional Petugas Fasilitas. Anda dapat memantau antrean reservasi fasilitas kampus dan melakukan validasi operasional secara langsung.
            </p>
        </div>

        <div class="relative z-10 shrink-0">
            <a href="{{ route('petugas.reservations.index') }}"
               class="kezak-btn-primary inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-bold shadow-md cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Tinjau Antrean Reservasi</span>
            </a>
        </div>
    </div>

    {{-- ==========================================
         2. OVERVIEW STATS CARDS (FROSTED GLASS)
         ========================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Menunggu Verifikasi (Amber / Pending) -->
        <a href="{{ route('petugas.reservations.index') }}"
           class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between hover:scale-[1.01] hover:border-amber-400 transition-all group">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400 block">Antrean Menunggu</span>
                <span class="text-3xl sm:text-4xl font-extrabold text-amber-600 dark:text-white mt-1 block tracking-tight">{{ $stats['pending_reservations'] ?? 0 }}</span>
                <span class="text-xs font-semibold text-amber-600 dark:text-amber-400 mt-1.5 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                    Tinjau antrean reservasi &rarr;
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-100/80 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </a>

        <!-- Reservasi Hari Ini (Teal/Emerald) -->
        <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Jadwal Hari Ini</span>
                <span class="text-3xl sm:text-4xl font-extrabold text-[#0F5143] dark:text-white mt-1 block tracking-tight">{{ $stats['today_reservations'] ?? 0 }}</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1.5 block">
                    Reservasi aktif disetujui
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <!-- Fasilitas Siap Pakai (Emerald) -->
        <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Fasilitas Siap Pakai</span>
                <span class="text-3xl sm:text-4xl font-extrabold text-emerald-600 dark:text-white mt-1 block tracking-tight">{{ $stats['aktif'] ?? 0 }}</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1.5 block">
                    Status operasional aktif
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Fasilitas Dalam Perbaikan (Yellow / Amber) -->
        <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Dalam Perbaikan</span>
                <span class="text-3xl sm:text-4xl font-extrabold text-yellow-600 dark:text-white mt-1 block tracking-tight">{{ $stats['dalam_perbaikan'] ?? 0 }}</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1.5 block">
                    Perlu monitoring perbaikan
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-yellow-100/80 dark:bg-yellow-950/60 text-yellow-700 dark:text-yellow-400 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- ==========================================
         3. INFORMASI SESI PETUGAS (FROSTED CARD)
         ========================================== --}}
    <div class="rounded-3xl bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 p-6 sm:p-7 shadow-xs space-y-5">
        <h3 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span>Informasi Sesi Petugas</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-white/40 dark:border-white/10">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Nama Petugas</span>
                    <span class="text-xs font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-white/40 dark:border-white/10">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Alamat Email</span>
                    <span class="text-xs font-mono text-slate-900 dark:text-white">{{ auth()->user()->email }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-white/40 dark:border-white/10">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Peran Sistem</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 capitalize border border-emerald-300 dark:border-emerald-800">
                        {{ auth()->user()->role }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Status Akun</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Terverifikasi (Verified)
                    </span>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/70 dark:border-white/10 flex flex-col justify-between">
                <div>
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-[#0F5143] dark:text-[#34D399] mb-1.5">
                        Pedoman Validasi Antrean
                    </h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Saat menyetujui sebuah permohonan reservasi, seluruh pengajuan lain yang memiliki jam dan ruangan bertabrakan (tumpang tindih) akan otomatis ditolak oleh sistem dengan alasan bentrok jadwal secara transparan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
