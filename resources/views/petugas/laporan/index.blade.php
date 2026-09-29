@extends('layouts.petugas')

@section('title', 'Laporan Kendala Fasilitas')
@section('header_title', 'Antrean & Resolusi Laporan Fasilitas')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- ==========================================
         1. HEADER SECTION (FROSTED HERO BANNER)
         ========================================== --}}
    <div class="glass-card-main rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
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
                <span class="text-2xl font-extrabold text-rose-600 dark:text-rose-400">{{ $counts['baru'] ?? 0 }}</span>
            </div>
            <div class="px-4 py-2.5 rounded-2xl bg-white/70 dark:bg-white/10 backdrop-blur-md border border-white/80 dark:border-white/15 text-center shadow-xs">
                <span class="block text-[11px] uppercase tracking-wider text-amber-600 dark:text-amber-400 font-bold">Diproses</span>
                <span class="text-2xl font-extrabold text-amber-600 dark:text-amber-400">{{ $counts['diproses'] ?? 0 }}</span>
            </div>
            <div class="px-4 py-2.5 rounded-2xl bg-white/70 dark:bg-white/10 backdrop-blur-md border border-white/80 dark:border-white/15 text-center shadow-xs">
                <span class="block text-[11px] uppercase tracking-wider text-emerald-600 dark:text-emerald-400 font-bold">Selesai</span>
                <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $counts['selesai'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    {{-- ALERT NOTIFIKASI SUKSES --}}
    @if (session('status'))
        <div class="p-4 sm:p-5 rounded-2xl bg-emerald-500/15 backdrop-blur-xl border border-emerald-400/40 text-emerald-950 dark:text-emerald-200 text-xs sm:text-sm flex items-start gap-3 shadow-xs">
            <div class="w-7 h-7 rounded-xl bg-emerald-500/20 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="pt-0.5">
                <strong class="font-bold block mb-0.5">Berhasil Diperbarui!</strong>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    {{-- ALERT VALIDASI / ERROR --}}
    @if ($errors->any())
        <div class="p-4 sm:p-5 rounded-2xl bg-rose-500/15 backdrop-blur-xl border border-rose-400/40 text-rose-950 dark:text-rose-200 text-xs sm:text-sm shadow-xs space-y-1">
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
    <div class="glass-card-main rounded-3xl p-6 sm:p-7 space-y-5">
        {{-- Status Filter Tabs --}}
        <div class="flex flex-wrap items-center gap-2 border-b border-white/40 dark:border-white/10 pb-4">
            <a href="{{ route('petugas.reports.index', array_merge(request()->except('page'), ['status' => 'baru'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'baru' ? 'bg-[#0F5143] text-white shadow-md' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5' }}">
                <span>Laporan Baru</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'baru' ? 'bg-white text-[#0F5143]' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-200 border border-rose-300 dark:border-rose-800' }}">
                    {{ $counts['baru'] ?? 0 }}
                </span>
            </a>

            <a href="{{ route('petugas.reports.index', array_merge(request()->except('page'), ['status' => 'diproses'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'diproses' ? 'bg-[#0F5143] text-white shadow-md' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5' }}">
                <span>Sedang Diproses</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'diproses' ? 'bg-white text-[#0F5143]' : 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-200 border border-amber-300 dark:border-amber-800' }}">
                    {{ $counts['diproses'] ?? 0 }}
                </span>
            </a>

            <a href="{{ route('petugas.reports.index', array_merge(request()->except('page'), ['status' => 'selesai'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'selesai' ? 'bg-[#0F5143] text-white shadow-md' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5' }}">
                <span>Selesai</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'selesai' ? 'bg-white text-[#0F5143]' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-200 border border-emerald-300 dark:border-emerald-800' }}">
                    {{ $counts['selesai'] ?? 0 }}
                </span>
            </a>

            <a href="{{ route('petugas.reports.index', array_merge(request()->except('page'), ['status' => 'ditolak'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'ditolak' ? 'bg-[#0F5143] text-white shadow-md' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5' }}">
                <span>Ditolak</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'ditolak' ? 'bg-white text-[#0F5143]' : 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                    {{ $counts['ditolak'] ?? 0 }}
                </span>
            </a>

            <a href="{{ route('petugas.reports.index', array_merge(request()->except('page'), ['status' => 'all'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all {{ $statusFilter === 'all' ? 'bg-[#0F5143] text-white shadow-md' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5' }}">
                <span>Semua Status</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'all' ? 'bg-white text-[#0F5143]' : 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                    {{ $counts['total'] ?? 0 }}
                </span>
            </a>
        </div>

        {{-- Form Pencarian & Filter Sekunder --}}
        <form method="GET" action="{{ route('petugas.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
            <input type="hidden" name="status" value="{{ $statusFilter }}">

            {{-- Input Pencarian --}}
            <div class="sm:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari pelapor, fasilitas, kendala, atau solusi..."
                       class="kezak-input w-full px-4 py-2.5 text-xs sm:text-sm">
            </div>

            {{-- Filter Kategori --}}
            <div>
                <select name="category" class="kezak-input w-full px-3 py-2.5 text-xs sm:text-sm">
                    <option value="">Semua Kategori Masalah</option>
                    <option value="kerusakan" @selected($categoryFilter === 'kerusakan')>Kerusakan Fisik</option>
                    <option value="kebersihan" @selected($categoryFilter === 'kebersihan')>Kebersihan</option>
                    <option value="lainnya" @selected($categoryFilter === 'lainnya')>Kendala Lainnya</option>
                </select>
            </div>

            {{-- Filter Fasilitas & Tombol Submit --}}
            <div class="flex items-center gap-2">
                <select name="facility_id" class="kezak-input w-full px-3 py-2.5 text-xs sm:text-sm">
                    <option value="">Semua Fasilitas</option>
                    @foreach ($facilities as $fac)
                        <option value="{{ $fac->id }}" @selected((string)$facilityId === (string)$fac->id)>
                            {{ $fac->nama }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="kezak-btn-primary px-4 py-2.5 rounded-xl text-xs font-bold shrink-0 shadow-xs cursor-pointer">
                    Filter
                </button>

                @if ($search || $categoryFilter || $facilityId || $statusFilter !== 'baru')
                    <a href="{{ route('petugas.reports.index', ['status' => 'baru']) }}"
                       class="px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-white shrink-0"
                       title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ==========================================
         3. DAFTAR ANTREAN LAPORAN (US 8)
         ========================================== --}}
    <div class="space-y-4">
        @forelse ($reports as $report)
            <div class="p-6 rounded-3xl glass-card-main border border-white/70 dark:border-white/10 space-y-4 shadow-sm hover:border-emerald-500/40 transition-all">
                
                {{-- Header Laporan: Pelapor & Status --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-white/40 dark:border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-teal-500/15 dark:bg-teal-950/60 text-[#0F5143] dark:text-[#34D399] font-extrabold flex items-center justify-center text-sm shadow-2xs shrink-0">
                            {{ strtoupper(substr($report->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-extrabold text-slate-900 dark:text-white">
                                    {{ $report->user->name ?? 'Pengguna' }}
                                </span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-white/60 dark:bg-white/10 text-slate-600 dark:text-slate-300">
                                    {{ $report->user->tipe_pengguna ?? 'Mahasiswa' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $report->user->email ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Status Badge & ID Laporan --}}
                    <div class="flex items-center gap-3 self-start sm:self-center">
                        <span class="text-xs font-mono font-bold text-slate-400 dark:text-slate-500">
                            #REP-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}
                        </span>

                        @if ($report->status === 'baru')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-500/15 text-rose-800 dark:text-rose-300 border border-rose-400/30">
                                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                                <span>Laporan Baru</span>
                            </span>
                        @elseif ($report->status === 'diproses')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-800 dark:text-amber-300 border border-amber-400/30">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span>Sedang Diproses</span>
                            </span>
                        @elseif ($report->status === 'selesai')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 border border-emerald-400/30">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Selesai Diperbaiki</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-500/15 text-slate-700 dark:text-slate-300 border border-slate-400/30">
                                <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                                <span>Ditolak</span>
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Detail Fasilitas & Isi Masalah --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 text-xs">
                    {{-- Informasi Fasilitas & Waktu --}}
                    <div class="space-y-2">
                        <div class="p-3.5 rounded-2xl glass-card-nested border border-white/60 dark:border-white/10 space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Fasilitas Kampus:</span>
                            <strong class="text-sm text-slate-900 dark:text-white block font-extrabold">
                                {{ $report->facility->nama ?? 'Fasilitas' }}
                            </strong>
                            <p class="text-slate-500 dark:text-slate-400">
                                {{ $report->facility->lokasi ?? '-' }}
                            </p>
                            <div class="pt-1 flex items-center gap-2">
                                <span class="text-[11px] font-medium text-slate-600 dark:text-slate-300">Status Operasional:</span>
                                @if ($report->facility && $report->facility->status === 'aktif')
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                        Aktif
                                    </span>
                                @elseif ($report->facility && $report->facility->status === 'dalam_perbaikan')
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">
                                        Dalam Perbaikan
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                        {{ $report->facility->status ?? '-' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between px-2 text-slate-500 dark:text-slate-400 text-[11px]">
                            <span>Kategori: <strong class="text-slate-800 dark:text-slate-200">{{ ucfirst($report->kategori) }}</strong></span>
                            <span>{{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    {{-- Deskripsi Kendala & Catatan Resolusi --}}
                    <div class="lg:col-span-2 space-y-3">
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Deskripsi Kendala Pelapor:</span>
                            <p class="p-3.5 rounded-2xl bg-white/40 dark:bg-white/5 border border-white/60 dark:border-white/10 text-slate-700 dark:text-slate-300 leading-relaxed">
                                {{ $report->deskripsi }}
                            </p>
                        </div>

                        {{-- Catatan Resolusi Petugas (Jika Sudah Diisi) --}}
                        @if ($report->catatan_resolusi)
                            <div class="p-3.5 rounded-2xl bg-emerald-500/10 dark:bg-emerald-950/30 border border-emerald-400/30 text-emerald-950 dark:text-emerald-200 space-y-1">
                                <div class="flex items-center justify-between text-xs font-bold text-[#0F5143] dark:text-[#34D399]">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Catatan Resolusi / Solusi Tindak Lanjut:</span>
                                    </span>
                                    @if ($report->resolver)
                                        <span class="text-[11px] font-normal text-slate-500 dark:text-slate-400">
                                            Oleh: {{ $report->resolver->name }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-700 dark:text-slate-300 pl-5 leading-relaxed">
                                    {{ $report->catatan_resolusi }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Footer Kartu: Bukti Foto & Tombol Aksi --}}
                <div class="pt-3 border-t border-white/40 dark:border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    {{-- Pratinjau Foto Bukti --}}
                    <div>
                        @if ($report->foto_path)
                            <a href="{{ asset('storage/' . $report->foto_path) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold text-[#0F5143] dark:text-[#34D399] bg-white/70 dark:bg-white/10 border border-white/80 dark:border-white/15 hover:bg-white/90 dark:hover:bg-white/20 transition-all shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Lihat Foto Bukti Kerusakan</span>
                            </a>
                        @else
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 italic">
                                * Pelapor tidak menyertakan foto bukti.
                            </span>
                        @endif
                    </div>

                    {{-- Tombol Aksi Tindak Lanjut Modal --}}
                    <div class="flex items-center gap-2 self-end sm:self-center">
                        <button type="button"
                                onclick="openUpdateModal(
                                    {{ $report->id }},
                                    '{{ addslashes($report->facility->nama ?? 'Fasilitas') }}',
                                    '{{ addslashes($report->user->name ?? 'Pengguna') }}',
                                    '{{ $report->status }}',
                                    '{{ addslashes($report->catatan_resolusi ?? '') }}',
                                    '{{ $report->facility->status ?? 'aktif' }}'
                                )"
                                class="kezak-btn-primary inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold shadow-xs cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Tindak Lanjut & Resolusi</span>
                        </button>
                    </div>
                </div>

            </div>
        @empty
            <div class="text-center py-16 rounded-3xl glass-card-main space-y-3">
                <div class="w-14 h-14 mx-auto rounded-3xl bg-teal-500/15 dark:bg-teal-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shadow-2xs">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-800 dark:text-white">Tidak Ada Laporan Ditemukan</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                    Tidak ada laporan kendala fasilitas yang cocok dengan filter atau kata kunci pencarian Anda saat ini.
                </p>
                <a href="{{ route('petugas.reports.index', ['status' => 'all']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-[#0F5143] dark:text-[#34D399] bg-white/70 dark:bg-white/10 border border-white/80 dark:border-white/15 hover:bg-white/90 dark:hover:bg-white/20 transition-all">
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
<div id="updateStatusModal" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-3xl bg-white/95 dark:bg-[#081411]/95 backdrop-blur-2xl border border-white/60 dark:border-white/10 p-6 sm:p-7 shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto">
        <div class="flex items-start justify-between pb-3 border-b border-white/40 dark:border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-teal-500/15 dark:bg-teal-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 id="modalTitle" class="text-base font-extrabold text-slate-900 dark:text-white">Tindak Lanjut Laporan</h3>
                    <p id="modalSub" class="text-xs text-slate-500 dark:text-slate-400"></p>
                </div>
            </div>
            <button type="button" onclick="closeUpdateModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
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
            <div class="p-4 rounded-2xl bg-teal-500/10 dark:bg-teal-950/20 border border-teal-500/20 space-y-2">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-800 dark:text-white">
                    <svg class="w-4 h-4 text-[#0F5143] dark:text-[#34D399]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Pengaturan Ketersediaan Ruangan (US 12)</span>
                </div>
                <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                    Jika kerusakan parah dan fasilitas tidak dapat digunakan, alihkan status menjadi <strong class="text-amber-700 dark:text-amber-300">Dalam Perbaikan</strong> untuk memblokir peminjaman baru oleh mahasiswa/dosen.
                </p>
                <select id="modal_mark_facility_status"
                        name="mark_facility_status"
                        class="kezak-input w-full px-3.5 py-2 text-xs">
                    <option value="">-- Biarkan status operasional saat ini --</option>
                    <option value="dalam_perbaikan">Set Fasilitas: Dalam Perbaikan (Blokir Reservasi Baru)</option>
                    <option value="aktif">Set Fasilitas: Aktif (Buka Kembali Reservasi)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-white/40 dark:border-white/10">
                <button type="button" onclick="closeUpdateModal()"
                        class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-white cursor-pointer">
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
            requiredText.textContent = '(Wajib diisi)';
            requiredText.className = 'text-rose-500 text-[11px] font-bold';
            textarea.setAttribute('required', 'required');
        } else {
            requiredText.textContent = '(Opsional)';
            requiredText.className = 'text-slate-400 text-[11px] font-normal';
            textarea.removeAttribute('required');
        }
    }

    function openUpdateModal(id, facilityName, userName, currentStatus, currentNotes, currentFacilityStatus) {
        document.getElementById('updateReportForm').action = '/petugas/reports/' + id;
        document.getElementById('modalTitle').innerText = 'Tindak Lanjut Laporan #REP-' + String(id).padStart(4, '0');
        document.getElementById('modalSub').innerText = facilityName + ' (Pelapor: ' + userName + ')';
        
        const statusSelect = document.getElementById('modal_status');
        statusSelect.value = currentStatus;

        document.getElementById('modal_catatan_resolusi').value = currentNotes || '';
        
        const facilitySelect = document.getElementById('modal_mark_facility_status');
        facilitySelect.value = '';

        handleStatusChange();

        document.getElementById('updateStatusModal').classList.remove('hidden');
    }

    function closeUpdateModal() {
        document.getElementById('updateStatusModal').classList.add('hidden');
    }
</script>
@endsection
