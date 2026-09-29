@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')
@section('header_title', 'Dashboard Operasional')
@section('header_subtitle', 'Tinjau antrean reservasi, laporan kerusakan fasilitas, dan status operasional kampus secara realtime (US 8)')

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
                Ini adalah portal operasional Petugas Fasilitas. Anda dapat memantau antrean permohonan reservasi serta menindaklanjuti laporan kerusakan fasilitas kampus secara terpusat.
            </p>
        </div>

        <div class="relative z-10 shrink-0 flex flex-wrap items-center gap-3">
            <a href="{{ route('petugas.reservations.index') }}"
               class="kezak-btn-primary inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-bold shadow-md cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Antrean Reservasi</span>
            </a>

            <a href="{{ route('petugas.reports.index', ['status' => 'baru']) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-rose-800 dark:text-rose-300 bg-rose-500/15 hover:bg-rose-500/25 border border-rose-400/40 transition-all shadow-xs cursor-pointer">
                <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Tinjau Laporan Kerusakan</span>
            </a>
        </div>
    </div>

    {{-- ==========================================
         2. OVERVIEW STATS CARDS (FROSTED GLASS 5 CARDS)
         ========================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-5">
        <!-- 1. Antrean Reservasi (Amber) -->
        <a href="{{ route('petugas.reservations.index') }}"
           class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between hover:scale-[1.01] hover:border-amber-400 transition-all group">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400 block">Antrean Menunggu</span>
                <span class="text-3xl font-extrabold text-amber-600 dark:text-white mt-1 block tracking-tight">{{ $stats['pending_reservations'] ?? 0 }}</span>
                <span class="text-xs font-semibold text-amber-600 dark:text-amber-400 mt-1.5 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                    Reservasi pending &rarr;
                </span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-100/80 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </a>

        <!-- 2. Laporan Kerusakan Menunggu (Rose / Alert) -->
        <a href="{{ route('petugas.reports.index', ['status' => 'baru']) }}"
           class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between hover:scale-[1.01] hover:border-rose-400 transition-all group">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-rose-700 dark:text-rose-400 block">Laporan Kerusakan Menunggu</span>
                <span class="text-3xl font-extrabold text-rose-600 dark:text-white mt-1 block tracking-tight">{{ $stats['pending_reports'] ?? 0 }}</span>
                <span class="text-xs font-semibold text-rose-600 dark:text-rose-400 mt-1.5 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                    Laporan baru &rarr;
                </span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-rose-100/80 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </a>

        <!-- 3. Jadwal Hari Ini (Teal/Emerald) -->
        <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Jadwal Hari Ini</span>
                <span class="text-3xl font-extrabold text-[#0F5143] dark:text-white mt-1 block tracking-tight">{{ $stats['today_reservations'] ?? 0 }}</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1.5 block">
                    Reservasi aktif
                </span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <!-- 4. Fasilitas Siap Pakai (Emerald) -->
        <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Fasilitas Siap Pakai</span>
                <span class="text-3xl font-extrabold text-emerald-600 dark:text-white mt-1 block tracking-tight">{{ $stats['aktif'] ?? 0 }}</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1.5 block">
                    Status aktif
                </span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- 5. Fasilitas Dalam Perbaikan (Yellow / Amber) -->
        <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/60 dark:border-white/10 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Dalam Perbaikan</span>
                <span class="text-3xl font-extrabold text-yellow-600 dark:text-white mt-1 block tracking-tight">{{ $stats['dalam_perbaikan'] ?? 0 }}</span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1.5 block">
                    Monitoring teknisi
                </span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-yellow-100/80 dark:bg-yellow-950/60 text-yellow-700 dark:text-yellow-400 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- ==========================================
         3. ANTREAN LAPORAN KERUSAKAN MENUNGGU TINDAK LANJUT (US 8)
         ========================================== --}}
    <div class="rounded-3xl bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 p-6 sm:p-7 shadow-xs space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/40 dark:border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-100/80 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 flex items-center justify-center shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight">
                        Laporan Kerusakan Menunggu Tindak Lanjut
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-0.5">
                        Antrean keluhan kendala fasilitas dari mahasiswa/dosen yang memerlukan inspeksi fisik (US 8)
                    </p>
                </div>
            </div>

            <a href="{{ route('petugas.reports.index', ['status' => 'baru']) }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-700 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 transition-colors">
                <span>Buka Seluruh Antrean Laporan &rarr;</span>
            </a>
        </div>

        @if (isset($pendingReports) && $pendingReports->isNotEmpty())
            <div class="space-y-3.5">
                @foreach ($pendingReports as $report)
                    <div class="p-4 sm:p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/70 dark:border-white/10 hover:border-rose-400/50 hover:shadow-xs transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-2 min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-900">
                                    #REP-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                                <h4 class="text-sm font-extrabold text-slate-900 dark:text-white truncate">
                                    {{ $report->facility->nama ?? 'Fasilitas Terhapus' }}
                                </h4>
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-white/70 dark:bg-white/10 text-slate-700 dark:text-slate-300 border border-white/80 dark:border-white/10 capitalize">
                                    {{ $report->kategori }}
                                </span>
                                <span class="text-xs text-slate-400 dark:text-slate-500">•</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">
                                    Pelapor: <strong class="text-slate-700 dark:text-slate-300">{{ $report->user->name ?? 'Pengguna' }}</strong>
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2 leading-relaxed">
                                {{ $report->deskripsi }}
                            </p>

                            <div class="text-[11px] text-slate-400 dark:text-slate-500 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Dilaporkan {{ $report->created_at->diffForHumans() }} ({{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB)</span>
                            </div>
                        </div>

                        <div class="shrink-0 flex items-center gap-2.5">
                            @if ($report->foto_path)
                                <a href="{{ asset('storage/' . $report->foto_path) }}" target="_blank" class="w-10 h-10 rounded-xl overflow-hidden border border-white/80 dark:border-white/20 shadow-2xs hover:scale-105 transition-transform shrink-0" title="Buka Foto Bukti Kerusakan">
                                    <img src="{{ asset('storage/' . $report->foto_path) }}" alt="Bukti" class="w-full h-full object-cover">
                                </a>
                            @endif

                            <a href="{{ route('petugas.reports.index', ['status' => 'baru', 'search' => $report->facility->nama ?? '']) }}"
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-800 dark:text-white bg-white/80 dark:bg-white/10 hover:bg-white dark:hover:bg-white/20 border border-white/80 dark:border-white/15 transition-all shadow-xs cursor-pointer">
                                <span>Tindak Lanjuti</span>
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 rounded-2xl bg-white/40 dark:bg-white/5 border border-dashed border-white/60 dark:border-white/10 space-y-2">
                <div class="w-10 h-10 mx-auto rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h4 class="text-sm font-extrabold text-slate-800 dark:text-white">Tidak Ada Laporan Kerusakan Menunggu</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                    Seluruh keluhan kendala fasilitas kampus telah diverifikasi atau telah selesai ditangani oleh teknisi lapangan.
                </p>
            </div>
        @endif
    </div>

    {{-- ==========================================
         4. ANTREAN RESERVASI MENUNGGU (US 8)
         ========================================== --}}
    <div class="rounded-3xl bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 p-6 sm:p-7 shadow-xs space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/40 dark:border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-100/80 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight">
                        Antrean Reservasi Menunggu Verifikasi
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-0.5">
                        Permohonan peminjaman ruang kelas, lab, atau aula yang menunggu persetujuan petugas
                    </p>
                </div>
            </div>

            <a href="{{ route('petugas.reservations.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-700 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 transition-colors">
                <span>Buka Seluruh Antrean Reservasi &rarr;</span>
            </a>
        </div>

        @if (isset($pendingReservations) && $pendingReservations->isNotEmpty())
            <div class="space-y-3.5">
                @foreach ($pendingReservations as $reservation)
                    <div class="p-4 sm:p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/70 dark:border-white/10 hover:border-amber-400/50 hover:shadow-xs transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1.5 min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-900">
                                    #RSV-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                                <h4 class="text-sm font-extrabold text-slate-900 dark:text-white truncate">
                                    {{ $reservation->facility->nama ?? 'Fasilitas Terhapus' }}
                                </h4>
                                <span class="text-xs text-slate-400 dark:text-slate-500">•</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">
                                    Pemohon: <strong class="text-slate-700 dark:text-slate-300">{{ $reservation->user->name ?? 'Pengguna' }}</strong>
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-300">
                                <strong class="text-slate-700 dark:text-slate-200">Jadwal:</strong> {{ \Carbon\Carbon::parse($reservation->tanggal)->translatedFormat('l, d F Y') }} • Pukul {{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }} WIB
                            </p>

                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 italic">
                                "{{ $reservation->tujuan_penggunaan }}"
                            </p>
                        </div>

                        <div class="shrink-0 flex items-center gap-2.5">
                            <a href="{{ route('petugas.reservations.index', ['search' => $reservation->user->name ?? '']) }}"
                               class="kezak-btn-primary px-3.5 py-2 text-xs font-bold shadow-xs cursor-pointer inline-flex items-center gap-1.5">
                                <span>Tinjau Permohonan</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 rounded-2xl bg-white/40 dark:bg-white/5 border border-dashed border-white/60 dark:border-white/10 space-y-2">
                <div class="w-10 h-10 mx-auto rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h4 class="text-sm font-extrabold text-slate-800 dark:text-white">Tidak Ada Permohonan Reservasi Pending</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                    Seluruh pengajuan peminjaman ruangan telah diproses (disetujui atau ditolak).
                </p>
            </div>
        @endif
    </div>

    {{-- ==========================================
         5. INFORMASI SESI PETUGAS (FROSTED CARD)
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
                        Saat menyetujui sebuah permohonan reservasi, seluruh pengajuan lain yang memiliki jam dan ruangan bertabrakan (tumpang tindih) akan otomatis ditolak oleh sistem dengan alasan bentrok jadwal secara transparan. Pada laporan kerusakan yang sedang diproses, Anda juga dapat menandai fasilitas sebagai <em>Dalam Perbaikan</em> untuk mencegah peminjaman baru selama penanganan teknisi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
