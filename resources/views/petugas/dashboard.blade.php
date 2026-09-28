@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')
@section('header_title', 'Dashboard Operasional')
@section('header_subtitle', 'Pantau kesiapan fasilitas kampus, jadwal operasional, dan status pemeliharaan secara realtime')

@php
    $repairFacilities = \App\Models\Facility::where('status', 'dalam_perbaikan')->orderBy('nama')->take(4)->get();
    $activeFacilities = \App\Models\Facility::where('status', 'aktif')->orderBy('nama')->take(4)->get();
@endphp

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
                Ini adalah portal operasional Petugas Fasilitas. Anda dapat memantau kesiapan sarana prasarana kampus dan melakukan monitoring operasional secara langsung.
            </p>
        </div>

        <div class="relative z-10 shrink-0 flex items-center gap-3">
            <a href="{{ route('facilities') }}"
               class="kezak-btn-primary inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-bold shadow-md cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Katalog Fasilitas</span>
            </a>
        </div>
    </div>

    {{-- ==========================================
         2. OVERVIEW STATS CARDS (FROSTED GLASS)
         ========================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Fasilitas Siap Pakai -->
        <a href="{{ route('facilities') }}"
           class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between hover:scale-[1.01] hover:border-emerald-500/50 transition-all group">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Fasilitas Siap Pakai</span>
                <span class="text-3xl sm:text-4xl font-extrabold text-[#0F5143] dark:text-white mt-1 block tracking-tight">{{ $stats['aktif'] ?? 0 }}</span>
                <span class="text-xs font-semibold text-[#0F5143] dark:text-[#34D399] mt-1.5 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                    Status aktif & operasional &rarr;
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </a>

        <!-- Dalam Perbaikan -->
        <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400 block">Dalam Pemeliharaan</span>
                <span class="text-3xl sm:text-4xl font-extrabold text-amber-600 dark:text-amber-400 mt-1 block tracking-tight">{{ $stats['dalam_perbaikan'] ?? 0 }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 block">
                    Perlu monitoring teknisi
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-100/80 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>

        <!-- Total Fasilitas Kampus -->
        <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Total Sarana Prasarana</span>
                <span class="text-3xl sm:text-4xl font-extrabold text-slate-800 dark:text-white mt-1 block tracking-tight">{{ $stats['total_facilities'] ?? 0 }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 block">
                    Terdata dalam sistem kampus
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-teal-100/80 dark:bg-teal-950/60 text-teal-700 dark:text-teal-400 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>
    </div>

    {{-- ==========================================
         3. DAFTAR FASILITAS DALAM PEMELIHARAAN (MONITORING)
         ========================================== --}}
    <div class="bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-7">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Monitoring Fasilitas Dalam Pemeliharaan
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Fasilitas yang sedang dalam status perbaikan atau maintenance
                </p>
            </div>
            <a href="{{ route('facilities') }}"
               class="text-xs font-bold text-[#0F5143] dark:text-[#34D399] hover:underline flex items-center gap-1">
                <span>Semua Fasilitas</span>
                <span>&rarr;</span>
            </a>
        </div>

        @if ($repairFacilities->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($repairFacilities as $facility)
                    <div class="p-4 rounded-2xl bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200/60 dark:border-amber-900/40 shadow-2xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300">
                                    Dalam Perbaikan
                                </span>
                            </div>
                            <h3 class="font-extrabold text-sm text-slate-800 dark:text-white line-clamp-1">
                                {{ $facility->nama }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 truncate">
                                {{ $facility->lokasi }}
                            </p>
                        </div>
                        <div class="mt-3 text-[11px] text-amber-700 dark:text-amber-400 font-medium">
                            {{ $facility->deskripsi ?? 'Perbaikan rutin' }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300">Tidak ada fasilitas dalam perbaikan.</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Seluruh fasilitas kampus siap beroperasi normal.</p>
            </div>
        @endif
    </div>

</div>
@endsection
