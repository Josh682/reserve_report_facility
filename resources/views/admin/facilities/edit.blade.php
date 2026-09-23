@extends('layouts.admin')

@section('title', 'Edit Fasilitas')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Edit Fasilitas: {{ $facility->nama }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Perbarui detail data, kapasitas, lokasi, atau status operasional fasilitas ini.
            </p>
        </div>
        <a href="{{ route('admin.facilities.index') }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.facilities.update', $facility) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Fasilitas -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nama Fasilitas <span class="text-red-500">*</span>
                </label>
                <div class="mt-1">
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $facility->nama) }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 @error('nama') border-red-500 @enderror"
                           placeholder="Contoh: Auditorium Utama Gedung A, Lab Komputer 1">
                </div>
                @error('nama')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid 2 Kolom: Tipe & Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Tipe Fasilitas -->
                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Jenis / Tipe Fasilitas <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <select id="tipe" name="tipe" required
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 @error('tipe') border-red-500 @enderror">
                            <option value="">-- Pilih Tipe Fasilitas --</option>
                            <option value="ruang_kelas" @selected(old('tipe', $facility->tipe) === 'ruang_kelas')>Ruang Kelas</option>
                            <option value="aula" @selected(old('tipe', $facility->tipe) === 'aula')>Aula</option>
                            <option value="laboratorium" @selected(old('tipe', $facility->tipe) === 'laboratorium')>Laboratorium</option>
                            <option value="alat" @selected(old('tipe', $facility->tipe) === 'alat')>Alat</option>
                            <option value="lapangan" @selected(old('tipe', $facility->tipe) === 'lapangan')>Lapangan</option>
                        </select>
                    </div>
                    @error('tipe')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Operasional -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Status Operasional <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <select id="status" name="status" required
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 @error('status') border-red-500 @enderror">
                            <option value="aktif" @selected(old('status', $facility->status) === 'aktif')>Aktif (Dapat dipinjam)</option>
                            <option value="dalam_perbaikan" @selected(old('status', $facility->status) === 'dalam_perbaikan')>Dalam Perbaikan (Maintenance)</option>
                            <option value="nonaktif" @selected(old('status', $facility->status) === 'nonaktif')>Nonaktif (Tidak tersedia)</option>
                        </select>
                    </div>
                    @error('status')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Grid 2 Kolom: Lokasi & Kapasitas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Lokasi -->
                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Lokasi / Gedung <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $facility->lokasi) }}" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 @error('lokasi') border-red-500 @enderror"
                               placeholder="Contoh: Gedung Rektorat Lt. 3, Sayap Barat">
                    </div>
                    @error('lokasi')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kapasitas -->
                <div>
                    <label for="kapasitas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Kapasitas (Orang / Unit)
                    </label>
                    <div class="mt-1">
                        <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $facility->kapasitas) }}" min="1"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 @error('kapasitas') border-red-500 @enderror"
                               placeholder="Contoh: 50">
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Opsional, kosongkan jika tidak berbasis kapasitas orang.</p>
                    @error('kapasitas')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Deskripsi / Kelengkapan Fasilitas
                </label>
                <div class="mt-1">
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Keterangan fasilitas, kelengkapan proyektor, AC, sound system, atau syarat pemakaian...">{{ old('deskripsi', $facility->deskripsi) }}</textarea>
                </div>
                @error('deskripsi')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.facilities.index') }}"
                   class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold shadow-xs transition-colors">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
