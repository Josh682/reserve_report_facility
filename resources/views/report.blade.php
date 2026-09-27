@extends('layouts.app')

@section('title', 'Pelaporan Kendala Fasilitas — Portal Fasilitas')
@section('page-title', 'Pelaporan Kendala & Kerusakan Fasilitas')

@section('content')
@php
    $dbFacilities = \App\Models\Facility::all();
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

<div class="space-y-6 max-w-5xl">

    {{-- HEADER BANNER (RAYCAST ACCENTED SWISS MINIMAL) --}}
    <div class="p-6 rounded-xs bg-white dark:bg-[#0c1419] raycast-card flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded-xs text-[11px] font-mono uppercase tracking-wider bg-teal-50 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/60 dark:border-teal-800/40 mb-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-xs bg-teal-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-xs h-2 w-2 bg-teal-500"></span>
                </span>
                <span>Layanan Sarana & Prasarana Kampus</span>
            </div>
            <h1 class="text-xl font-black uppercase tracking-tight text-slate-900 dark:text-white">
                Form Pelaporan Kendala Fasilitas
            </h1>
            <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 max-w-2xl leading-relaxed">
                Laporkan kerusakan alat, kendala kebersihan, atau gangguan fungsi ruangan kampus agar tim teknisi dapat segera melakukan inspeksi dan perbaikan.
            </p>
        </div>

        <a href="{{ route('facilities') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xs text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shrink-0 border border-slate-200 dark:border-slate-700">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Katalog Fasilitas</span>
        </a>
    </div>

    {{-- ALERT STATUS NOTIFIKASI --}}
    @if (session('status'))
        <div class="p-4 rounded-xs bg-teal-50 dark:bg-teal-950/40 border border-teal-200 dark:border-teal-800 shadow-none flex items-start gap-3">
            <svg class="w-5 h-5 text-teal-600 dark:text-teal-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="text-xs font-mono uppercase font-bold text-teal-900 dark:text-teal-200">Laporan Berhasil Terkirim</p>
                <p class="text-xs text-teal-800/80 dark:text-teal-300/80 mt-0.5">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- FORMULIR UTAMA (2 KOLOM) --}}
        <div class="lg:col-span-2">
            <div class="p-6 sm:p-7 rounded-xs bg-white dark:bg-[#0c1419] raycast-card">
                <div class="pb-4 mb-6 border-b border-slate-100 dark:border-slate-800/80">
                    <h2 class="text-base font-bold uppercase tracking-tight text-slate-900 dark:text-white">Detail Kerusakan / Kendala</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Isi formulir berikut dengan spesifik untuk mempercepat tindakan petugas di lapangan.
                    </p>
                </div>

                <form method="POST" action="{{ url('/reports') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Kategori Fasilitas --}}
                        <div>
                            <label for="reportFacilityCategory" class="block text-[11px] font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                                Kategori Fasilitas <span class="text-rose-500">*</span>
                            </label>
                            <select id="reportFacilityCategory"
                                    name="facility_category"
                                    required
                                    class="block w-full px-3 py-2 rounded-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-colors">
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
                            <label for="reportFacilitySpec" class="block text-[11px] font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                                Spesifikasi / Nama Ruangan <span class="text-rose-500">*</span>
                            </label>
                            <select id="reportFacilitySpec"
                                    name="facility_name"
                                    required
                                    disabled
                                    class="block w-full px-3 py-2 rounded-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs focus:ring-1 focus:ring-teal-500 focus:border-teal-500 disabled:opacity-60 disabled:cursor-not-allowed transition-colors">
                                <option value="">-- Pilih Kategori Terlebih Dahulu --</option>
                            </select>
                        </div>
                    </div>

                    {{-- Jenis Kendala --}}
                    <div>
                        <label for="reportCategory" class="block text-[11px] font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            Jenis Masalah / Kendala <span class="text-rose-500">*</span>
                        </label>
                        <select id="reportCategory"
                                name="category"
                                required
                                class="block w-full px-3 py-2 rounded-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                            <option value="kerusakan">Kerusakan Fisik / Alat Tidak Berfungsi</option>
                            <option value="kebersihan">Masalah Kebersihan / Ruangan Kotor / Toilet</option>
                            <option value="lainnya">Kendala Operasional Lainnya</option>
                        </select>
                    </div>

                    {{-- Deskripsi Masalah --}}
                    <div>
                        <label for="reportDescription" class="block text-[11px] font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            Deskripsi Rinci Kendala <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="reportDescription"
                                  name="description"
                                  rows="4"
                                  required
                                  placeholder="Jelaskan secara spesifik kerusakan atau kendala yang dialami, misalnya: AC tidak dingin, proyektor bergaris ungu, stopkontak meja depan kendor..."
                                  class="block w-full px-3 py-2 rounded-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-xs focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-colors resize-y"></textarea>
                    </div>

                    {{-- Unggah Foto Bukti --}}
                    <div>
                        <label for="reportPhoto" class="block text-[11px] font-mono uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            Foto Bukti Pendukung <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                        </label>
                        <input id="reportPhoto"
                               name="photo"
                               type="file"
                               accept="image/png, image/jpeg, image/webp"
                               class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-3 file:rounded-xs file:border-0 file:text-[11px] file:font-mono file:font-semibold file:uppercase file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 dark:file:bg-teal-950/60 dark:file:text-teal-300 dark:hover:file:bg-teal-900/50 cursor-pointer border border-slate-300 dark:border-slate-700 rounded-xs bg-white dark:bg-slate-900 p-1.5 focus:outline-none focus:ring-1 focus:ring-teal-500 transition-colors">
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1.5">
                            Format JPG, PNG, atau WEBP (maksimal 5MB). Lampirkan foto pendukung untuk mempercepat proses identifikasi kendala oleh petugas teknisi.
                        </p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-end gap-3">
                        <a href="{{ route('facilities') }}"
                           class="inline-flex items-center justify-center px-4 py-2 rounded-xs text-xs font-mono uppercase tracking-wider text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors border border-slate-200 dark:border-slate-700">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-xs text-xs font-semibold uppercase tracking-wider text-white bg-teal-700 hover:bg-teal-800 dark:bg-teal-600 dark:hover:bg-teal-500 shadow-none transition-colors cursor-pointer border-t border-white/20">
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
            <div class="p-6 rounded-xs bg-white dark:bg-[#0c1419] raycast-card">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="p-2 rounded-xs bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-400 border border-teal-200/60 dark:border-teal-800/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-900 dark:text-white">Alur Penanganan</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Prosedur tindak lanjut laporan</p>
                    </div>
                </div>

                <ol class="space-y-3.5 text-xs text-slate-600 dark:text-slate-400">
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono font-bold flex items-center justify-center shrink-0 text-[11px] border border-slate-200 dark:border-slate-700">1</span>
                        <div>
                            <strong class="text-slate-900 dark:text-slate-200 block text-xs">Laporan Diterima</strong>
                            Petugas menerima notifikasi keluhan fasilitas yang masuk ke sistem.
                        </div>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono font-bold flex items-center justify-center shrink-0 text-[11px] border border-slate-200 dark:border-slate-700">2</span>
                        <div>
                            <strong class="text-slate-900 dark:text-slate-200 block text-xs">Verifikasi Teknis</strong>
                            Petugas melakukan pengecekan lapangan dan menentukan skala perbaikan.
                        </div>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono font-bold flex items-center justify-center shrink-0 text-[11px] border border-slate-200 dark:border-slate-700">3</span>
                        <div>
                            <strong class="text-slate-900 dark:text-slate-200 block text-xs">Perbaikan & Pembaruan Status</strong>
                            Jika perbaikan butuh waktu, status fasilitas dialihkan ke status pemeliharaan.
                        </div>
                    </li>
                </ol>
            </div>

            <div class="p-5 rounded-xs bg-teal-50/60 dark:bg-teal-950/20 raycast-card text-xs text-teal-900 dark:text-teal-200">
                <div class="font-bold flex items-center gap-1.5 mb-1 text-teal-800 dark:text-teal-300 font-mono text-[11px] uppercase tracking-wider">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Keadaan Darurat?</span>
                </div>
                <p class="text-slate-600 dark:text-slate-400 text-[11px] leading-relaxed">
                    Untuk korsleting listrik, pipa bocor deras, atau situasi darurat yang membahayakan fasilitas, segera hubungi petugas jaga kampus.
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
