@extends('layouts.petugas')

@section('title', 'Laporan Kendala Fasilitas')
@section('header_title', 'Antrean & Resolusi Laporan Fasilitas')
@section('header_subtitle', 'Tinjau keluhan kerusakan, lakukan investigasi lapangan, perbarui status resolusi, dan atur ketersediaan fasilitas')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- ==========================================
         1. HEADER SECTION (FROSTED HERO BANNER)
         ========================================== --}}
    <div class="bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-60 h-60 rounded-full bg-emerald-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 border border-emerald-500/20 text-[#0F5143] dark:text-[#34D399] mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Modul Pelaporan & Resolusi Kerusakan — US 8, 11, 12</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                Antrean & Penanganan Kendala
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-300 max-w-xl leading-relaxed">
                Tindak lanjuti laporan kerusakan dari sivitas akademika. Periksa bukti visual, catat progres investigasi, serta sesuaikan status fasilitas jika memerlukan pemeliharaan intensif.
            </p>
        </div>

        {{-- Quick Summary Counters --}}
        <div class="relative z-10 flex flex-wrap items-center gap-2.5 shrink-0">
            <div class="px-4 py-2.5 rounded-2xl bg-white/70 dark:bg-white/10 backdrop-blur-md border border-white/80 dark:border-white/15 text-center shadow-xs">
                <span class="block text-[11px] uppercase tracking-wider text-rose-600 dark:text-rose-400 font-bold">Laporan Baru</span>
                <span class="text-2xl font-extrabold text-rose-600 dark:text-rose-400">{{ $counts['baru'] }}</span>
            </div>
            <div class="px-4 py-2.5 rounded-2xl bg-white/70 dark:bg-white/10 backdrop-blur-md border border-white/80 dark:border-white/15 text-center shadow-xs">
                <span class="block text-[11px] uppercase tracking-wider text-amber-600 dark:text-amber-400 font-bold">Diproses</span>
                <span class="text-2xl font-extrabold text-amber-600 dark:text-amber-400">{{ $counts['diproses'] }}</span>
            </div>
            <div class="px-4 py-2.5 rounded-2xl bg-white/70 dark:bg-white/10 backdrop-blur-md border border-white/80 dark:border-white/15 text-center shadow-xs">
                <span class="block text-[11px] uppercase tracking-wider text-emerald-600 dark:text-emerald-400 font-bold">Selesai</span>
                <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $counts['selesai'] }}</span>
            </div>
        </div>
    </div>

    {{-- ALERT VALIDASI / ERROR --}}
    @if ($errors->any())
        <div class="p-5 rounded-2xl bg-rose-500/15 backdrop-blur-xl border border-rose-400/40 text-rose-950 dark:text-rose-200 text-xs shadow-xs space-y-1.5">
            <strong class="font-bold block text-sm mb-1">Terdapat kesalahan pengisian data:</strong>
            <ul class="list-disc pl-5 space-y-0.5 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ==========================================
         2. FILTER & SEARCH CONTROLS (FROSTED CARD)
         ========================================== --}}
    <div class="p-6 rounded-3xl bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-xs space-y-5">
        
        {{-- Status Filter Tabs --}}
        <div class="flex flex-wrap items-center gap-2 border-b border-white/40 dark:border-white/10 pb-4">
            <a href="{{ route('petugas.reports.index', array_merge(request()->except('page'), ['status' => 'baru'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'baru' ? 'bg-[#0F5143] text-white shadow-md' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5' }}">
                <span>Laporan Baru</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'baru' ? 'bg-white text-[#0F5143]' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-200 border border-rose-300 dark:border-rose-800' }}">
                    {{ $counts['baru'] }}
                </span>
            </a>

            <a href="{{ route('petugas.reports.index', array_merge(request()->except('page'), ['status' => 'diproses'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'diproses' ? 'bg-[#0F5143] text-white shadow-md' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5' }}">
                <span>Sedang Diproses</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'diproses' ? 'bg-white text-[#0F5143]' : 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-200 border border-amber-300 dark:border-amber-800' }}">
                    {{ $counts['diproses'] }}
                </span>
            </a>

            <a href="{{ route('petugas.reports.index', array_merge(request()->except('page'), ['status' => 'selesai'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'selesai' ? 'bg-[#0F5143] text-white shadow-md' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5' }}">
                <span>Selesai</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'selesai' ? 'bg-white text-[#0F5143]' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-200 border border-emerald-300 dark:border-emerald-800' }}">
                    {{ $counts['selesai'] }}
                </span>
            </a>

            <a href="{{ route('petugas.reports.index', array_merge(request()->except('page'), ['status' => 'ditolak'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'ditolak' ? 'bg-[#0F5143] text-white shadow-md' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5' }}">
                <span>Ditolak</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'ditolak' ? 'bg-white text-[#0F5143]' : 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                    {{ $counts['ditolak'] }}
                </span>
            </a>

            <a href="{{ route('petugas.reports.index', array_merge(request()->except('page'), ['status' => 'all'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'all' ? 'bg-[#0F5143] text-white shadow-md' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5' }}">
                <span>Semua Status</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'all' ? 'bg-white text-[#0F5143]' : 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                    {{ $counts['total'] }}
                </span>
            </a>
        </div>

        {{-- Form Filter & Pencarian --}}
        <form method="GET" action="{{ route('petugas.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 items-end">
            <input type="hidden" name="status" value="{{ $statusFilter }}">

            {{-- Input Search --}}
            <div>
                <label for="search" class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">
                    Pencarian Kata Kunci
                </label>
                <div class="relative">
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari pelapor, ruangan, keluhan..."
                           class="kezak-input w-full pl-9 pr-3.5 py-2 text-xs">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Kategori Kendala --}}
            <div>
                <label for="category" class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">
                    Kategori Kendala
                </label>
                <select id="category" name="category" class="kezak-input w-full px-3 py-2 text-xs">
                    <option value="all">Semua Kategori</option>
                    <option value="kerusakan" {{ $categoryFilter === 'kerusakan' ? 'selected' : '' }}>Kerusakan Fisik / Alat</option>
                    <option value="kebersihan" {{ $categoryFilter === 'kebersihan' ? 'selected' : '' }}>Masalah Kebersihan</option>
                    <option value="lainnya" {{ $categoryFilter === 'lainnya' ? 'selected' : '' }}>Operasional Lainnya</option>
                </select>
            </div>

            {{-- Filter Fasilitas --}}
            <div>
                <label for="facility_id" class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">
                    Fasilitas Terkait
                </label>
                <select id="facility_id" name="facility_id" class="kezak-input w-full px-3 py-2 text-xs">
                    <option value="">Semua Fasilitas</option>
                    @foreach ($facilities as $facility)
                        <option value="{{ $facility->id }}" {{ (string)$facilityId === (string)$facility->id ? 'selected' : '' }}>
                            {{ $facility->nama }} ({{ ucfirst(str_replace('_', ' ', $facility->tipe)) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Aksi Filter --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="kezak-btn-primary flex-1 px-4 py-2 text-xs font-bold inline-flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Terapkan</span>
                </button>
                <a href="{{ route('petugas.reports.index', ['status' => $statusFilter]) }}"
                   class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white bg-white/50 dark:bg-white/5 border border-white/80 dark:border-white/10 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- ==========================================
         3. DAFTAR ANTREAN LAPORAN (US 8, 11, 12)
         ========================================== --}}
    <div class="space-y-4">
        @forelse ($reports as $report)
            <div class="p-6 rounded-3xl bg-white/70 dark:bg-white/5 backdrop-blur-xl border border-white/70 dark:border-white/10 shadow-md hover:border-emerald-500/40 hover:shadow-lg transition-all space-y-4">
                
                {{-- Baris Atas: ID, Fasilitas, Kategori, Status --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-white/40 dark:border-white/10">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono font-bold uppercase tracking-wider bg-white/80 dark:bg-white/10 text-slate-700 dark:text-slate-300 border border-white/90 dark:border-white/15 shadow-2xs">
                            #REP-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}
                        </span>

                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shrink-0">
                                <x-facility-icon :tipe="$report->facility->tipe ?? 'aula'" class="w-4 h-4" />
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-extrabold text-slate-900 dark:text-white">
                                        {{ $report->facility->nama ?? 'Fasilitas Terhapus' }}
                                    </span>

                                    {{-- Status Fasilitas Operasional Saat Ini (US 12) --}}
                                    @if ($report->facility)
                                        @if ($report->facility->status === 'dalam_perbaikan')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-400/40">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Fasilitas: Dalam Perbaikan
                                            </span>
                                        @elseif ($report->facility->status === 'aktif')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-400/30">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Fasilitas: Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                                Fasilitas: Nonaktif
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                @if ($report->facility && $report->facility->lokasi)
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-0.5 font-medium">
                                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ $report->facility->lokasi }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Status Laporan Badge --}}
                    <div>
                        @if ($report->status === 'baru')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-500/15 text-sky-800 dark:text-sky-300 border border-sky-400/30">
                                <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span> Baru Masuk
                            </span>
                        @elseif ($report->status === 'diproses')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-800 dark:text-amber-300 border border-amber-400/30">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span> Sedang Ditangani
                            </span>
                        @elseif ($report->status === 'selesai')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 border border-emerald-400/30">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Selesai Diperbaiki
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-500/15 text-rose-800 dark:text-rose-300 border border-rose-400/30">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span> Laporan Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Baris Detail: Pelapor, Waktu, Deskripsi, Foto Bukti --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                    
                    {{-- Kolom Kiri: Informasi Pelapor & Deskripsi --}}
                    <div class="md:col-span-3 space-y-3">
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 text-slate-600 dark:text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <strong class="text-slate-800 dark:text-slate-200">Pelapor:</strong>
                                <span>{{ $report->user->name ?? 'Pengguna' }}</span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-white/60 dark:bg-white/10 border border-white/80 dark:border-white/15">
                                    {{ ucfirst($report->user->tipe_pengguna ?? 'pengguna') }}
                                </span>
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1.5">
                                <strong class="text-slate-800 dark:text-slate-200">Kategori:</strong>
                                <span class="capitalize">{{ $report->kategori }}</span>
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                            </span>
                        </div>

                        {{-- Deskripsi Kendala --}}
                        <div class="bg-white/50 dark:bg-white/5 p-3.5 rounded-2xl border border-white/60 dark:border-white/10 space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Uraian Kendala Pelapor:</span>
                            <p class="text-xs text-slate-800 dark:text-slate-200 leading-relaxed font-medium">
                                {{ $report->deskripsi }}
                            </p>
                        </div>

                        {{-- Resolusi / Catatan Teknisi (Jika sudah ada) --}}
                        @if ($report->catatan_resolusi)
                            <div class="bg-emerald-500/10 dark:bg-emerald-950/20 p-3.5 rounded-2xl border border-emerald-400/30 text-emerald-950 dark:text-emerald-200 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-300 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Catatan Penanganan & Resolusi:
                                    </span>
                                    <span class="text-[10px] text-emerald-700 dark:text-emerald-300">
                                        Oleh: {{ $report->resolver->name ?? 'Petugas Teknis' }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed pl-4">
                                    {{ $report->catatan_resolusi }}
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Kolom Kanan: Foto Bukti & Tombol Aksi --}}
                    <div class="flex flex-col sm:flex-row md:flex-col justify-between items-start md:items-end gap-3 shrink-0">
                        {{-- Foto Bukti --}}
                        <div>
                            @if ($report->foto_path)
                                <a href="{{ asset('storage/' . $report->foto_path) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="group block relative rounded-2xl overflow-hidden border border-white/80 dark:border-white/20 shadow-xs hover:border-emerald-500 transition-all">
                                    <img src="{{ asset('storage/' . $report->foto_path) }}"
                                         alt="Bukti Kerusakan"
                                         class="w-24 h-24 sm:w-28 sm:h-28 object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold">
                                        Perbesar Bukti
                                    </div>
                                </a>
                            @else
                                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white/40 dark:bg-white/5 border border-dashed border-white/60 dark:border-white/10 flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 text-center p-2">
                                    <svg class="w-6 h-6 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[10px]">Tanpa Foto</span>
                                </div>
                            @endif
                        </div>

                        {{-- Tombol Aksi Tindak Lanjut --}}
                        <button type="button"
                                onclick="openUpdateModal('{{ $report->id }}', '{{ addslashes($report->facility->nama ?? 'Fasilitas') }}', '{{ addslashes($report->user->name ?? 'Pengguna') }}', '{{ $report->status }}', '{{ addslashes($report->catatan_resolusi ?? '') }}', '{{ $report->facility->status ?? 'aktif' }}')"
                                class="kezak-btn-primary w-full md:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-bold shadow-xs cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Tindak Lanjut</span>
                        </button>
                    </div>

                </div>

            </div>
        @empty
            <div class="text-center py-16 rounded-3xl bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-dashed border-white/60 dark:border-white/10 space-y-3">
                <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-800 dark:text-white">Tidak Ada Laporan Ditemukan</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                    Tidak ada antrean laporan fasilitas yang sesuai dengan kriteria filter saat ini.
                </p>
                <a href="{{ route('petugas.reports.index', ['status' => 'all']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-[#0F5143] dark:text-[#34D399] bg-white/70 dark:bg-white/10 border border-white/80 dark:border-white/15 hover:bg-white/90">
                    Lihat Semua Laporan
                </a>
            </div>
        @endforelse

        {{-- PAGINATION --}}
        @if ($reports->hasPages())
            <div class="pt-4 border-t border-white/40 dark:border-white/10">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ==========================================
     4. MODAL UPDATE STATUS & RESOLUSI (US 11 & US 12)
     ========================================== --}}
<div id="updateReportModal" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-3xl bg-white/95 dark:bg-[#081411]/95 backdrop-blur-2xl border border-white/60 dark:border-white/10 p-6 sm:p-7 shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto">
        <div class="flex items-start justify-between pb-3 border-b border-white/40 dark:border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 id="modalTitle" class="text-base font-extrabold text-slate-900 dark:text-white">Tindak Lanjut Laporan</h3>
                    <p id="modalSub" class="text-xs text-slate-500 dark:text-slate-400"></p>
                </div>
            </div>
            <button type="button" onclick="closeUpdateModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="updateReportForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PATCH')

            {{-- Pilihan Status Laporan --}}
            <div>
                <label for="modal_status" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                    Status Penanganan Laporan <span class="text-rose-500">*</span>:
                </label>
                <select id="modal_status"
                        name="status"
                        required
                        onchange="handleStatusChange()"
                        class="kezak-input w-full px-3.5 py-2.5 text-xs sm:text-sm">
                    <option value="diproses">Sedang Diproses (Investigasi / Perbaikan Berlangsung)</option>
                    <option value="selesai">Selesai (Kerusakan Telah Teratasi & Fasilitas Normal)</option>
                    <option value="ditolak">Ditolak (Bukan Kerusakan / Tidak Dapat Divalidasi)</option>
                    <option value="baru">Kembalikan ke Status Baru</option>
                </select>
            </div>

            {{-- Catatan Resolusi --}}
            <div>
                <label for="modal_catatan_resolusi" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">
                    Catatan Tindak Lanjut / Solusi <span id="resolusiRequiredText" class="text-rose-500 text-[11px] font-normal">(Wajib untuk Selesai / Ditolak)</span>:
                </label>
                <textarea id="modal_catatan_resolusi"
                          name="catatan_resolusi"
                          rows="3"
                          placeholder="Jelaskan tindakan yang dilakukan teknisi atau alasan penolakan, misal: Telah diganti bohlam proyektor baru dan diuji coba lancar..."
                          class="kezak-input w-full px-3.5 py-2.5 text-xs sm:text-sm resize-y"></textarea>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                    Catatan ini akan langsung terlihat oleh pelapor pada halaman riwayat laporan mereka.
                </p>
            </div>

            {{-- US 12: Pengaturan Status Operasional Fasilitas --}}
            <div class="p-4 rounded-2xl bg-emerald-500/10 dark:bg-emerald-950/20 border border-emerald-400/30 space-y-2.5">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-800 dark:text-white">
                        <svg class="w-4 h-4 text-[#0F5143] dark:text-[#34D399]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Pengaturan Ketersediaan Ruangan (US 12)</span>
                    </div>
                    <span id="modalCurrentFacilityBadge" class="text-[10px] font-bold px-2 py-0.5 rounded-full"></span>
                </div>
                <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                    Jika kerusakan memerlukan penutupan operasional, alihkan status menjadi <strong class="text-amber-700 dark:text-amber-300">Dalam Perbaikan</strong> untuk memblokir peminjaman baru. Setelah selesai diperbaiki, kembalikan ke <strong class="text-emerald-700 dark:text-emerald-300">Aktif</strong>.
                </p>
                <div>
                    <label for="modal_mark_facility_status" class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        Pembaruan Status Fasilitas:
                    </label>
                    <select id="modal_mark_facility_status"
                            name="mark_facility_status"
                            class="kezak-input w-full px-3.5 py-2 text-xs">
                        <option value="">-- Biarkan status operasional saat ini --</option>
                        <option value="dalam_perbaikan">Set Fasilitas: Dalam Perbaikan (Blokir Reservasi Baru)</option>
                        <option value="aktif">Set Fasilitas: Aktif (Buka Kembali Reservasi)</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-white/40 dark:border-white/10">
                <button type="button" onclick="closeUpdateModal()"
                        class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-white">
                    Batal
                </button>
                <button type="submit"
                        class="kezak-btn-primary px-5 py-2.5 rounded-xl text-xs font-bold shadow-xs cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function handleStatusChange() {
        const status = document.getElementById('modal_status').value;
        const requiredText = document.getElementById('resolusiRequiredText');
        const textarea = document.getElementById('modal_catatan_resolusi');
        
        if (status === 'selesai' || status === 'ditolak') {
            if (requiredText) {
                requiredText.textContent = '(Wajib diisi)';
                requiredText.className = 'text-rose-500 text-[11px] font-bold';
            }
            if (textarea) textarea.setAttribute('required', 'required');
        } else {
            if (requiredText) {
                requiredText.textContent = '(Opsional)';
                requiredText.className = 'text-slate-400 text-[11px] font-normal';
            }
            if (textarea) textarea.removeAttribute('required');
        }
    }

    function openUpdateModal(id, facilityName, userName, currentStatus, currentNotes, currentFacilityStatus) {
        document.getElementById('updateReportForm').action = '/petugas/reports/' + id;
        document.getElementById('modalTitle').innerText = 'Tindak Lanjut Laporan #REP-' + String(id).padStart(4, '0');
        document.getElementById('modalSub').innerText = facilityName + ' (Pelapor: ' + userName + ')';
        
        const statusSelect = document.getElementById('modal_status');
        statusSelect.value = currentStatus;

        document.getElementById('modal_catatan_resolusi').value = currentNotes || '';
        handleStatusChange();
        
        const facilitySelect = document.getElementById('modal_mark_facility_status');
        facilitySelect.value = '';

        const badge = document.getElementById('modalCurrentFacilityBadge');
        if (badge) {
            if (currentFacilityStatus === 'dalam_perbaikan') {
                badge.innerText = 'Saat Ini: Dalam Perbaikan';
                badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-400/40';
            } else if (currentFacilityStatus === 'aktif') {
                badge.innerText = 'Saat Ini: Aktif';
                badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-400/30';
            } else {
                badge.innerText = 'Saat Ini: ' + (currentFacilityStatus || 'Unknown');
                badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300';
            }
        }

        const modal = document.getElementById('updateReportModal') || document.getElementById('updateStatusModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeUpdateModal() {
        const modal = document.getElementById('updateReportModal') || document.getElementById('updateStatusModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>
@endsection
