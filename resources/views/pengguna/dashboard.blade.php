@extends('layouts.pengguna')

@section('title', 'Dashboard Pengguna')
@section('header_title', 'Dashboard Saya')
@section('header_subtitle', 'Pantau ketersediaan sarana prasarana kampus dan kelola peminjaman fasilitas secara mudah')

@php
    $availableFacilities = $availableFacilities ?? \App\Models\Facility::where('status', 'aktif')->orderBy('nama')->take(4)->get();
@endphp

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- ==========================================
         1. WELCOME BANNER (FROSTED GLASS HERO)
         ========================================== --}}
    <div class="bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
        <!-- Ambient radial glow inside hero -->
        <div class="absolute -right-20 -top-20 w-60 h-60 rounded-full bg-emerald-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 border border-emerald-500/20 text-[#0F5143] dark:text-[#34D399] mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Akun Terverifikasi — {{ ucfirst(auth()->user()->tipe_pengguna ?? 'Mahasiswa') }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">
                Selamat Datang, {{ auth()->user()->name }}!
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 max-w-2xl mt-1.5 leading-relaxed">
                Cek fasilitas kampus yang siap digunakan, ajukan peminjaman ruangan atau laboratorium, dan laporkan kendala sarana prasarana secara terpadu.
            </p>
        </div>

        <div class="relative z-10 shrink-0 flex items-center gap-3">
            <a href="{{ route('facilities') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-white/70 dark:bg-white/10 hover:bg-white/90 dark:hover:bg-white/20 transition-all border border-white/80 dark:border-white/15 shadow-xs">
                <svg class="w-4 h-4 text-[#0F5143] dark:text-[#34D399]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Lihat Katalog</span>
            </a>
            <a href="{{ route('reservation') }}"
               class="kezak-btn-primary inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Buat Reservasi</span>
            </a>
        </div>
    </div>

    {{-- ==========================================
         2. STATS METRIK KPI (FROSTED GLASS CARDS)
         ========================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        {{-- Card 1: Fasilitas Siap Pakai --}}
        <a href="{{ route('facilities') }}"
           class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between hover:scale-[1.01] hover:border-emerald-500/50 transition-all group">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Fasilitas Siap Pakai</span>
                <span class="text-3xl sm:text-4xl font-extrabold text-[#0F5143] dark:text-white mt-1 block tracking-tight">{{ $stats['aktif'] ?? 0 }}</span>
                <span class="text-xs font-semibold text-[#0F5143] dark:text-[#34D399] mt-1.5 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                    Buka katalog fasilitas &rarr;
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </a>

        {{-- Card 2: Dalam Pemeliharaan --}}
        <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Dalam Pemeliharaan</span>
                <span class="text-3xl sm:text-4xl font-extrabold text-amber-600 dark:text-amber-400 mt-1 block tracking-tight">{{ $stats['dalam_perbaikan'] ?? 0 }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 block">
                    Sedang ditangani teknisi
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-100/80 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>

        {{-- Card 3: Total Fasilitas --}}
        <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Total Sarana Prasarana</span>
                <span class="text-3xl sm:text-4xl font-extrabold text-slate-800 dark:text-white mt-1 block tracking-tight">{{ $stats['total_facilities'] ?? 0 }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 block">
                    Ruang, aula & laboratorium
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
         3. DAFTAR FASILITAS POPULER / REKOMENDASI (PREVIEW)
         ========================================== --}}
    <div class="bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-7">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Fasilitas Siap Digunakan
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Fasilitas berstatus aktif yang siap diajukan untuk kegiatan akademik atau organisasi
                </p>
            </div>
            <a href="{{ route('facilities') }}"
               class="text-xs font-bold text-[#0F5143] dark:text-[#34D399] hover:underline flex items-center gap-1">
                <span>Lihat Semua</span>
                <span>&rarr;</span>
            </a>
        </div>

        @if ($availableFacilities->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($availableFacilities as $facility)
                    <div class="p-4 rounded-2xl bg-white/70 dark:bg-white/5 border border-white/70 dark:border-white/10 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[10px] font-bold uppercase tracking-wider bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399]">
                                    <x-facility-icon :tipe="$facility->tipe" class="w-3.5 h-3.5" />
                                    <span>{{ str_replace('_', ' ', ucfirst($facility->tipe)) }}</span>
                                </span>
                                <span class="w-2 h-2 rounded-full bg-emerald-500" title="Aktif"></span>
                            </div>

                            <h3 class="font-extrabold text-sm sm:text-base text-slate-800 dark:text-white group-hover:text-[#0F5143] dark:group-hover:text-[#34D399] transition-colors line-clamp-1">
                                {{ $facility->nama }}
                            </h3>

                            <div class="mt-2 space-y-1 text-xs text-slate-500 dark:text-slate-400">
                                <p class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ $facility->lokasi }}</span>
                                </p>
                                @if ($facility->kapasitas)
                                    <p class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span>Kapasitas: {{ $facility->kapasitas }} orang</span>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-200/60 dark:border-white/10">
                            <a href="{{ route('reservation') }}"
                               class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl text-xs font-bold text-[#0F5143] dark:text-[#34D399] bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition-colors">
                                <span>Pilih Ruangan</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-xs sm:text-sm text-slate-500">Belum ada fasilitas aktif yang terdaftar.</p>
            </div>
        @endif
    </div>

</div>
@endsection
