@extends('layouts.admin')

@section('title', 'Rekapitulasi Okupansi & Kerusakan Fasilitas')
@section('header_title', 'Rekapitulasi Okupansi & Kerusakan Fasilitas')

@section('content')
<div class="space-y-7">

    {{-- Hidden/subtle backward compatibility block for existing aggregations test suite --}}
    <div data-testid="filter-params" class="hidden" aria-hidden="true">
        <span data-testid="filter-preset">{{ $filterParams['preset'] }}</span>
        <span data-testid="filter-start">{{ $filterParams['start_date'] ?? 'all' }}</span>
        <span data-testid="filter-end">{{ $filterParams['end_date'] ?? 'all' }}</span>
    </div>

    <!-- ==========================================
         1. HERO BANNER FROSTED GLASS & BADGE US 17
         ========================================== -->
    <div class="relative overflow-hidden rounded-3xl bg-white/65 backdrop-blur-xl border border-white/60 p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.06)]">
        {{-- Background decorative glows --}}
        <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-emerald-400/15 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full bg-teal-300/20 blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100/90 text-emerald-800 border border-emerald-300/80 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                    <span>Modul Analitik &amp; Rekapitulasi — US 17</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">
                    Rekapitulasi Okupansi &amp; Kerusakan Fasilitas
                </h1>
                <p class="text-sm text-slate-600 max-w-2xl leading-relaxed">
                    Pemantauan terpadu utilisasi jam operasional fasilitas kampus dan pemetaan frekuensi insiden kerusakan untuk mendukung pemeliharaan preventif sarana &amp; prasarana.
                </p>
            </div>

            <!-- Multi-Format Export Action Bar -->
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('admin.rekap.export-csv', request()->all()) }}"
                   class="export-action-link inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white/80 hover:bg-white text-slate-700 text-xs sm:text-sm font-semibold border border-white shadow-xs hover:shadow-sm transition-all group"
                   title="Unduh data dalam format CSV terstruktur">
                    <svg class="w-4 h-4 text-emerald-700 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Unduh CSV</span>
                </a>

                <a href="{{ route('admin.rekap.export-excel', request()->all()) }}"
                   class="export-action-link inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs sm:text-sm font-semibold shadow-xs hover:shadow-md transition-all group"
                   title="Unduh laporan lengkap dalam format Microsoft Excel (.xls)">
                    <svg class="w-4 h-4 text-emerald-200 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Unduh Excel (.xls)</span>
                </a>

                <a href="{{ route('admin.rekap.print', request()->all()) }}"
                   target="_blank"
                   class="export-action-link inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white/80 hover:bg-white text-slate-700 text-xs sm:text-sm font-semibold border border-white shadow-xs hover:shadow-sm transition-all group"
                   title="Buka pratinjau cetak / cetak dokumen rekapitulasi PDF">
                    <svg class="w-4 h-4 text-slate-700 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak / Simpan PDF</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ==========================================
         2. KPI SUMMARY CARDS (OKUPANSI & KERUSAKAN)
         ========================================== -->
    <div class="space-y-4">
        <!-- Sub-Header Ringkasan KPI -->
        <div class="flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                Ringkasan Metrik Utama
            </h2>
            <span class="text-xs text-slate-500">
                Rentang: <strong class="text-slate-700 font-semibold">{{ $filterParams['start_date'] ? \Carbon\Carbon::parse($filterParams['start_date'])->translatedFormat('d M Y') : 'Awal' }} — {{ $filterParams['end_date'] ? \Carbon\Carbon::parse($filterParams['end_date'])->translatedFormat('d M Y') : 'Sekarang' }}</strong>
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-4">
            <!-- Okupansi Card 1: Total Jam Pemakaian -->
            <div class="lg:col-span-2 rounded-2xl bg-white/65 backdrop-blur-xl border border-white/60 p-4 sm:p-5 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:bg-white/80 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Jam Pemakaian</span>
                    <div class="w-8 h-8 rounded-xl bg-teal-50 text-[#0F5143] border border-teal-200/60 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline gap-1">
                    <span data-testid="occupancy-total-jam" class="text-2xl font-black text-slate-800 tracking-tight">
                        {{ $occupancySummary['total_hours'] }} jam
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Akumulasi durasi disetujui</p>
            </div>

            <!-- Okupansi Card 2: Total Reservasi Approved -->
            <div class="lg:col-span-2 rounded-2xl bg-white/65 backdrop-blur-xl border border-white/60 p-4 sm:p-5 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:bg-white/80 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Reservasi Approved</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200/60 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span data-testid="occupancy-total-reservasi" class="text-2xl font-black text-emerald-800 tracking-tight">
                        {{ $occupancySummary['total_reservations'] }}
                    </span>
                    <span class="text-xs font-semibold text-slate-500 ml-1">kegiatan</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Status reservasi disetujui</p>
            </div>

            <!-- Okupansi Card 3: Fasilitas Terfavorit -->
            <div class="lg:col-span-3 rounded-2xl bg-white/65 backdrop-blur-xl border border-white/60 p-4 sm:p-5 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:bg-white/80 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Fasilitas Terfavorit</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 border border-amber-200/60 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2 truncate">
                    <span data-testid="occupancy-fasilitas-terfavorit" class="text-xl font-extrabold text-slate-800 tracking-tight truncate block" title="{{ $occupancySummary['most_used_facility'] }}">
                        {{ $occupancySummary['most_used_facility'] }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Frekuensi peminjaman tertinggi</p>
            </div>

            <!-- Kerusakan Card 1: Total Laporan Masuk -->
            <div class="lg:col-span-2 rounded-2xl bg-white/65 backdrop-blur-xl border border-white/60 p-4 sm:p-5 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:bg-white/80 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Laporan Masuk</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 border border-rose-200/60 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span data-testid="damage-total-insiden" class="text-2xl font-black text-rose-700 tracking-tight">
                        {{ $damageSummary['total_reports'] }}
                    </span>
                    <span class="text-xs font-semibold text-slate-500 ml-1">insiden</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Kendala & kerusakan fisik</p>
            </div>

            <!-- Kerusakan Card 2: Selesai Ditangani -->
            <div class="lg:col-span-2 rounded-2xl bg-white/65 backdrop-blur-xl border border-white/60 p-4 sm:p-5 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:bg-white/80 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Selesai Ditangani</span>
                    <div class="w-8 h-8 rounded-xl bg-teal-50 text-[#0F5143] border border-teal-200/60 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span data-testid="damage-total-selesai" class="text-2xl font-black text-slate-800 tracking-tight">
                        {{ $damageSummary['resolved_reports'] }}
                    </span>
                    <span class="text-xs font-semibold text-slate-500 ml-1">terselesaikan</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Perbaikan tuntas petugas</p>
            </div>

            <!-- Kerusakan Card 3: Tingkat Resolusi -->
            <div class="lg:col-span-1 rounded-2xl bg-white/65 backdrop-blur-xl border border-white/60 p-4 sm:p-5 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:bg-white/80 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Resolusi</span>
                    <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-700 border border-sky-200/60 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span data-testid="damage-persentase-resolusi" class="text-2xl font-black {{ $damageSummary['resolution_rate'] >= 75 ? 'text-emerald-700' : ($damageSummary['resolution_rate'] >= 50 ? 'text-amber-700' : 'text-rose-700') }} tracking-tight">
                        {{ $damageSummary['resolution_rate'] }}%
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Rasio perbaikan</p>
            </div>

            <!-- Kerusakan Card 4: Lokasi Paling Rawan -->
            <div class="lg:col-span-2 rounded-2xl bg-white/65 backdrop-blur-xl border border-white/60 p-4 sm:p-5 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:bg-white/80 transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Lokasi Paling Rawan</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 border border-rose-200/60 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2 truncate">
                    <span data-testid="damage-lokasi-paling-rawan" class="text-xl font-extrabold text-slate-800 tracking-tight truncate block" title="{{ $damageSummary['most_damaged_location'] }}">
                        {{ $damageSummary['most_damaged_location'] }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Gedung insiden terbanyak</p>
            </div>
        </div>
    </div>

    <!-- ==========================================
         3. FILTER RENTANG WAKTU (FROSTED CARD)
         ========================================== -->
    <div class="rounded-3xl bg-white/65 backdrop-blur-xl border border-white/60 p-5 sm:p-6 shadow-[0_8px_30px_rgb(0,0,0,0.06)]">
        <form method="GET" action="{{ route('admin.rekap.index') }}" id="date-filter-form" class="space-y-4">
            {{-- Hidden input to maintain the active tab when filtering --}}
            <input type="hidden" name="tab" id="filter-tab-input" value="{{ $activeTab }}">
            <input type="hidden" name="preset" id="filter-preset-input" value="{{ $filterParams['preset'] }}">

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                <!-- Presets Pill Bar -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                        Filter Waktu Cepat
                    </label>
                    <div class="inline-flex flex-wrap items-center gap-2 p-1.5 rounded-2xl bg-slate-900/5 backdrop-blur-md border border-white/70">
                        <button type="button"
                                onclick="applyPreset('bulan_ini')"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $filterParams['preset'] === 'bulan_ini' ? 'bg-[#0F5143] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                            Bulan Ini
                        </button>
                        <button type="button"
                                onclick="applyPreset('30_hari')"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $filterParams['preset'] === '30_hari' ? 'bg-[#0F5143] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                            30 Hari Terakhir
                        </button>
                        <button type="button"
                                onclick="applyPreset('semua')"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $filterParams['preset'] === 'semua' ? 'bg-[#0F5143] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                            Semua Waktu
                        </button>
                    </div>
                </div>

                <!-- Custom Date Inputs -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3 flex-1 lg:max-w-2xl">
                    <div class="flex-1 space-y-1.5">
                        <label for="start_date" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                            Tanggal Mulai
                        </label>
                        <input type="date"
                               name="start_date"
                               id="start_date"
                               value="{{ $filterParams['start_date'] ?? '' }}"
                               class="kezak-input w-full px-3.5 py-2 text-sm text-slate-700"
                               placeholder="YYYY-MM-DD">
                    </div>

                    <div class="flex-1 space-y-1.5">
                        <label for="end_date" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                            Tanggal Selesai
                        </label>
                        <input type="date"
                               name="end_date"
                               id="end_date"
                               value="{{ $filterParams['end_date'] ?? '' }}"
                               class="kezak-input w-full px-3.5 py-2 text-sm text-slate-700"
                               placeholder="YYYY-MM-DD">
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit"
                                onclick="document.getElementById('filter-preset-input').value = 'custom'"
                                class="kezak-btn-primary px-5 py-2.5 text-xs sm:text-sm font-bold flex items-center justify-center gap-2 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Terapkan Filter</span>
                        </button>

                        @if($filterParams['preset'] !== 'bulan_ini' || request()->filled('start_date') || request()->filled('end_date'))
                            <a href="{{ route('admin.rekap.index', ['tab' => $activeTab]) }}"
                               class="px-3 py-2.5 rounded-xl bg-white/70 hover:bg-white text-slate-600 text-xs sm:text-sm font-semibold border border-white shadow-2xs hover:text-rose-700 transition-colors"
                               title="Reset ke kondisi awal (Bulan Ini)">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ==========================================
         4. TAB NAVIGASI INTERAKTIF (OKUPANSI vs KERUSAKAN)
         ========================================== -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-white/40">
        <!-- Segmented Tab Controls -->
        <div class="inline-flex p-1.5 rounded-2xl bg-slate-900/5 backdrop-blur-md border border-white/70 w-full sm:w-auto" role="tablist">
            <!-- Tab 1: Rekap Okupansi Fasilitas Kampus -->
            <button type="button"
                    id="tab-btn-okupansi"
                    role="tab"
                    aria-selected="{{ $activeTab === 'okupansi' ? 'true' : 'false' }}"
                    aria-controls="tab-panel-okupansi"
                    onclick="switchTab('okupansi')"
                    class="tab-trigger flex-1 sm:flex-none flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $activeTab === 'okupansi' ? 'bg-white text-[#0F5143] shadow-sm border border-white/90' : 'text-slate-600 hover:text-slate-900 hover:bg-white/40' }}">
                <svg class="w-4 h-4 {{ $activeTab === 'okupansi' ? 'text-[#0F5143]' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Rekap Okupansi Fasilitas Kampus</span>
            </button>

            <!-- Tab 2: Rekap Frekuensi Kerusakan & Lokasi -->
            <button type="button"
                    id="tab-btn-kerusakan"
                    role="tab"
                    aria-selected="{{ $activeTab === 'kerusakan' ? 'true' : 'false' }}"
                    aria-controls="tab-panel-kerusakan"
                    onclick="switchTab('kerusakan')"
                    class="tab-trigger flex-1 sm:flex-none flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all {{ $activeTab === 'kerusakan' ? 'bg-white text-[#0F5143] shadow-sm border border-white/90' : 'text-slate-600 hover:text-slate-900 hover:bg-white/40' }}">
                <svg class="w-4 h-4 {{ $activeTab === 'kerusakan' ? 'text-[#0F5143]' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Rekap Frekuensi Kerusakan &amp; Lokasi</span>
            </button>
        </div>

        <div class="text-xs text-slate-500 px-1">
            Data diperbarui secara riil berdasarkan catatan transaksi sistem
        </div>
    </div>

    <!-- ==========================================
         5. TAB 1: TABEL OKUPANSI FASILITAS KAMPUS
         ========================================== -->
    <div id="tab-panel-okupansi" class="{{ $activeTab === 'okupansi' ? 'block' : 'hidden' }} space-y-4" role="tabpanel" aria-labelledby="tab-btn-okupansi">

        {{-- Container occupancy-section untuk backward-compatibility test --}}
        <div id="occupancy-section" class="rounded-3xl bg-white/65 backdrop-blur-xl border border-white/60 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.06)]">
            <div class="px-6 py-5 border-b border-white/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold text-slate-800">
                        Tabel Rekapitulasi Okupansi Fasilitas Kampus
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Menampilkan durasi pemakaian aktual, kuantitas reservasi terkonfirmasi, dan profil pemesan dominan
                    </p>
                </div>
                <div class="text-xs font-semibold px-3 py-1.5 rounded-full bg-teal-50 text-[#0F5143] border border-teal-200/60 self-start sm:self-auto">
                    {{ $occupancyData->count() }} Fasilitas Terdaftar
                </div>
            </div>

            @php
                $maxHours = $occupancyData->max('total_jam') ?: 1;
            @endphp

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900/5 text-slate-600 text-[11px] font-extrabold uppercase tracking-wider border-b border-white/60">
                            <th class="py-3.5 px-4 text-center w-12">No</th>
                            <th class="py-3.5 px-5">Nama Fasilitas &amp; Tipe</th>
                            <th class="py-3.5 px-4">Lokasi</th>
                            <th class="py-3.5 px-4 text-center">Kapasitas</th>
                            <th class="py-3.5 px-4 text-center">Total Booking</th>
                            <th class="py-3.5 px-5 min-w-[200px]">Total Jam Pemakaian</th>
                            <th class="py-3.5 px-5">Pemesan Teraktif</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/50 text-xs sm:text-sm text-slate-700">
                        @forelse($occupancyData as $index => $item)
                            @php
                                $barPercent = $maxHours > 0 ? min(100, (int) round(($item['total_jam'] / $maxHours) * 100)) : 0;
                            @endphp
                            <tr data-testid="occupancy-facility-{{ $item['facility_id'] }}"
                                class="hover:bg-white/50 transition-colors">
                                <!-- No -->
                                <td class="py-4 px-4 text-center font-bold text-slate-400">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Nama Fasilitas & Tipe -->
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-teal-500/15 border border-teal-500/25 text-[#0F5143] flex items-center justify-center shrink-0 shadow-2xs">
                                            <x-facility-icon :tipe="$item['facility_tipe']" class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-slate-800">
                                                {{ $item['facility_nama'] }}
                                            </div>
                                            <div class="text-[11px] text-slate-500 capitalize">
                                                {{ str_replace('_', ' ', $item['facility_tipe']) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Lokasi -->
                                <td class="py-4 px-4 font-medium text-slate-700">
                                    {{ $item['facility_lokasi'] }}
                                </td>

                                <!-- Kapasitas -->
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $item['facility_kapasitas'] ?? ($item['facility']->kapasitas ?? '-') }} Orang
                                    </span>
                                </td>

                                <!-- Total Booking -->
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $item['total_reservasi'] > 0 ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                        {{ $item['total_reservasi'] }} kali
                                    </span>
                                </td>

                                <!-- Total Jam Pemakaian (dengan visual bar persentase jam) -->
                                <td class="py-4 px-5">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center justify-between text-xs font-bold text-slate-800">
                                            <span>{{ $item['total_jam'] }} jam</span>
                                            <span class="text-[11px] text-slate-500 font-semibold">{{ $barPercent }}%</span>
                                        </div>
                                        <div class="w-full h-2.5 rounded-full bg-slate-200/80 overflow-hidden p-0.5">
                                            <div class="h-full rounded-full bg-[#0F5143] transition-all duration-500" style="width: {{ $barPercent }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Pemesan Teraktif -->
                                <td class="py-4 px-5">
                                    @if($item['pemesan_terbanyak'] !== '-')
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($item['pemesan_terbanyak'], 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-slate-800 truncate max-w-[150px]" title="{{ $item['pemesan_terbanyak'] }}">
                                                {{ $item['pemesan_terbanyak'] }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 font-medium">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 px-4 text-center">
                                    <div class="max-w-xs mx-auto text-slate-500 space-y-2">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 mx-auto flex items-center justify-center text-slate-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <p class="font-bold text-slate-700">Tidak ada data okupansi</p>
                                        <p class="text-xs text-slate-500">Tidak ditemukan reservasi yang disetujui pada rentang tanggal filter yang dipilih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==========================================
         6. TAB 2: TABEL KERUSAKAN & FREKUENSI LOKASI
         ========================================== -->
    <div id="tab-panel-kerusakan" class="{{ $activeTab === 'kerusakan' ? 'block' : 'hidden' }} space-y-7" role="tabpanel" aria-labelledby="tab-btn-kerusakan">

        {{-- Container damage-section untuk backward-compatibility test --}}
        <div id="damage-section" class="space-y-7">

            <!-- Sub-Tabel A: Frekuensi Kerusakan per Fasilitas -->
            <div class="rounded-3xl bg-white/65 backdrop-blur-xl border border-white/60 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.06)]">
                <div class="px-6 py-5 border-b border-white/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">
                            Sub-tabel A: Frekuensi Kerusakan per Fasilitas
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Rincian kuantitas laporan kendala per kategori dan tingkat penuntasan teknis oleh petugas
                        </p>
                    </div>
                    <div class="text-xs font-semibold px-3 py-1.5 rounded-full bg-rose-50 text-rose-800 border border-rose-200 self-start sm:self-auto">
                        {{ $damageData->count() }} Fasilitas Dianalisis
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900/5 text-slate-600 text-[11px] font-extrabold uppercase tracking-wider border-b border-white/60">
                                <th class="py-3.5 px-5">Fasilitas</th>
                                <th class="py-3.5 px-4">Lokasi</th>
                                <th class="py-3.5 px-4 text-center">Total Laporan</th>
                                <th class="py-3.5 px-3 text-center">Kerusakan Fisik</th>
                                <th class="py-3.5 px-3 text-center">Kebersihan</th>
                                <th class="py-3.5 px-3 text-center">Lainnya</th>
                                <th class="py-3.5 px-5 text-center">Status Selesai / Belum Selesai</th>
                                <th class="py-3.5 px-4 text-center">% Resolusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/50 text-xs sm:text-sm text-slate-700">
                            @forelse($damageData as $item)
                                <tr data-testid="damage-facility-{{ $item['facility_id'] }}"
                                    class="hover:bg-white/50 transition-colors">
                                    <!-- Fasilitas -->
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-rose-500/15 border border-rose-500/25 text-rose-700 flex items-center justify-center shrink-0 shadow-2xs">
                                                <x-facility-icon :tipe="$item['facility_tipe']" class="w-4 h-4" />
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-800">
                                                    {{ $item['facility_nama'] }}
                                                </div>
                                                <div class="text-[11px] text-slate-500 capitalize">
                                                    {{ str_replace('_', ' ', $item['facility_tipe']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Lokasi -->
                                    <td class="py-4 px-4 font-medium text-slate-700">
                                        {{ $item['facility_lokasi'] }}
                                    </td>

                                    <!-- Total Laporan -->
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black {{ $item['total_laporan'] > 0 ? 'bg-rose-100 text-rose-800 border border-rose-300' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                            {{ $item['total_laporan'] }}
                                        </span>
                                    </td>

                                    <!-- Kerusakan Fisik -->
                                    <td class="py-4 px-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $item['kerusakan'] > 0 ? 'bg-red-50 text-red-700 border border-red-200' : 'text-slate-400' }}">
                                            {{ $item['kerusakan'] }}
                                        </span>
                                    </td>

                                    <!-- Kebersihan -->
                                    <td class="py-4 px-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $item['kebersihan'] > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'text-slate-400' }}">
                                            {{ $item['kebersihan'] }}
                                        </span>
                                    </td>

                                    <!-- Lainnya -->
                                    <td class="py-4 px-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $item['lainnya'] > 0 ? 'bg-slate-100 text-slate-700 border border-slate-200' : 'text-slate-400' }}">
                                            {{ $item['lainnya'] }}
                                        </span>
                                    </td>

                                    <!-- Status Selesai / Belum Selesai -->
                                    <td class="py-4 px-5 text-center">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 border border-slate-200 text-xs">
                                            <span class="font-bold text-emerald-700">{{ $item['selesai'] }} Selesai</span>
                                            <span class="text-slate-300">/</span>
                                            <span class="font-semibold text-rose-700">{{ $item['belum_selesai'] }} Belum</span>
                                        </div>
                                    </td>

                                    <!-- % Resolusi -->
                                    <td class="py-4 px-4 text-center">
                                        <div class="inline-flex items-center gap-1.5">
                                            <span class="text-xs font-black {{ $item['tingkat_penyelesaian'] >= 75 ? 'text-emerald-700' : ($item['tingkat_penyelesaian'] >= 50 ? 'text-amber-700' : ($item['total_laporan'] > 0 ? 'text-rose-700' : 'text-slate-400')) }}">
                                                {{ $item['tingkat_penyelesaian'] }}%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 px-4 text-center">
                                        <div class="max-w-xs mx-auto text-slate-500 space-y-2">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-100 mx-auto flex items-center justify-center text-slate-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <p class="font-bold text-slate-700">Tidak ada riwayat kerusakan</p>
                                            <p class="text-xs text-slate-500">Tidak terdapat laporan kendala fasilitas pada rentang tanggal yang dipilih.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sub-Tabel B: Frekuensi Kerusakan per Lokasi/Gedung Kampus -->
            <div class="rounded-3xl bg-white/65 backdrop-blur-xl border border-white/60 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.06)]">
                <div class="px-6 py-5 border-b border-white/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">
                            Sub-tabel B: Frekuensi Kerusakan per Lokasi/Gedung Kampus
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Sebaran agregat titik insiden berdasarkan kluster gedung fakultas dan fasilitas umum kampus
                        </p>
                    </div>
                    <div class="text-xs font-semibold px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 self-start sm:self-auto">
                        {{ $locationDamageData->count() }} Gedung / Lokasi
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900/5 text-slate-600 text-[11px] font-extrabold uppercase tracking-wider border-b border-white/60">
                                <th class="py-3.5 px-5">Gedung / Lokasi</th>
                                <th class="py-3.5 px-4 text-center">Total Insiden</th>
                                <th class="py-3.5 px-4 text-center">Terselesaikan</th>
                                <th class="py-3.5 px-5 min-w-[200px]">% Resolusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/50 text-xs sm:text-sm text-slate-700">
                            @forelse($locationDamageData as $item)
                                @php
                                    $resolusiPercent = $item['tingkat_penyelesaian'];
                                @endphp
                                <tr data-testid="location-{{ \Illuminate\Support\Str::slug($item['lokasi']) }}"
                                    class="hover:bg-white/50 transition-colors">
                                    <!-- Gedung / Lokasi -->
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                            <span class="font-extrabold text-slate-800">
                                                {{ $item['lokasi'] }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Total Insiden -->
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black {{ $item['total_laporan'] > 0 ? 'bg-rose-100 text-rose-800 border border-rose-300' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                            {{ $item['total_laporan'] }} insiden
                                        </span>
                                    </td>

                                    <!-- Terselesaikan -->
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $item['selesai'] > 0 ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                            {{ $item['selesai'] }} selesai
                                        </span>
                                    </td>

                                    <!-- % Resolusi -->
                                    <td class="py-4 px-5">
                                        <div class="space-y-1.5">
                                            <div class="flex items-center justify-between text-xs font-bold text-slate-800">
                                                <span>{{ $resolusiPercent }}%</span>
                                                <span class="text-[11px] text-slate-500 font-semibold">{{ $item['selesai'] }}/{{ $item['total_laporan'] }}</span>
                                            </div>
                                            <div class="w-full h-2 rounded-full bg-slate-200/80 overflow-hidden p-0.5">
                                                <div class="h-full rounded-full {{ $resolusiPercent >= 75 ? 'bg-emerald-600' : ($resolusiPercent >= 50 ? 'bg-amber-500' : 'bg-rose-500') }} transition-all duration-500"
                                                     style="width: {{ $resolusiPercent }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 px-4 text-center">
                                        <div class="max-w-xs mx-auto text-slate-500 space-y-2">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-100 mx-auto flex items-center justify-center text-slate-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                            <p class="font-bold text-slate-700">Tidak ada riwayat per gedung</p>
                                            <p class="text-xs text-slate-500">Tidak ada insiden tercatat pada kluster gedung manapun pada periode ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- ==========================================
     JAVASCRIPT: TAB TOGGLING & DATE PRESET
     ========================================== -->
<script>
    /**
     * Switch interactive tabs with instant DOM toggle and URL state update.
     */
    function switchTab(tabName, updateUrl = true) {
        const okupansiBtn = document.getElementById('tab-btn-okupansi');
        const kerusakanBtn = document.getElementById('tab-btn-kerusakan');
        const okupansiPanel = document.getElementById('tab-panel-okupansi');
        const kerusakanPanel = document.getElementById('tab-panel-kerusakan');
        const filterTabInput = document.getElementById('filter-tab-input');

        if (!okupansiBtn || !kerusakanBtn || !okupansiPanel || !kerusakanPanel) {
            return;
        }

        const activeClasses = ['bg-white', 'text-[#0F5143]', 'shadow-sm', 'border', 'border-white/90'];
        const inactiveClasses = ['text-slate-600', 'hover:text-slate-900', 'hover:bg-white/40'];

        if (tabName === 'kerusakan') {
            // Activate Kerusakan
            okupansiPanel.classList.add('hidden');
            okupansiPanel.classList.remove('block');
            kerusakanPanel.classList.remove('hidden');
            kerusakanPanel.classList.add('block');

            kerusakanBtn.setAttribute('aria-selected', 'true');
            okupansiBtn.setAttribute('aria-selected', 'false');

            kerusakanBtn.classList.add(...activeClasses);
            kerusakanBtn.classList.remove(...inactiveClasses);
            okupansiBtn.classList.remove(...activeClasses);
            okupansiBtn.classList.add(...inactiveClasses);

            const iconKerusakan = kerusakanBtn.querySelector('svg');
            const iconOkupansi = okupansiBtn.querySelector('svg');
            if (iconKerusakan) iconKerusakan.classList.replace('text-slate-500', 'text-[#0F5143]');
            if (iconOkupansi) iconOkupansi.classList.replace('text-[#0F5143]', 'text-slate-500');

            if (filterTabInput) filterTabInput.value = 'kerusakan';
        } else {
            // Activate Okupansi
            kerusakanPanel.classList.add('hidden');
            kerusakanPanel.classList.remove('block');
            okupansiPanel.classList.remove('hidden');
            okupansiPanel.classList.add('block');

            okupansiBtn.setAttribute('aria-selected', 'true');
            kerusakanBtn.setAttribute('aria-selected', 'false');

            okupansiBtn.classList.add(...activeClasses);
            okupansiBtn.classList.remove(...inactiveClasses);
            kerusakanBtn.classList.remove(...activeClasses);
            kerusakanBtn.classList.add(...inactiveClasses);

            const iconKerusakan = kerusakanBtn.querySelector('svg');
            const iconOkupansi = okupansiBtn.querySelector('svg');
            if (iconOkupansi) iconOkupansi.classList.replace('text-slate-500', 'text-[#0F5143]');
            if (iconKerusakan) iconKerusakan.classList.replace('text-[#0F5143]', 'text-slate-500');

            if (filterTabInput) filterTabInput.value = 'okupansi';
        }

        // Update URL query parameter without page reload
        if (updateUrl && window.history && window.history.replaceState) {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabName);
            window.history.replaceState({ tab: tabName }, '', url.toString());

            // Update export links to preserve active tab
            document.querySelectorAll('.export-action-link').forEach(link => {
                try {
                    const linkUrl = new URL(link.href, window.location.origin);
                    linkUrl.searchParams.set('tab', tabName);
                    link.href = linkUrl.toString();
                } catch (e) {}
            });
        }
    }

    /**
     * Terapkan preset rentang tanggal cepat (bulan_ini, 30_hari, semua).
     */
    function applyPreset(presetName) {
        const presetInput = document.getElementById('filter-preset-input');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const form = document.getElementById('date-filter-form');

        if (!form) return;

        if (presetInput) presetInput.value = presetName;

        // Clear custom inputs when switching to predefined preset
        if (presetName === 'semua' && startDateInput && endDateInput) {
            startDateInput.value = '';
            endDateInput.value = '';
        }

        form.submit();
    }

    // Listen to browser navigation back/forward
    window.addEventListener('popstate', function (event) {
        const url = new URL(window.location.href);
        const tab = url.searchParams.get('tab') || 'okupansi';
        switchTab(tab, false);
    });
</script>
@endsection
