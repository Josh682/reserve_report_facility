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
    <div class="bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-60 h-60 rounded-full bg-amber-400/15 blur-3xl pointer-events-none"></div>

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

    {{-- ==========================================
         2. FORMULIR PELAPORAN KENDALA (FROSTED GLASS)
         ========================================== --}}
    <div class="bg-white/70 dark:bg-white/5 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-[0_15px_40px_rgba(15,81,67,0.08)] rounded-3xl p-6 sm:p-8">
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

        <form method="POST" action="/reports" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Fasilitas Terkait --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        Fasilitas / Ruangan Terkait <span class="text-rose-500">*</span>
                    </label>
                    <select name="facility_id" required class="w-full kezak-input px-3.5 py-2.5 text-xs sm:text-sm">
                        <option value="">-- Pilih Fasilitas Kampus --</option>
                        @foreach ($facilitiesList as $fac)
                            <option value="{{ $fac->id }}">
                                {{ $fac->nama }} ({{ $fac->lokasi }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kategori Kendala --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        Kategori Masalah <span class="text-rose-500">*</span>
                    </label>
                    <select name="category" required class="w-full kezak-input px-3.5 py-2.5 text-xs sm:text-sm">
                        <option value="kerusakan">Kerusakan Alat / Fasilitas Fisik</option>
                        <option value="kebersihan">Kebersihan Ruangan / Lingkungan</option>
                        <option value="lainnya">Kendala Lainnya</option>
                    </select>
                </div>
            </div>

            {{-- Deskripsi Kendala --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                    Deskripsi Detail Kerusakan <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" rows="4" required placeholder="Jelaskan secara spesifik lokasi kendala, barang yang rusak, atau dampak yang ditimbulkan..." class="w-full kezak-input px-3.5 py-2.5 text-xs sm:text-sm resize-none"></textarea>
            </div>

            {{-- Unggah Foto Bukti --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                    Unggah Foto Bukti (Opsional)
                </label>
                <input type="file" name="photo" accept="image/jpeg,image/png" class="w-full kezak-input px-3.5 py-2 text-xs sm:text-sm file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-[#0F5143] dark:file:bg-emerald-950/60 dark:file:text-[#34D399] hover:file:bg-emerald-200 cursor-pointer">
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                    Format: JPG, JPEG, atau PNG (Maksimal 2MB).
                </p>
            </div>

            <div class="flex items-center justify-end pt-3">
                <button type="submit" class="kezak-btn-primary px-6 py-2.5 text-xs sm:text-sm font-bold shadow-md cursor-pointer">
                    Kirim Laporan Kerusakan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
