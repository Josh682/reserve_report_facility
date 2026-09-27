@extends('layouts.pengguna')

@section('title', 'Pelaporan Kendala Fasilitas — FacilityHub')
@section('header_title', 'Laporan Kendala Fasilitas')
@section('header_subtitle', 'Laporkan kerusakan sarana prasarana, kendala kebersihan, atau gangguan fungsi ruangan kampus')

@section('content')
@php
    $dbFacilities = \App\Models\Facility::where('status', '!=', 'nonaktif')->get();
    $categoriesMapping = [
        'ruang_kelas' => 'Ruang Kelas',
        'aula' => 'Aula',
        'laboratorium' => 'Laboratorium',
        'alat' => 'Alat',
        'lapangan' => 'Lapangan',
    ];
    $facilityOptions = [
        'Ruang Kelas' => ['E103', 'A301', 'A302', 'A303', 'A304', 'A305'],
        'Aula' => ['Aula A', 'Aula B'],
        'Laboratorium' => ['Lab A', 'Lab B', 'Lab C', 'Lab D'],
        'Alat' => ['Meja Kelas', 'Meja Panjang', 'Kursi Kelas', 'Kursi Panjang', 'Sound System', 'Mic', 'TV'],
        'Lapangan' => ['Lapangan Voli', 'Lapangan Basket'],
    ];

    foreach ($dbFacilities as $facility) {
        $categoryName = $categoriesMapping[$facility->tipe] ?? ucfirst(str_replace('_', ' ', (string) $facility->tipe));
        if (!isset($facilityOptions[$categoryName])) {
            $facilityOptions[$categoryName] = [];
        }
        if (!in_array($facility->nama, $facilityOptions[$categoryName], true)) {
            $facilityOptions[$categoryName][] = $facility->nama;
        }
    }
@endphp

<div class="space-y-6 sm:space-y-8">

    {{-- ==========================================
         1. HEADER BANNER (FROSTED GLASS HERO)
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

    {{-- GRID KONTEN --}}
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

                <form method="POST" action="{{ url('/reports') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Kategori Fasilitas --}}
                        <div>
                            <label for="reportFacilityCategory" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                                Kategori Fasilitas <span class="text-rose-500">*</span>
                            </label>
                            <select id="reportFacilityCategory"
                                    name="facility_category"
                                    required
                                    class="kezak-input block w-full px-3.5 py-2.5 text-xs sm:text-sm">
                                <option value="">-- Pilih Fasilitas --</option>
                                <option value="Ruang Kelas">Ruang Kelas</option>
                                <option value="Aula">Aula</option>
                                <option value="Laboratorium">Laboratorium</option>
                                <option value="Alat">Alat / Perlengkapan</option>
                                <option value="Lapangan">Lapangan</option>
                            </select>
                        </div>

                        {{-- Spesifikasi / Nama Fasilitas --}}
                        <div>
                            <label for="reportFacilitySpec" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                                Spesifikasi / Nama Ruangan <span class="text-rose-500">*</span>
                            </label>
                            <select id="reportFacilitySpec"
                                    name="facility_name"
                                    required
                                    disabled
                                    class="kezak-input block w-full px-3.5 py-2.5 text-xs sm:text-sm disabled:opacity-60 disabled:cursor-not-allowed">
                                <option value="">-- Pilih Kategori Terlebih Dahulu --</option>
                            </select>
                        </div>
                    </div>

                    {{-- Jenis Kendala --}}
                    <div>
                        <label for="reportCategory" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Jenis Masalah / Kendala <span class="text-rose-500">*</span>
                        </label>
                        <select id="reportCategory"
                                name="category"
                                required
                                class="kezak-input block w-full px-3.5 py-2.5 text-xs sm:text-sm">
                            <option value="kerusakan">Kerusakan Fisik / Alat Tidak Berfungsi</option>
                            <option value="kebersihan">Masalah Kebersihan / Ruangan Kotor / Toilet</option>
                            <option value="lainnya">Kendala Operasional Lainnya</option>
                        </select>
                    </div>

                    {{-- Deskripsi Masalah --}}
                    <div>
                        <label for="reportDescription" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Deskripsi Rinci Kendala <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="reportDescription"
                                  name="description"
                                  rows="4"
                                  required
                                  placeholder="Jelaskan secara spesifik kerusakan atau kendala yang dialami, misalnya: AC tidak dingin, proyektor bergaris ungu, stopkontak meja depan kendor..."
                                  class="kezak-input block w-full px-3.5 py-2.5 text-xs sm:text-sm resize-y"></textarea>
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
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="pt-4 border-t border-white/40 dark:border-white/10 flex items-center justify-end gap-3">
                        <a href="{{ route('facilities') }}"
                           class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-white transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                                class="kezak-btn-primary inline-flex items-center justify-center gap-2 px-6 py-2.5 text-xs sm:text-sm font-bold shadow-md cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span>Kirim Laporan</span>
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
                            <strong class="text-slate-900 dark:text-white block text-xs">Verifikasi Teknis</strong>
                            Petugas melakukan pengecekan lapangan dan menentukan skala perbaikan.
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-xl bg-white/80 dark:bg-white/10 text-slate-800 dark:text-slate-200 font-extrabold flex items-center justify-center shrink-0 text-xs border border-white/90 dark:border-white/15 shadow-2xs">3</span>
                        <div>
                            <strong class="text-slate-900 dark:text-white block text-xs">Perbaikan & Pembaruan Status</strong>
                            Jika perbaikan butuh waktu, status fasilitas dialihkan ke status pemeliharaan.
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
                    Untuk korsleting listrik, pipa bocor deras, atau situasi darurat yang membahayakan fasilitas, segera hubungi pos petugas jaga kampus.
                </p>
            </div>
        </div>

    </div>

</div>

<script>
    const reportFacilityOptions = @json($facilityOptions);

    const reportFacilityCategory = document.getElementById('reportFacilityCategory');
    const reportFacilitySpec = document.getElementById('reportFacilitySpec');

    function updateReportSpecOptions() {
        const category = reportFacilityCategory.value;
        const options = reportFacilityOptions[category] || [];

        reportFacilitySpec.innerHTML = '<option value="">-- Pilih spesifikasi / ruangan --</option>' +
            options.map(item => `<option value="${item}">${item}</option>`).join('');

        reportFacilitySpec.disabled = options.length === 0;
    }

    reportFacilityCategory.addEventListener('change', updateReportSpecOptions);
</script>
@endsection
