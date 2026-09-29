@extends('layouts.pengguna')

@section('title', 'Pelaporan Kendala Fasilitas — FacilityHub')
@section('header_title', 'Laporan Kendala Fasilitas')
@section('header_subtitle', 'Laporkan kerusakan sarana prasarana, kendala kebersihan, atau gangguan fungsi ruangan kampus')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- ==========================================
         1. NOTIFIKASI SUKSES / ERROR
         ========================================== --}}
    @if (session('status'))
        <div class="p-4 sm:p-5 rounded-2xl bg-emerald-500/15 border border-emerald-400/30 text-emerald-900 dark:text-emerald-200 backdrop-blur-xl flex items-start gap-3 shadow-xs">
            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold">Laporan Berhasil Terkirim</p>
                <p class="text-xs text-emerald-800 dark:text-emerald-300 mt-0.5">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 sm:p-5 rounded-2xl bg-rose-500/15 border border-rose-400/30 text-rose-900 dark:text-rose-200 backdrop-blur-xl space-y-1.5 shadow-xs">
            <div class="flex items-center gap-2 font-bold text-xs sm:text-sm text-rose-800 dark:text-rose-300">
                <svg class="w-4 h-4 shrink-0 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Mohon periksa kembali isian formulir:</span>
            </div>
            <ul class="list-disc list-inside text-xs text-rose-700 dark:text-rose-300 space-y-0.5 pl-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ==========================================
         2. HEADER BANNER (FROSTED GLASS HERO)
         ========================================== --}}
    <div class="bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-5 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-60 h-60 rounded-full bg-emerald-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 border border-emerald-500/20 text-[#0F5143] dark:text-[#34D399] mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Layanan Sarana & Prasarana Kampus</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                Form Pelaporan Kendala Fasilitas
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-300 max-w-2xl leading-relaxed">
                Laporkan kerusakan alat, kendala kebersihan, atau gangguan fungsi ruangan kampus agar tim teknisi dapat segera melakukan inspeksi dan perbaikan.
            </p>
        </div>

        <div class="relative z-10 shrink-0">
            <a href="{{ route('facilities') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-white/70 dark:bg-white/10 hover:bg-white/90 dark:hover:bg-white/20 transition-all border border-white/80 dark:border-white/15 shadow-xs">
                <svg class="w-4 h-4 text-[#0F5143] dark:text-[#34D399]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Katalog Fasilitas</span>
            </a>
        </div>
    </div>

    {{-- GRID KONTEN FORMULIR & PANDUAN --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

        {{-- FORMULIR UTAMA (2 KOLOM) --}}
        <div class="lg:col-span-2">
            <div class="p-6 sm:p-8 rounded-3xl bg-white/80 dark:bg-white/5 backdrop-blur-xl border border-white/70 dark:border-white/10 shadow-lg space-y-6">
                <div class="pb-4 border-b border-white/40 dark:border-white/10">
                    <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Detail Kerusakan / Kendala</h2>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-0.5">
                        Isi formulir berikut dengan spesifik untuk mempercepat tindakan petugas di lapangan.
                    </p>
                </div>

                <form id="reportForm" method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Fasilitas Terkait --}}
                    <div>
                        <label for="facility_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Fasilitas / Ruangan Kampus <span class="text-rose-500">*</span>
                        </label>
                        <select id="facility_id"
                                name="facility_id"
                                required
                                class="kezak-input block w-full px-3.5 py-2.5 text-xs sm:text-sm">
                            <option value="">-- Pilih Fasilitas yang Bermasalah --</option>
                            @foreach ($facilities as $facility)
                                <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->nama }} ({{ ucfirst(str_replace('_', ' ', $facility->tipe)) }} • {{ $facility->lokasi }}) - Status: {{ ucfirst(str_replace('_', ' ', $facility->status)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('facility_id')
                            <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Masalah / Kendala --}}
                    <div>
                        <label for="reportCategory" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Jenis Masalah / Kendala <span class="text-rose-500">*</span>
                        </label>
                        <select id="reportCategory"
                                name="category"
                                required
                                class="kezak-input block w-full px-3.5 py-2.5 text-xs sm:text-sm">
                            <option value="kerusakan" {{ old('category') === 'kerusakan' ? 'selected' : '' }}>Kerusakan Fisik / Alat Tidak Berfungsi</option>
                            <option value="kebersihan" {{ old('category') === 'kebersihan' ? 'selected' : '' }}>Masalah Kebersihan / Ruangan Kotor / Toilet</option>
                            <option value="lainnya" {{ old('category') === 'lainnya' ? 'selected' : '' }}>Kendala Operasional Lainnya</option>
                        </select>
                        @error('category')
                            <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi Masalah --}}
                    <div>
                        <label for="reportDescription" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Deskripsi Rinci Kendala <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="reportDescription"
                                  name="description"
                                  rows="4"
                                  maxlength="2000"
                                  required
                                  placeholder="Jelaskan secara spesifik kerusakan atau kendala yang dialami, misalnya: AC tidak dingin, proyektor bergaris ungu, stopkontak meja depan kendor..."
                                  class="kezak-input block w-full px-3.5 py-2.5 text-xs sm:text-sm resize-y">{{ old('description') }}</textarea>
                        <div class="flex items-center justify-between mt-1.5 text-[11px] text-slate-500 dark:text-slate-400">
                            <span>Minimal 5 karakter, maksimal 2000 karakter</span>
                            <span id="charCounter" class="font-medium text-slate-600 dark:text-slate-300">0 / 2000 karakter</span>
                        </div>
                        @error('description')
                            <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Unggah Foto Bukti --}}
                    <div>
                        <label for="reportPhoto" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Foto Bukti Pendukung <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                        </label>
                        <div class="rounded-2xl border-2 border-dashed border-white/80 dark:border-white/20 bg-white/40 dark:bg-white/5 p-4 sm:p-5 text-center hover:border-emerald-500/50 transition-colors">
                            <div class="w-10 h-10 mx-auto rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center mb-2 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <input id="reportPhoto"
                                   name="photo"
                                   type="file"
                                   accept="image/png, image/jpeg"
                                   class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0F5143] file:text-white hover:file:bg-[#146353] cursor-pointer">
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-2">
                                Format JPEG atau PNG (maksimal 2MB). Lampirkan foto pendukung untuk mempercepat verifikasi teknisi.
                            </p>
                        </div>

                        {{-- Pesan Error Validasi Client-Side --}}
                        <div id="photoError" class="hidden mt-2 p-3 rounded-2xl bg-rose-500/15 border border-rose-400/30 text-rose-900 dark:text-rose-200 text-xs flex items-center gap-2 backdrop-blur-md shadow-2xs">
                            <svg class="w-4 h-4 shrink-0 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span id="photoErrorMessage" class="font-semibold"></span>
                        </div>

                        @error('photo')
                            <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror

                        {{-- Live Photo Preview Container --}}
                        <div id="photoPreviewContainer" class="hidden mt-3 p-3.5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/70 dark:border-white/10 shadow-xs">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-white/80 dark:border-white/15 shrink-0 shadow-2xs">
                                        <img id="photoPreviewImg" src="" alt="Pratinjau Foto Bukti" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0">
                                        <p id="photoPreviewName" class="text-xs font-bold text-slate-800 dark:text-white truncate">nama_file.jpg</p>
                                        <p id="photoPreviewSize" class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">1.2 MB</p>
                                        <span class="inline-flex items-center gap-1 mt-1 text-[10px] font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-500/20">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Foto siap diunggah
                                        </span>
                                    </div>
                                </div>
                                <button type="button"
                                        id="removePhotoBtn"
                                        class="shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold text-rose-700 dark:text-rose-300 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-400/20 transition-colors flex items-center gap-1.5 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>Hapus / Ganti Foto</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="pt-4 border-t border-white/40 dark:border-white/10 flex items-center justify-end gap-3">
                        <a href="{{ route('facilities') }}"
                           class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-white transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                                id="submitReportBtn"
                                class="kezak-btn-primary inline-flex items-center justify-center gap-2 px-6 py-2.5 text-xs sm:text-sm font-bold shadow-md cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                            <svg id="submitReportIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span id="submitReportText">Kirim Laporan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- SIDEBAR INFORMASI PROSEDUR PELAPORAN --}}
        <div class="space-y-4">
            <div class="p-6 rounded-3xl bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-xs space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Alur Penanganan</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Prosedur tindak lanjut laporan</p>
                    </div>
                </div>

                <ol class="space-y-3.5 text-xs text-slate-600 dark:text-slate-400">
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-xl bg-white/80 dark:bg-white/10 text-slate-800 dark:text-slate-200 font-extrabold flex items-center justify-center shrink-0 text-xs border border-white/90 dark:border-white/15 shadow-2xs">1</span>
                        <div>
                            <strong class="text-slate-900 dark:text-white block text-xs">Laporan Diterima</strong>
                            Petugas menerima notifikasi keluhan fasilitas yang masuk ke sistem.
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-xl bg-white/80 dark:bg-white/10 text-slate-800 dark:text-slate-200 font-extrabold flex items-center justify-center shrink-0 text-xs border border-white/90 dark:border-white/15 shadow-2xs">2</span>
                        <div>
                            <strong class="text-slate-900 dark:text-white block text-xs">Verifikasi Lapangan</strong>
                            Petugas melakukan pengecekan fisik dan memulai proses perbaikan.
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-xl bg-white/80 dark:bg-white/10 text-slate-800 dark:text-slate-200 font-extrabold flex items-center justify-center shrink-0 text-xs border border-white/90 dark:border-white/15 shadow-2xs">3</span>
                        <div>
                            <strong class="text-slate-900 dark:text-white block text-xs">Resolusi & Pembaruan</strong>
                            Petugas mencatat tindakan solusi dan menyelesaikan status laporan.
                        </div>
                    </li>
                </ol>
            </div>

            <div class="p-5 rounded-3xl bg-amber-500/10 backdrop-blur-xl border border-amber-400/40 text-xs text-amber-950 dark:text-amber-200 shadow-xs">
                <div class="font-extrabold flex items-center gap-2 mb-1.5 text-amber-800 dark:text-amber-300">
                    <svg class="w-4 h-4 shrink-0 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Keadaan Darurat?</span>
                </div>
                <p class="text-slate-600 dark:text-slate-300 text-xs leading-relaxed">
                    Untuk korsleting listrik, pipa bocor deras, atau situasi darurat yang membahayakan keselamatan kampus, segera hubungi pos petugas jaga fisik kampus.
                </p>
            </div>
        </div>

    </div>

    {{-- ==========================================
         3. RIWAYAT LAPORAN SAYA (US 7)
         ========================================== --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-white/80 dark:bg-white/5 backdrop-blur-xl border border-white/70 dark:border-white/10 shadow-lg space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/40 dark:border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Riwayat Laporan Kendala Saya</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-0.5">Pantau status penanganan dan catatan tindak lanjut dari petugas</p>
                </div>
            </div>
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-white/60 dark:bg-white/10 px-3 py-1.5 rounded-full border border-white/70 dark:border-white/10 self-start sm:self-auto">
                Total: {{ $myReports->total() }} Laporan
            </span>
        </div>

        <div class="space-y-4">
            @forelse ($myReports as $report)
                <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/70 dark:border-white/10 hover:border-emerald-500/50 hover:shadow-sm transition-all space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono font-bold uppercase tracking-wider bg-white/80 dark:bg-white/10 text-slate-700 dark:text-slate-300 border border-white/90 dark:border-white/15 shadow-2xs">
                                #REP-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 rounded-lg bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shrink-0">
                                    <x-facility-icon :tipe="$report->facility->tipe ?? 'aula'" class="w-3.5 h-3.5" />
                                </div>
                                <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">
                                    {{ $report->facility->nama ?? 'Fasilitas Terhapus' }}
                                </h4>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        <div>
                            @if ($report->status === 'baru')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-500/15 text-sky-800 dark:text-sky-300 border border-sky-400/30">
                                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span> Baru / Menunggu Verifikasi
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

                    {{-- Metadata & Deskripsi --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                        <div class="md:col-span-3 space-y-2">
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-slate-500 dark:text-slate-400">
                                <span class="flex items-center gap-1">
                                    <span class="font-bold text-slate-700 dark:text-slate-300">Kategori:</span>
                                    <span class="px-2 py-0.5 rounded-md bg-white/60 dark:bg-white/10 font-medium text-slate-800 dark:text-slate-200">
                                        {{ ucfirst($report->kategori) }}
                                    </span>
                                </span>
                                <span>•</span>
                                <span>{{ $report->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                            </div>

                            <p class="text-slate-700 dark:text-slate-300 bg-white/40 dark:bg-white/5 p-3 rounded-xl border border-white/60 dark:border-white/10">
                                {{ $report->deskripsi }}
                            </p>

                            {{-- Catatan Resolusi Petugas (Jika Ada) --}}
                            @if ($report->catatan_resolusi)
                                <div class="p-3.5 rounded-xl bg-emerald-500/10 dark:bg-emerald-950/30 border border-emerald-400/30 text-emerald-950 dark:text-emerald-200 space-y-1">
                                    <div class="flex items-center gap-1.5 font-bold text-xs text-emerald-800 dark:text-emerald-300">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Catatan Tindak Lanjut Petugas ({{ $report->resolver->name ?? 'Petugas Teknis' }}):</span>
                                    </div>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 pl-5">
                                        {{ $report->catatan_resolusi }}
                                    </p>
                                </div>
                            @elseif ($report->status === 'baru')
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 italic">
                                    * Laporan Anda telah tercatat dan sedang dalam antrean inspeksi teknisi.
                                </p>
                            @endif
                        </div>

                        {{-- Foto Bukti --}}
                        <div class="shrink-0 flex md:justify-end items-start">
                            @if ($report->foto_path)
                                <a href="{{ asset('storage/' . $report->foto_path) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="group block relative rounded-2xl overflow-hidden border border-white/80 dark:border-white/20 shadow-xs hover:border-emerald-500 transition-all">
                                    <img src="{{ asset('storage/' . $report->foto_path) }}"
                                         alt="Bukti Kerusakan"
                                         class="w-24 h-24 sm:w-28 sm:h-28 object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold">
                                        Lihat Foto
                                    </div>
                                </a>
                            @else
                                <div class="w-24 h-24 rounded-2xl bg-white/40 dark:bg-white/5 border border-dashed border-white/60 dark:border-white/10 flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 text-center p-2">
                                    <svg class="w-6 h-6 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[10px]">Tanpa Foto</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 rounded-2xl bg-white/40 dark:bg-white/5 border border-dashed border-white/60 dark:border-white/10 space-y-3">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum Ada Riwayat Laporan</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                        Anda belum pernah mengirim laporan kendala fasilitas. Jika menemukan sarana kampus yang rusak atau tidak berfungsi, silakan gunakan formulir di atas.
                    </p>
                </div>
            @endforelse

            {{-- PAGINATION --}}
            @if ($myReports->hasPages())
                <div class="pt-4 border-t border-white/40 dark:border-white/10">
                    {{ $myReports->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Penghitung Karakter Dinamis Deskripsi
        const descTextarea = document.getElementById('reportDescription');
        const charCounter = document.getElementById('charCounter');

        function updateCharCounter() {
            if (descTextarea && charCounter) {
                const count = descTextarea.value.length;
                charCounter.textContent = `${count} / 2000 karakter`;
            }
        }

        if (descTextarea) {
            descTextarea.addEventListener('input', updateCharCounter);
            updateCharCounter();
        }

        // 2. Validasi Client-Side Foto & Live Image Preview
        const photoInput = document.getElementById('reportPhoto');
        const photoPreviewContainer = document.getElementById('photoPreviewContainer');
        const photoPreviewImg = document.getElementById('photoPreviewImg');
        const photoPreviewName = document.getElementById('photoPreviewName');
        const photoPreviewSize = document.getElementById('photoPreviewSize');
        const photoError = document.getElementById('photoError');
        const photoErrorMessage = document.getElementById('photoErrorMessage');
        const removePhotoBtn = document.getElementById('removePhotoBtn');

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function showPhotoError(message) {
            if (photoErrorMessage) {
                photoErrorMessage.textContent = message;
            }
            if (photoError) {
                photoError.classList.remove('hidden');
            }
            if (photoPreviewContainer) {
                photoPreviewContainer.classList.add('hidden');
            }
            if (photoPreviewImg) {
                photoPreviewImg.src = '';
            }
        }

        function clearPhotoError() {
            if (photoError) {
                photoError.classList.add('hidden');
            }
        }

        function resetPhoto() {
            if (photoInput) {
                photoInput.value = '';
            }
            if (photoPreviewContainer) {
                photoPreviewContainer.classList.add('hidden');
            }
            if (photoPreviewImg) {
                photoPreviewImg.src = '';
            }
            clearPhotoError();
        }

        if (removePhotoBtn) {
            removePhotoBtn.addEventListener('click', function () {
                resetPhoto();
            });
        }

        if (photoInput) {
            photoInput.addEventListener('change', function () {
                clearPhotoError();

                const file = this.files && this.files[0];
                if (!file) {
                    resetPhoto();
                    return;
                }

                // Validasi tipe mime
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type.toLowerCase())) {
                    this.value = '';
                    showPhotoError('Hanya file gambar JPG atau PNG yang diperbolehkan');
                    return;
                }

                // Validasi ukuran maksimal (2MB = 2 * 1024 * 1024 bytes)
                const maxSizeBytes = 2 * 1024 * 1024;
                if (file.size > maxSizeBytes) {
                    this.value = '';
                    showPhotoError('Ukuran file foto melebihi batas maksimal 2MB.');
                    return;
                }

                // Baca file dan tampilkan live preview
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (photoPreviewImg) {
                        photoPreviewImg.src = e.target.result;
                    }
                    if (photoPreviewName) {
                        photoPreviewName.textContent = file.name;
                    }
                    if (photoPreviewSize) {
                        photoPreviewSize.textContent = formatFileSize(file.size);
                    }
                    if (photoPreviewContainer) {
                        photoPreviewContainer.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        // 3. Proteksi Anti-Double Submit
        const reportForm = document.getElementById('reportForm');
        const submitBtn = document.getElementById('submitReportBtn');
        const submitText = document.getElementById('submitReportText');

        if (reportForm && submitBtn) {
            reportForm.addEventListener('submit', function () {
                setTimeout(function () {
                    submitBtn.disabled = true;
                }, 0);
                if (submitText) {
                    submitText.textContent = 'Mengirim laporan...';
                }
            });
        }
    });
</script>
@endsection
