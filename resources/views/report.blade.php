@extends('layouts.pengguna')

@section('title', 'Pelaporan Kendala Fasilitas — FacilityHub')
@section('header_title', 'Lapor Kerusakan')
@section('header_subtitle', 'Laporkan kendala sarana, kebersihan, atau kerusakan fasilitas kampus untuk ditindaklanjuti')

@php
    $facilitiesList = $facilities ?? \App\Models\Facility::where('status', '!=', 'nonaktif')->orderBy('nama')->get();
@endphp

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- ==========================================
         1. HEADER SECTION (FROSTED HERO BANNER)
         ========================================== --}}
    <div class="glass-card-main rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-60 h-60 rounded-full bg-emerald-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 border border-amber-500/20 text-amber-800 dark:text-amber-300 mb-3">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Layanan Aspirasi & Pemeliharaan Sarana</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                Pelaporan Kerusakan Fasilitas
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-300 max-w-xl leading-relaxed">
                Menemukan AC rusak, proyektor mati, atau sarana yang kotor? Sampaikan laporan Anda agar segera diperbaiki oleh tim teknisi kampus.
            </p>
        </div>

        <div class="relative z-10 shrink-0">
            <a href="{{ route('pengguna.facilities') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-white/70 dark:bg-white/10 hover:bg-white/90 dark:hover:bg-white/20 transition-all border border-white/80 dark:border-white/15 shadow-xs">
                <svg class="w-4 h-4 text-[#0F5143] dark:text-[#34D399]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Cek Daftar Fasilitas</span>
            </a>
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
                <strong class="font-bold block mb-0.5">Sukses Terkirim!</strong>
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
         2. FORMULIR & PANDUAN PELAPORAN
         ========================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        {{-- FORMULIR PENGADUAN (FROSTED GLASS CARD) --}}
        <div class="lg:col-span-2 glass-card-main rounded-3xl p-6 sm:p-8">
            <div class="flex items-center justify-between pb-5 border-b border-white/40 dark:border-white/10 mb-6">
                <div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-800 dark:text-white tracking-tight">
                        Formulir Pengaduan Sarana
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Lengkapi rincian kendala beserta foto bukti untuk mempercepat proses tindak lanjut.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Fasilitas Terkait --}}
                    <div>
                        <label for="facility_id" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5 uppercase tracking-wider">
                            Fasilitas / Ruangan Terkait <span class="text-rose-500">*</span>
                        </label>
                        <select id="facility_id" name="facility_id" required class="w-full kezak-input px-3.5 py-2.5 text-xs sm:text-sm">
                            <option value="">-- Pilih Fasilitas Kampus --</option>
                            @foreach ($facilitiesList as $fac)
                                <option value="{{ $fac->id }}" @selected(old('facility_id') == $fac->id)>
                                    {{ $fac->nama }} ({{ $fac->lokasi }})
                                </option>
                            @endforeach
                        </select>
                        @error('facility_id')
                            <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori Kendala --}}
                    <div>
                        <label for="category" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5 uppercase tracking-wider">
                            Kategori Masalah <span class="text-rose-500">*</span>
                        </label>
                        <select id="category" name="category" required class="w-full kezak-input px-3.5 py-2.5 text-xs sm:text-sm">
                            <option value="kerusakan" @selected(old('category') === 'kerusakan')>Kerusakan Alat / Fasilitas Fisik</option>
                            <option value="kebersihan" @selected(old('category') === 'kebersihan')>Kebersihan Ruangan / Lingkungan</option>
                            <option value="lainnya" @selected(old('category') === 'lainnya')>Kendala Lainnya</option>
                        </select>
                        @error('category')
                            <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Deskripsi Kendala --}}
                <div>
                    <label for="description" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5 uppercase tracking-wider">
                        Deskripsi Detail Kerusakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="4" required placeholder="Jelaskan secara spesifik lokasi kendala, barang yang rusak, atau dampak yang ditimbulkan..." class="w-full kezak-input px-3.5 py-2.5 text-xs sm:text-sm resize-none">{{ old('description') }}</textarea>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                        Minimal 5 karakter. Berikan penjelasan yang jelas agar teknisi dapat menyiapkan peralatan yang tepat.
                    </p>
                    @error('description')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Unggah Foto Bukti --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5 uppercase tracking-wider">
                        Unggah Foto Bukti (Opsional)
                    </label>
                    <input type="file" name="photo" accept="image/jpeg,image/png" class="w-full kezak-input px-3.5 py-2 text-xs sm:text-sm file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-[#0F5143] dark:file:bg-emerald-950/60 dark:file:text-[#34D399] hover:file:bg-emerald-200 cursor-pointer">
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                        Format: JPG, JPEG, atau PNG (Maksimal 2MB).
                    </p>
                    @error('photo')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end pt-3">
                    <button type="submit" class="kezak-btn-primary px-6 py-2.5 text-xs sm:text-sm font-bold shadow-md cursor-pointer inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <span>Kirim Laporan Kerusakan</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- SIDEBAR INFORMASI ALUR PENANGANAN --}}
        <div class="space-y-4">
            <div class="p-6 rounded-3xl glass-card-main space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-2xl bg-teal-500/15 dark:bg-teal-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shadow-2xs">
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
         3. RIWAYAT & PELACAKAN LAPORAN SAYA (US 7)
         ========================================== --}}
    <div class="glass-card-main rounded-3xl p-6 sm:p-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/40 dark:border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-teal-500/15 dark:bg-teal-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-800 dark:text-white tracking-tight">
                        Pelacakan Status Laporan Saya
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Pantau perkembangan tindak lanjut keluhan sarana yang telah Anda ajukan
                    </p>
                </div>
            </div>
        </div>

        {{-- DAFTAR LAPORAN --}}
        <div class="space-y-4">
            @forelse ($myReports ?? [] as $report)
                <div class="p-5 sm:p-6 rounded-2xl glass-card-nested border border-white/70 dark:border-white/10 space-y-4 shadow-2xs">
                    {{-- Header Kartu Laporan --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-white/40 dark:border-white/10">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono font-bold text-slate-400 dark:text-slate-500">
                                    #REP-{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-slate-300 dark:text-slate-600">•</span>
                                <h3 class="text-sm font-extrabold text-slate-800 dark:text-white">
                                    {{ $report->facility->nama ?? 'Fasilitas' }}
                                </h3>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                {{ $report->facility->lokasi ?? '-' }}
                            </p>
                        </div>

                        {{-- Badge Status --}}
                        <div>
                            @if ($report->status === 'baru')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-500/15 text-sky-800 dark:text-sky-300 border border-sky-400/30">
                                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                                    <span>Baru / Menunggu Verifikasi</span>
                                </span>
                            @elseif ($report->status === 'diproses')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-800 dark:text-amber-300 border border-amber-400/30">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    <span>Sedang Ditangani</span>
                                </span>
                            @elseif ($report->status === 'selesai')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 border border-emerald-400/30">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>Selesai Diperbaiki</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-500/15 text-rose-800 dark:text-rose-300 border border-rose-400/30">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span>Laporan Ditolak</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Metadata & Deskripsi --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                        <div class="md:col-span-3 space-y-2.5">
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

                            <p class="text-slate-700 dark:text-slate-300 bg-white/40 dark:bg-white/5 p-3.5 rounded-xl border border-white/60 dark:border-white/10 leading-relaxed">
                                {{ $report->deskripsi }}
                            </p>

                            {{-- Catatan Resolusi Petugas (Jika Ada) --}}
                            @if ($report->catatan_resolusi)
                                <div class="p-3.5 rounded-xl bg-emerald-500/10 dark:bg-emerald-950/30 border border-emerald-400/30 text-emerald-950 dark:text-emerald-200 space-y-1">
                                    <div class="flex items-center gap-1.5 font-bold text-xs text-[#0F5143] dark:text-[#34D399]">
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
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-teal-500/15 dark:bg-teal-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shadow-2xs">
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
            @if (isset($myReports) && $myReports->hasPages())
                <div class="pt-4 border-t border-white/40 dark:border-white/10">
                    {{ $myReports->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
