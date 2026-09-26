@extends('layouts.app')

@section('title', 'Katalog & Ketersediaan Fasilitas')

@push('styles')
<style>
    .kezak-input {
        border: 1.5px solid #94A3B8 !important;
        background: rgba(255, 255, 255, 0.92) !important;
        color: #0F172A !important;
    }
    .kezak-input::placeholder {
        color: #64748B !important;
    }
    .kezak-input:hover {
        border-color: #0F5143 !important;
        background: #FFFFFF !important;
    }
    .kezak-input:focus,
    .kezak-input:focus-within,
    .kezak-input:active,
    select.kezak-input:focus {
        background-color: #E8F8F3 !important;
        border-color: #10B981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25), inset 0 1px 1px rgba(0, 0, 0, 0.02) !important;
        outline: none !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6 sm:space-y-8">

    <!-- ==========================================
         1. HERO / WELCOME BANNER (FROSTED GLASS)
         ========================================== -->
    <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
        <!-- Ambient radial glow inside hero -->
        <div class="absolute -right-20 -top-20 w-60 h-60 rounded-full bg-emerald-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 border border-emerald-500/20 text-[#0F5143] mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Mode Pengunjung</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">
                Katalog & Ketersediaan Fasilitas Kampus
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 max-w-2xl mt-1.5 leading-relaxed">
                Periksa ketersediaan ruangan, laboratorium, aula, dan sarana prasarana.
            </p>
        </div>

        <div class="relative z-10 shrink-0 flex flex-col items-start md:items-end gap-2">
            <div class="px-4 py-2.5 rounded-2xl bg-white/70 backdrop-blur-md border border-white/80 shadow-2xs flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-teal-500/15 text-[#0F5143] flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tanggal Ditinjau</span>
                    <strong class="text-xs sm:text-sm font-extrabold text-slate-800">
                        {{ \Illuminate\Support\Carbon::parse($selectedDate)->translatedFormat('l, d M Y') }}
                    </strong>
                </div>
            </div>
            <div class="inline-flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Jam Operasional: 07.00 - 20.00 WIB</span>
            </div>
        </div>
    </div>

    <!-- ==========================================
         2. TOP 3 KPI SUMMARY CARDS (FROSTED GLASS)
         ========================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <!-- 1. Total Fasilitas -->
        <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-5 sm:p-6 flex items-center justify-between transition-all hover:bg-white/80">
            <div>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Fasilitas</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mt-1">{{ $stats['total'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-teal-500/15 border border-teal-500/20 text-[#0F5143] flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>

        <!-- 2. Siap Digunakan (Aktif) -->
        <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-5 sm:p-6 flex items-center justify-between transition-all hover:bg-white/80">
            <div>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Siap Digunakan (Aktif)</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-800 mt-1">{{ $stats['aktif'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/15 border border-emerald-500/20 text-emerald-700 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- 3. Dalam Perbaikan -->
        <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-5 sm:p-6 flex items-center justify-between transition-all hover:bg-white/80">
            <div>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Dalam Perbaikan</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-rose-700 mt-1">{{ $stats['dalam_perbaikan'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-500/15 border border-rose-500/20 text-rose-700 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- ==========================================
         3. SEARCH & FILTER BAR (FROSTED GLASS)
         ========================================== -->
    <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-5 sm:p-6">
        <form method="GET" action="{{ route('facilities') }}" class="space-y-4">
            <!-- Search Keyword Input with Icon -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#0F5143]">
                    <svg class="w-5 h-5 text-[#0F5143]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama ruang, laboratorium, aula, alat, lapangan, atau lokasi..."
                    class="w-full pl-11 pr-4 py-2.5 text-xs sm:text-sm kezak-input placeholder-slate-500 font-medium"
                >
            </div>

            <!-- Filter Controls Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                <!-- Filter Tanggal -->
                <div class="lg:col-span-3 flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-700 shrink-0">Tanggal:</label>
                    <input
                        type="date"
                        name="tanggal"
                        value="{{ $selectedDate }}"
                        class="w-full py-2 px-3 text-xs sm:text-sm kezak-input font-medium cursor-pointer"
                        onchange="this.form.submit()"
                    >
                </div>

                <!-- Filter Tipe -->
                <div class="lg:col-span-3">
                    <select name="tipe" class="w-full py-2 px-3 text-xs sm:text-sm kezak-input font-medium cursor-pointer" onchange="this.form.submit()">
                        <option value="">Semua Tipe Fasilitas</option>
                        <option value="ruang_kelas" @selected(request('tipe') === 'ruang_kelas')>Ruang Kelas</option>
                        <option value="laboratorium" @selected(request('tipe') === 'laboratorium')>Laboratorium</option>
                        <option value="aula" @selected(request('tipe') === 'aula')>Aula</option>
                        <option value="lapangan" @selected(request('tipe') === 'lapangan')>Lapangan</option>
                        <option value="alat" @selected(request('tipe') === 'alat')>Alat / Perangkat</option>
                    </select>
                </div>

                <!-- Filter Lokasi -->
                <div class="lg:col-span-2">
                    <select name="lokasi" class="w-full py-2 px-3 text-xs sm:text-sm kezak-input font-medium cursor-pointer" onchange="this.form.submit()">
                        <option value="">Semua Lokasi</option>
                        @foreach ($availableLocations as $loc)
                            <option value="{{ $loc }}" @selected(request('lokasi') === $loc)>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kapasitas -->
                <div class="lg:col-span-2">
                    <select name="kapasitas" class="w-full py-2 px-3 text-xs sm:text-sm kezak-input font-medium cursor-pointer" onchange="this.form.submit()">
                        <option value="">Semua Kapasitas</option>
                        <option value="< 30 orang" @selected(request('kapasitas') === '< 30 orang')>&lt; 30 orang</option>
                        <option value="30 - 50 orang" @selected(request('kapasitas') === '30 - 50 orang')>30 - 50 orang</option>
                        <option value="> 50 orang" @selected(request('kapasitas') === '> 50 orang')>&gt; 50 orang</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 px-3 kezak-btn-primary text-white text-xs sm:text-sm font-bold shadow-xs text-center cursor-pointer">
                        Cari
                    </button>
                    @if (request()->hasAny(['search', 'tipe', 'lokasi', 'kapasitas', 'tanggal']))
                        <a href="{{ route('facilities') }}" class="px-3 py-2 rounded-xl bg-white/70 hover:bg-rose-50 border border-rose-200 text-rose-600 text-xs sm:text-sm font-semibold transition-all shadow-2xs text-center" title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- ==========================================
         4. CATALOG HEADER & FACILITY CARDS GRID
         ========================================== -->
    <div class="space-y-4">
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-800">
                    Daftar Fasilitas & Jadwal Ketersediaan
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">
                    Setiap fasilitas memiliki 26 slot waktu (07.00 - 20.00 WIB). Klik tombol untuk memeriksa detail tiap slot.
                </p>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-white/70 backdrop-blur-md border border-white/80 text-slate-600 shadow-2xs">
                    Menampilkan <strong class="text-slate-800 font-bold">{{ $facilities->count() }}</strong> dari <strong class="text-slate-800 font-bold">{{ $facilities->total() }}</strong> fasilitas
                </span>
            </div>
        </div>

        <!-- Facility Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($facilities as $facility)
                <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:shadow-[0_16px_40px_rgba(15,81,67,0.12)] hover:-translate-y-1 rounded-3xl p-5 sm:p-6 flex flex-col justify-between transition-all duration-300 group">
                    
                    <!-- Card Upper Area -->
                    <div>
                        <!-- Header Row: Icon & Status Badge -->
                        <div class="flex items-center justify-between gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-teal-500/15 border border-teal-500/20 text-[#0F5143] flex items-center justify-center shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                                <x-facility-icon :tipe="$facility->tipe" class="w-6 h-6 text-[#0F5143]" />
                            </div>

                            <div>
                                @if ($facility->status === 'dalam_perbaikan')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-500/15 border border-rose-500/20 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Dalam Perbaikan
                                    </span>
                                @elseif ($facility->status === 'nonaktif')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-500/15 border border-slate-500/20 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                @elseif ($facility->is_fully_booked)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 border border-amber-500/20 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Penuh (0/26 Slot)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 border border-emerald-500/20 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Tersedia ({{ $facility->available_slots_count }}/26 Slot)
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Category Tag -->
                        <span class="text-[11px] font-bold text-teal-700 uppercase tracking-wider mt-4 block">
                            {{ ucwords(str_replace('_', ' ', $facility->tipe)) }}
                        </span>

                        <!-- Facility Name -->
                        <h3 class="text-lg font-extrabold text-slate-800 group-hover:text-[#0F5143] transition-colors mt-0.5 line-clamp-1" title="{{ $facility->nama }}">
                            {{ $facility->nama }}
                        </h3>

                        <!-- Meta Pills (Lokasi & Kapasitas) -->
                        <div class="flex flex-wrap items-center gap-2 mt-2.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-medium bg-white/70 border border-white/90 text-slate-600 shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $facility->lokasi }}</span>
                            </span>

                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-medium bg-white/70 border border-white/90 text-slate-600 shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>{{ $facility->kapasitas ? $facility->kapasitas . ' orang' : 'Fleksibel' }}</span>
                            </span>
                        </div>

                        <!-- Description Snippet -->
                        @if ($facility->deskripsi)
                            <p class="text-xs text-slate-500 mt-2.5 line-clamp-2 leading-relaxed" title="{{ $facility->deskripsi }}">
                                {{ $facility->deskripsi }}
                            </p>
                        @endif
                    </div>

                    <!-- Card Lower Area: Status & Action -->
                    <div class="border-t border-white/70 pt-4 mt-4">
                        <!-- Slot Status Box -->
                        <div class="p-3 rounded-2xl bg-white/50 border border-white/80 flex items-center justify-between mb-3 text-xs shadow-2xs">
                            @if ($facility->status === 'dalam_perbaikan')
                                <span class="text-slate-500 font-medium">Status Pemeliharaan</span>
                                <span class="text-rose-600 font-bold">Sedang perbaikan teknisi</span>
                            @elseif ($facility->status === 'nonaktif')
                                <span class="text-slate-500 font-medium">Status Fasilitas</span>
                                <span class="text-slate-500 font-bold">Tidak aktif sementara</span>
                            @elseif ($facility->next_available_slot)
                                <span class="text-slate-500 font-medium">Slot berikutnya ({{ \Illuminate\Support\Carbon::parse($selectedDate)->translatedFormat('d M') }})</span>
                                <span class="text-emerald-700 font-extrabold flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $facility->next_available_slot }}
                                </span>
                            @else
                                <span class="text-slate-500 font-medium">Status Slot</span>
                                <span class="text-rose-600 font-bold">Semua slot sudah dipesan</span>
                            @endif
                        </div>

                        <!-- Modal Trigger Button -->
                        <button
                            type="button"
                            onclick="openScheduleModal({{ $facility->id }}, '{{ addslashes($facility->nama) }}', '{{ ucwords(str_replace('_', ' ', $facility->tipe)) }}', '{{ addslashes($facility->lokasi) }}', '{{ $facility->status }}')"
                            class="w-full py-2.5 px-4 kezak-btn-primary text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Cek 26 Slot Waktu</span>
                        </button>
                    </div>

                </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-full bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-10 sm:p-14 text-center">
                    <div class="w-16 h-16 rounded-3xl bg-teal-500/15 border border-teal-500/20 text-[#0F5143] mx-auto flex items-center justify-center mb-4 shadow-2xs">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-800">Tidak ada fasilitas yang sesuai</h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                        Coba ubah kata kunci pencarian atau sesuaikan filter tipe, lokasi, dan kapasitas fasilitas.
                    </p>
                    <div class="mt-5">
                        <a href="{{ route('facilities') }}" class="inline-flex items-center gap-2 px-4 py-2.5 kezak-btn-primary text-white text-xs sm:text-sm font-bold shadow-xs">
                            Tampilkan Semua Fasilitas
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($facilities->hasPages())
            <div class="pt-4">
                {{ $facilities->links() }}
            </div>
        @endif
    </div>

</div>

<!-- ==========================================
     5. MODAL DETAIL 26 SLOT WAKTU (FROSTED GLASS)
     ========================================== -->
<div id="scheduleModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm p-4 overflow-y-auto flex items-center justify-center" style="display: none;">
    <div class="bg-white/90 backdrop-blur-2xl border border-white/80 rounded-[28px] shadow-[0_25px_60px_rgba(0,0,0,0.22)] w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal Header -->
        <div class="p-5 sm:p-6 border-b border-white/60 bg-white/70 backdrop-blur-md flex items-start justify-between sticky top-0 z-10">
            <div>
                <span id="modalFacilityType" class="text-[11px] font-bold text-teal-700 uppercase tracking-wider block"></span>
                <h3 id="modalFacilityName" class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-0.5"></h3>
                <p id="modalFacilityLocation" class="text-xs text-slate-500 mt-1 flex items-center gap-1"></p>
            </div>
            <button type="button" onclick="closeScheduleModal()" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center transition-colors cursor-pointer" title="Tutup Dialog">
                ✕
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 sm:p-6 overflow-y-auto space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-white/60">
                <div>
                    <h4 class="text-sm font-extrabold text-slate-800">Jadwal Slot 30 Menit (26 Slot)</h4>
                    <span class="text-xs text-slate-500">Tanggal: <strong id="modalDateLabel" class="text-slate-700 font-bold">{{ \Illuminate\Support\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</strong></span>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="inline-flex items-center gap-1.5 font-semibold text-emerald-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Tersedia
                    </span>
                    <span class="inline-flex items-center gap-1.5 font-semibold text-rose-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Tidak Tersedia
                    </span>
                </div>
            </div>

            <!-- Loading State -->
            <div id="slotsLoading" class="py-12 text-center text-xs text-slate-500">
                <div class="w-8 h-8 rounded-full border-2 border-teal-600 border-t-transparent animate-spin mx-auto mb-2"></div>
                Memuat data ketersediaan 26 slot...
            </div>

            <!-- Slots Grid -->
            <div id="slotsContainer" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5" style="display: none;">
                <!-- Diisi via JavaScript -->
            </div>

            <!-- Privacy Notice (US 1) -->
            <div class="p-3.5 rounded-2xl bg-white/60 border border-white/80 text-xs text-slate-600 flex items-start gap-2.5 shadow-2xs">
                <svg class="w-4 h-4 text-teal-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span class="leading-relaxed">
                    Sesuai kebijakan kampus, status slot hanya menampilkan informasi ketersediaan tanpa memuat identitas pemesan maupun tujuan reservasi.
                </span>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 sm:p-5 border-t border-white/60 bg-white/70 backdrop-blur-md flex items-center justify-between">
            <button type="button" onclick="closeScheduleModal()" class="px-4 py-2 rounded-xl bg-white/80 hover:bg-white border border-slate-200 text-slate-700 text-xs sm:text-sm font-semibold transition-all shadow-2xs cursor-pointer">
                Tutup
            </button>

            @auth
                <a href="{{ route('reservation') }}" class="inline-flex items-center gap-1.5 px-4 py-2 kezak-btn-primary text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow-md transition-all">
                    <span>+ Ajukan Reservasi</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 kezak-btn-primary text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow-md transition-all">
                    <span>Masuk Akun untuk Reservasi →</span>
                </a>
            @endauth
        </div>
    </div>
</div>

<script>
    const selectedDate = '{{ $selectedDate }}';

    function openScheduleModal(facilityId, name, type, location, status) {
        const modal = document.getElementById('scheduleModal');
        const modalName = document.getElementById('modalFacilityName');
        const modalType = document.getElementById('modalFacilityType');
        const modalLocation = document.getElementById('modalFacilityLocation');
        const loading = document.getElementById('slotsLoading');
        const container = document.getElementById('slotsContainer');

        modalName.innerText = name;
        modalType.innerText = type;
        modalLocation.innerHTML = `<svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> <span>${location}</span>`;

        loading.style.display = 'block';
        container.style.display = 'none';
        container.innerHTML = '';
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        fetch(`/facilities/${facilityId}/schedule?date=${selectedDate}`)
            .then(res => res.json())
            .then(data => {
                loading.style.display = 'none';
                container.style.display = 'grid';

                data.slots.forEach(slot => {
                    const card = document.createElement('div');
                    card.className = slot.is_available
                        ? 'p-2.5 rounded-xl text-center bg-emerald-50/90 border border-emerald-200/90 text-emerald-800 shadow-2xs flex flex-col justify-center'
                        : 'p-2.5 rounded-xl text-center bg-rose-50/80 border border-rose-200/80 text-rose-700 shadow-2xs opacity-85 flex flex-col justify-center';

                    if (slot.is_available) {
                        card.innerHTML = `<span class="font-bold text-xs text-slate-800">${slot.label}</span><span class="text-[10px] text-emerald-700 font-bold mt-0.5">Tersedia</span>`;
                    } else {
                        card.innerHTML = `<span class="font-bold text-xs text-slate-500 line-through">${slot.label}</span><span class="text-[10px] text-rose-700 font-bold mt-0.5">${slot.status_label}</span>`;
                    }

                    container.appendChild(card);
                });
            })
            .catch(err => {
                loading.innerText = 'Gagal memuat jadwal slot. Silakan coba lagi.';
            });
    }

    function closeScheduleModal() {
        const modal = document.getElementById('scheduleModal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('scheduleModal');
        if (event.target === modal) {
            closeScheduleModal();
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeScheduleModal();
        }
    });
</script>
@endsection