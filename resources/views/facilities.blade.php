@extends('layouts.app')

@section('title', 'Katalog Fasilitas Kampus')
@section('page-title', 'Katalog & Ketersediaan Fasilitas')

@section('content')
<div class="space-y-6">

    {{-- WELCOME & VALUE BANNER --}}
    <div class="p-6 rounded-2xl bg-white dark:bg-[#0b171c] border border-slate-200 dark:border-teal-950/70 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-50 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/60 dark:border-teal-800/40 mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
                <span>Jam Operasional Kampus: 07.00 – 20.00 WIB</span>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Katalog Sarana & Prasarana Kampus
            </h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400 max-w-2xl">
                Pantau ketersediaan ruangan per slot 30 menit secara real-time. Temukan ruang kelas, laboratorium, aula, dan perlengkapan perkuliahan.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            @auth
                <a href="{{ route('reservation') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-teal-700 hover:bg-teal-800 dark:bg-teal-600 dark:hover:bg-teal-500 shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Ajukan Reservasi</span>
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/50 hover:bg-teal-100 dark:hover:bg-teal-900/40 border border-teal-200 dark:border-teal-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>Masuk untuk Meminjam</span>
                </a>
            @endauth
        </div>
    </div>

    {{-- RINGKASAN METRIK STATUS FASILITAS --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        {{-- Total --}}
        <div class="p-4 rounded-xl bg-white dark:bg-[#0b171c] border border-slate-200 dark:border-teal-950/70 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Total Sarana</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $stats['total'] }}</span>
                <span class="text-xs text-slate-400">Unit</span>
            </div>
        </div>

        {{-- Aktif --}}
        <div class="p-4 rounded-xl bg-white dark:bg-[#0b171c] border border-slate-200 dark:border-teal-950/70 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Siap Dipakai</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            </div>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $stats['aktif'] }}</span>
                <span class="text-xs text-emerald-700/70 dark:text-emerald-500">Tersedia</span>
            </div>
        </div>

        {{-- Dalam Perbaikan --}}
        <div class="p-4 rounded-xl bg-white dark:bg-[#0b171c] border border-slate-200 dark:border-teal-950/70 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-amber-700 dark:text-amber-400 uppercase tracking-wider">Pemeliharaan</span>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            </div>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $stats['dalam_perbaikan'] }}</span>
                <span class="text-xs text-amber-700/70 dark:text-amber-500">Perbaikan</span>
            </div>
        </div>

        {{-- Nonaktif --}}
        <div class="p-4 rounded-xl bg-white dark:bg-[#0b171c] border border-slate-200 dark:border-teal-950/70 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nonaktif</span>
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
            </div>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-slate-600 dark:text-slate-400">{{ $stats['nonaktif'] }}</span>
                <span class="text-xs text-slate-400">Tutup</span>
            </div>
        </div>
    </div>

    {{-- SEARCH & MULTI-FILTER FORM (US 2) --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-[#0b171c] border border-slate-200 dark:border-teal-950/70 shadow-xs">
        <form method="GET" action="{{ route('facilities') }}" class="space-y-4">
            {{-- Search Bar Input --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama ruangan, gedung, alat laboratorium, atau kata kunci deskripsi..."
                    class="block w-full pl-11 pr-24 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
                >
                <div class="absolute inset-y-0 right-1.5 flex items-center">
                    <button type="submit"
                            class="px-4 py-1.5 rounded-lg text-xs font-semibold text-white bg-teal-700 hover:bg-teal-800 dark:bg-teal-600 transition-colors">
                        Cari
                    </button>
                </div>
            </div>

            {{-- Multi-Criteria Filters --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2">
                {{-- Filter Tanggal (US 1) --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                        Tanggal Peninjauan:
                    </label>
                    <input
                        type="date"
                        name="tanggal"
                        value="{{ $selectedDate }}"
                        onchange="this.form.submit()"
                        class="block w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs focus:ring-teal-500 focus:border-teal-500"
                    >
                </div>

                {{-- Filter Tipe --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                        Kategori Fasilitas:
                    </label>
                    <select name="tipe" onchange="this.form.submit()"
                            class="block w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Semua Kategori</option>
                        <option value="ruang_kelas" {{ request('tipe') === 'ruang_kelas' ? 'selected' : '' }}>Ruang Kelas</option>
                        <option value="laboratorium" {{ request('tipe') === 'laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                        <option value="aula" {{ request('tipe') === 'aula' ? 'selected' : '' }}>Aula</option>
                        <option value="lapangan" {{ request('tipe') === 'lapangan' ? 'selected' : '' }}>Lapangan</option>
                        <option value="alat" {{ request('tipe') === 'alat' ? 'selected' : '' }}>Alat / Perangkat</option>
                    </select>
                </div>

                {{-- Filter Lokasi --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                        Lokasi Gedung:
                    </label>
                    <select name="lokasi" onchange="this.form.submit()"
                            class="block w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Semua Lokasi</option>
                        @foreach ($availableLocations as $loc)
                            <option value="{{ $loc }}" {{ request('lokasi') === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Kapasitas --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                        Kapasitas Ruangan:
                    </label>
                    <select name="kapasitas" onchange="this.form.submit()"
                            class="block w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Semua Kapasitas</option>
                        <option value="< 30 orang" {{ request('kapasitas') === '< 30 orang' ? 'selected' : '' }}>&lt; 30 orang</option>
                        <option value="30 - 50 orang" {{ request('kapasitas') === '30 - 50 orang' ? 'selected' : '' }}>30 - 50 orang</option>
                        <option value="> 50 orang" {{ request('kapasitas') === '> 50 orang' ? 'selected' : '' }}>&gt; 50 orang</option>
                    </select>
                </div>
            </div>

            @if (request()->hasAny(['search', 'tipe', 'lokasi', 'kapasitas']) && (request('search') || request('tipe') || request('lokasi') || request('kapasitas')))
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800 text-xs">
                    <span class="text-slate-500 dark:text-slate-400">
                        Filter aktif diterapkan
                    </span>
                    <a href="{{ route('facilities') }}" class="font-semibold text-rose-600 hover:text-rose-700 dark:text-rose-400">
                        ✕ Reset Semua Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- DAFTAR KARTU FASILITAS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($facilities as $facility)
            <div class="rounded-2xl border border-slate-200 dark:border-teal-950/70 bg-white dark:bg-[#0b171c] shadow-xs flex flex-col justify-between hover:border-teal-500/50 dark:hover:border-teal-600/50 hover:shadow-md transition-all">
                {{-- Card Header --}}
                <div class="p-5 pb-4 border-b border-slate-100 dark:border-slate-800/80">
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold tracking-wide uppercase bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                            {{ str_replace('_', ' ', $facility->tipe) }}
                        </span>

                        @if ($facility->status === 'aktif')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Aktif
                            </span>
                        @elseif ($facility->status === 'dalam_perbaikan')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Dalam Perbaikan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Nonaktif
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight">
                        {{ $facility->nama }}
                    </h3>

                    <div class="mt-2.5 space-y-1.5 text-xs text-slate-500 dark:text-slate-400">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="truncate">{{ $facility->lokasi }}</span>
                        </p>
                        @if ($facility->kapasitas)
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Kapasitas: <strong class="text-slate-700 dark:text-slate-300 font-semibold">{{ $facility->kapasitas }}</strong> orang</span>
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Card Body: Status Ketersediaan Slot --}}
                <div class="p-5 pt-4 flex-1 flex flex-col justify-between">
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/70 dark:border-slate-800 mb-4">
                        @if ($facility->status === 'dalam_perbaikan')
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400 block">Status Pemeliharaan</span>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 mt-0.5 block">Sedang diperbaiki oleh teknisi</span>
                        @elseif ($facility->status === 'nonaktif')
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">Status Operasional</span>
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-0.5 block">Fasilitas ditutup sementara</span>
                        @elseif ($facility->is_fully_booked)
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-rose-600 dark:text-rose-400 block">Ketersediaan Hari Ini</span>
                            <span class="text-xs font-bold text-rose-700 dark:text-rose-300 mt-0.5 block">Semua slot sudah terpesan penuh</span>
                        @else
                            <div class="flex items-center justify-between text-[11px] font-medium text-slate-500 dark:text-slate-400">
                                <span>Slot berikutnya:</span>
                                <span class="text-emerald-700 dark:text-emerald-400 font-semibold">{{ $facility->available_slots_count }}/{{ $facility->total_slots }} Tersedia</span>
                            </div>
                            <span class="text-xs font-mono font-bold text-emerald-700 dark:text-emerald-400 mt-1 block">
                                {{ $facility->next_available_slot ?? '07.00 - 07.30' }} WIB
                            </span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2">
                        <button type="button"
                                onclick="openScheduleModal({{ $facility->id }}, '{{ addslashes($facility->nama) }}', '{{ ucfirst(str_replace('_', ' ', $facility->tipe)) }}', '{{ addslashes($facility->lokasi) }}', '{{ $facility->status }}')"
                                class="flex-1 py-2 px-3 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition-colors text-center cursor-pointer">
                            Cek Ketersediaan
                        </button>

                        @auth
                            @if ($facility->status === 'aktif')
                                <a href="{{ route('reservation', ['facility_id' => $facility->id]) }}"
                                   class="py-2 px-3.5 rounded-xl text-xs font-bold text-white bg-teal-700 hover:bg-teal-800 dark:bg-teal-600 dark:hover:bg-teal-500 shadow-xs transition-colors shrink-0">
                                    + Pinjam
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white dark:bg-[#0b171c] rounded-2xl border border-dashed border-slate-300 dark:border-slate-800">
                <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h4 class="font-bold text-slate-900 dark:text-white text-base">Tidak ada fasilitas ditemukan</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">
                    Kriteria pencarian Anda tidak cocok dengan fasilitas mana pun. Silakan coba kata kunci lain.
                </p>
                <a href="{{ route('facilities') }}" class="inline-block mt-4 text-xs font-bold text-teal-700 dark:text-teal-400 hover:underline">
                    Reset Filter Pencarian
                </a>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="pt-2">
        {{ $facilities->links() }}
    </div>

</div>

{{-- MODAL MATRIKS WAKTU 26 SLOT 30 MENIT (TIME-GROUPED MATRIX) --}}
<div id="scheduleModal"
     class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs hidden items-center justify-center p-4 overflow-y-auto">
    <div class="w-full max-w-3xl bg-white dark:bg-[#0b171c] rounded-2xl border border-slate-200 dark:border-teal-950/80 shadow-2xl flex flex-col max-h-[90vh] my-auto overflow-hidden">
        {{-- Modal Header --}}
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span id="modalFacilityType" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-teal-50 dark:bg-teal-950/50 text-teal-800 dark:text-teal-300"></span>
                    <span id="modalFacilityLocation" class="text-xs text-slate-500 dark:text-slate-400"></span>
                </div>
                <h3 id="modalFacilityName" class="text-xl font-black text-slate-900 dark:text-white tracking-tight"></h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Ketersediaan jadwal per slot 30 menit pada tanggal <strong id="modalDateLabel" class="text-slate-800 dark:text-slate-200 font-semibold">{{ \Illuminate\Support\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</strong>
                </p>
            </div>

            <button type="button" onclick="closeScheduleModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Modal Content Body --}}
        <div class="p-6 overflow-y-auto space-y-6">
            {{-- Legend Indicator --}}
            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-800 text-xs">
                <span class="font-medium text-slate-600 dark:text-slate-400">Petunjuk Status:</span>
                <div class="flex items-center gap-4 font-semibold">
                    <span class="flex items-center gap-1.5 text-emerald-700 dark:text-emerald-400">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Tersedia
                    </span>
                    <span class="flex items-center gap-1.5 text-rose-700 dark:text-rose-400">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Tidak Tersedia
                    </span>
                </div>
            </div>

            {{-- Loading State --}}
            <div id="slotsLoading" class="py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                <svg class="animate-spin w-6 h-6 mx-auto mb-2 text-teal-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memuat data 26 slot waktu operasional...</span>
            </div>

            {{-- Time-Grouped Matrix Container --}}
            <div id="slotsContainer" class="hidden space-y-5">
                {{-- SESI PAGI (07.00 - 12.00) --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2.5 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Sesi Pagi (07.00 - 12.00 WIB)</span>
                    </h4>
                    <div id="morningSlots" class="grid grid-cols-2 sm:grid-cols-5 gap-2"></div>
                </div>

                {{-- SESI SIANG (12.00 - 16.00) --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2.5 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Sesi Siang (12.00 - 16.00 WIB)</span>
                    </h4>
                    <div id="afternoonSlots" class="grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
                </div>

                {{-- SESI SORE / MALAM (16.00 - 20.00) --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2.5 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <span>Sesi Sore / Malam (16.00 - 20.00 WIB)</span>
                    </h4>
                    <div id="eveningSlots" class="grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
                </div>
            </div>

            {{-- Privacy Notice (US 1 Compliance) --}}
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-800 flex items-start gap-2.5 text-xs text-slate-500 dark:text-slate-400">
                <svg class="w-4 h-4 text-teal-600 dark:text-teal-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>
                    <strong>Privasi Terjamin:</strong> Informasi slot hanya menampilkan ketersediaan waktu tanpa mempublikasikan identitas pemohon atau agenda kegiatan peminjam.
                </span>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="p-4 px-6 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-[#071317] flex items-center justify-between">
            <button type="button" onclick="closeScheduleModal()"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                Tutup
            </button>

            <div id="modalActionContainer"></div>
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
        const morningBox = document.getElementById('morningSlots');
        const afternoonBox = document.getElementById('afternoonSlots');
        const eveningBox = document.getElementById('eveningSlots');
        const actionBox = document.getElementById('modalActionContainer');

        modalName.innerText = name;
        modalType.innerText = type;
        modalLocation.innerText = '⌖ ' + location;

        loading.classList.remove('hidden');
        container.classList.add('hidden');
        morningBox.innerHTML = '';
        afternoonBox.innerHTML = '';
        eveningBox.innerHTML = '';

        @auth
            if (status === 'aktif') {
                actionBox.innerHTML = `
                    <a href="/reservation?facility_id=${facilityId}&tanggal=${selectedDate}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-teal-700 hover:bg-teal-800 dark:bg-teal-600 transition-colors shadow-xs">
                        <span>+ Ajukan Peminjaman</span>
                    </a>
                `;
            } else {
                actionBox.innerHTML = `<span class="text-xs text-amber-600 dark:text-amber-400 font-semibold">Fasilitas tidak dapat dipinjam</span>`;
            }
        @else
            actionBox.innerHTML = `
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-teal-700 hover:bg-teal-800 dark:bg-teal-600 transition-colors shadow-xs">
                    <span>Masuk Akun untuk Meminjam</span>
                </a>
            `;
        @endauth

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        fetch(`/facilities/${facilityId}/schedule?date=${selectedDate}`)
            .then(res => res.json())
            .then(data => {
                loading.classList.add('hidden');
                container.classList.remove('hidden');

                data.slots.forEach(slot => {
                    const chip = document.createElement('div');
                    chip.className = 'p-2 rounded-lg text-center flex flex-col items-center justify-center transition-all';

                    if (slot.is_available) {
                        chip.className += ' bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300';
                        chip.innerHTML = `
                            <span class="text-[11px] font-mono font-bold">${slot.label}</span>
                            <span class="text-[9px] font-semibold text-emerald-600 dark:text-emerald-400 mt-0.5">Tersedia</span>
                        `;
                    } else {
                        chip.className += ' bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 opacity-90';
                        chip.innerHTML = `
                            <span class="text-[11px] font-mono font-bold line-through">${slot.label}</span>
                            <span class="text-[9px] font-semibold text-rose-600 dark:text-rose-400 mt-0.5">${slot.status_label}</span>
                        `;
                    }

                    // Kelompokkan slot berdasarkan jam mulai
                    const startHour = parseInt(slot.start.split(':')[0]);
                    if (startHour < 12) {
                        morningBox.appendChild(chip);
                    } else if (startHour < 16) {
                        afternoonBox.appendChild(chip);
                    } else {
                        eveningBox.appendChild(chip);
                    }
                });
            })
            .catch(() => {
                loading.innerHTML = '<span class="text-rose-600 dark:text-rose-400">Gagal memuat jadwal fasilitas. Silakan coba kembali.</span>';
            });
    }

    function closeScheduleModal() {
        const modal = document.getElementById('scheduleModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    window.onclick = function(event) {
        const modal = document.getElementById('scheduleModal');
        if (event.target === modal) {
            closeScheduleModal();
        }
    };
</script>
@endsection