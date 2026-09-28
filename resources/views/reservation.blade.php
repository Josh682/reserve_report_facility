@extends('layouts.pengguna')

@section('title', 'Peminjaman Fasilitas — FacilityHub')
@section('header_title', 'Reservasi Fasilitas')
@section('header_subtitle', 'Ajukan peminjaman ruangan kampus dan pantau riwayat pengajuan peminjaman Anda')

@php
    $facilitiesList = $facilities ?? \App\Models\Facility::where('status', 'aktif')->orderBy('nama')->get();
@endphp

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- ==========================================
         1. HEADER & TOMBOL AKSI UTAMA (FROSTED HERO)
         ========================================== --}}
    <div class="bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-5 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-60 h-60 rounded-full bg-emerald-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 border border-emerald-500/20 text-[#0F5143] dark:text-[#34D399] mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Portal Peminjaman Ruang & Lab</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                Peminjaman Fasilitas Saya
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-300 max-w-xl leading-relaxed">
                Ajukan peminjaman fasilitas kampus untuk keperluan kegiatan akademik, seminar, atau organisasi.
            </p>
        </div>

        <div class="relative z-10 shrink-0">
            <button type="button"
                    id="toggleReservationForm"
                    onclick="toggleForm()"
                    class="kezak-btn-primary inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-bold shadow-md cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span id="toggleButtonText">Buat Pengajuan Baru</span>
            </button>
        </div>
    </div>

    {{-- ==========================================
         2. FORMULIR PENGAJUAN RESERVASI BARU (ACCORDION/EXPANDABLE)
         ========================================== --}}
    <div id="reservationFormWrapper" class="hidden">
        <div class="bg-white/70 dark:bg-white/5 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-[0_15px_40px_rgba(15,81,67,0.08)] rounded-3xl p-6 sm:p-8">
            <div class="flex items-center justify-between pb-5 border-b border-white/40 dark:border-white/10 mb-6">
                <div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-800 dark:text-white tracking-tight">
                        Formulir Permohonan Peminjaman Fasilitas
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Isi data kegiatan dan waktu penggunaan fasilitas sesuai ketentuan jam operasional kampus.
                    </p>
                </div>
                <button type="button" onclick="toggleForm()" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="/reservations" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Fasilitas --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                            Fasilitas / Ruangan <span class="text-rose-500">*</span>
                        </label>
                        <select name="facility_id" required class="w-full kezak-input px-3.5 py-2.5 text-xs sm:text-sm">
                            <option value="">-- Pilih Fasilitas Kampus --</option>
                            @foreach ($facilitiesList as $fac)
                                <option value="{{ $fac->id }}">
                                    {{ $fac->nama }} ({{ ucwords(str_replace('_', ' ', $fac->tipe)) }} — {{ $fac->lokasi }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                            Tanggal Penggunaan <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="tanggal" required min="{{ now()->toDateString() }}" class="w-full kezak-input px-3.5 py-2.5 text-xs sm:text-sm">
                    </div>

                    {{-- Jam Mulai --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                            Jam Mulai (07.00 - 20.00 WIB) <span class="text-rose-500">*</span>
                        </label>
                        <input type="time" name="start_time" step="1800" min="07:00" max="20:00" required class="w-full kezak-input px-3.5 py-2.5 text-xs sm:text-sm">
                    </div>

                    {{-- Jam Selesai --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                            Jam Selesai (07.00 - 20.00 WIB) <span class="text-rose-500">*</span>
                        </label>
                        <input type="time" name="end_time" step="1800" min="07:00" max="20:00" required class="w-full kezak-input px-3.5 py-2.5 text-xs sm:text-sm">
                    </div>
                </div>

                {{-- Tujuan Penggunaan --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        Tujuan Penggunaan & Rincian Kegiatan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="tujuan_penggunaan" rows="3" required placeholder="Tuliskan tujuan peminjaman, nama kegiatan, estimasi peserta, dsb..." class="w-full kezak-input px-3.5 py-2.5 text-xs sm:text-sm resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <button type="button" onclick="toggleForm()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="kezak-btn-primary px-6 py-2.5 text-xs sm:text-sm font-bold shadow-md cursor-pointer">
                        Kirim Pengajuan Reservasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==========================================
         3. DAFTAR RIWAYAT RESERVASI (FROSTED GLASS CONTAINER)
         ========================================== --}}
    <div class="bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-7">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Riwayat Pengajuan Peminjaman
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Daftar permohonan reservasi fasilitas kampus yang telah Anda ajukan
                </p>
            </div>
        </div>

        <div class="space-y-4">
            {{-- Card Demo 1: Disetujui --}}
            <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 border border-white/70 dark:border-white/10 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h3 class="font-extrabold text-base text-slate-800 dark:text-white">Ruang Kelas A101</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                            Disetujui
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                        <span>Gedung Perkuliahan Lantai 1</span>
                        <span>•</span>
                        <span>25 September 2026 • 09.00 - 10.30 WIB</span>
                    </p>
                    <p class="text-xs text-slate-600 dark:text-slate-300 font-medium pt-1">
                        Kegiatan: Diskusi Kelompok Belajar & Praktikum
                    </p>
                </div>
                <div class="shrink-0">
                    <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Telah Dikonfirmasi Petugas
                    </span>
                </div>
            </div>

            {{-- Card Demo 2: Menunggu --}}
            <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 border border-white/70 dark:border-white/10 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h3 class="font-extrabold text-base text-slate-800 dark:text-white">Laboratorium Komputer 2</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">
                            Menunggu Verifikasi
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                        <span>Gedung Lab Terpadu Lantai 2</span>
                        <span>•</span>
                        <span>27 September 2026 • 13.30 - 15.00 WIB</span>
                    </p>
                    <p class="text-xs text-slate-600 dark:text-slate-300 font-medium pt-1">
                        Kegiatan: Sesi Belajar Mandiri & Coding Lab
                    </p>
                </div>
                <div class="shrink-0">
                    <span class="text-xs font-semibold text-amber-700 dark:text-amber-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Dalam Antrean Verifikasi
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function toggleForm() {
        const wrapper = document.getElementById('reservationFormWrapper');
        const btnText = document.getElementById('toggleButtonText');
        if (wrapper.classList.contains('hidden')) {
            wrapper.classList.remove('hidden');
            if (btnText) btnText.textContent = 'Tutup Formulir';
            wrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            wrapper.classList.add('hidden');
            if (btnText) btnText.textContent = 'Buat Pengajuan Baru';
        }
    }
</script>
@endsection
