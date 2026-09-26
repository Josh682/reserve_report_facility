@extends('layouts.admin')

@section('title', 'Edit Fasilitas')
@section('header_title', 'Edit Fasilitas')

@section('content')
<div class="w-full space-y-6">
    <!-- Form Card with Frosted Glassmorphism (Sized same as facility list) -->
    <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-8 relative">
        <!-- Tombol Back Kecil di Pojok Kanan Atas dalam Form (Icon Only) -->
        <a href="{{ route('admin.facilities.index') }}"
           class="absolute top-5 right-5 sm:top-6 sm:right-6 w-9 h-9 rounded-xl bg-white/80 hover:bg-emerald-50/80 border border-emerald-400 hover:border-emerald-500 text-emerald-800 hover:text-emerald-900 flex items-center justify-center transition-all shadow-xs group"
           title="Kembali ke Daftar">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="sr-only">Kembali ke Daftar</span>
        </a>

        <form method="POST" action="{{ route('admin.facilities.update', $facility) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Fasilitas -->
            <div class="pr-12 sm:pr-14">
                <label for="nama" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                    Nama Fasilitas <span class="text-red-500">*</span>
                </label>
                <div>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $facility->nama) }}" required
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm kezak-input placeholder-slate-400 @error('nama') border-red-500 @enderror"
                           placeholder="Contoh: Auditorium Utama Gedung A, Lab Komputer Terpadu 1">
                </div>
                @error('nama')
                    <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid 2 Kolom: Tipe & Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Tipe Fasilitas -->
                <div>
                    <label for="tipe" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                        Jenis / Tipe Fasilitas <span class="text-red-500">*</span>
                    </label>
                    <div>
                        <select id="tipe" name="tipe" required
                                class="w-full px-3.5 py-2.5 text-xs sm:text-sm kezak-input font-medium @error('tipe') border-red-500 @enderror">
                            <option value="">-- Pilih Tipe Fasilitas --</option>
                            <option value="ruang_kelas" @selected(old('tipe', $facility->tipe) === 'ruang_kelas')>Ruang Kelas</option>
                            <option value="aula" @selected(old('tipe', $facility->tipe) === 'aula')>Aula</option>
                            <option value="laboratorium" @selected(old('tipe', $facility->tipe) === 'laboratorium')>Laboratorium</option>
                            <option value="alat" @selected(old('tipe', $facility->tipe) === 'alat')>Alat</option>
                            <option value="lapangan" @selected(old('tipe', $facility->tipe) === 'lapangan')>Lapangan</option>
                        </select>
                    </div>
                    @error('tipe')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Operasional -->
                <div>
                    <label for="status" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                        Status Operasional <span class="text-red-500">*</span>
                    </label>
                    <div>
                        <select id="status" name="status" required
                                class="w-full px-3.5 py-2.5 text-xs sm:text-sm kezak-input font-medium @error('status') border-red-500 @enderror">
                            <option value="aktif" @selected(old('status', $facility->status) === 'aktif')>Aktif</option>
                            <option value="dalam_perbaikan" @selected(old('status', $facility->status) === 'dalam_perbaikan')>Dalam Perbaikan</option>
                            <option value="nonaktif" @selected(old('status', $facility->status) === 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>
                    @error('status')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Grid 2 Kolom: Lokasi & Kapasitas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Lokasi Gedung / Lantai -->
                <div>
                    <label for="lokasi" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                        Lokasi Gedung / Lantai <span class="text-red-500">*</span>
                    </label>
                    <div>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $facility->lokasi) }}" required
                               class="w-full px-3.5 py-2.5 text-xs sm:text-sm kezak-input placeholder-slate-400 @error('lokasi') border-red-500 @enderror"
                               placeholder="Contoh: Gedung Rektorat Lt. 3, Sayap Barat">
                    </div>
                    @error('lokasi')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kapasitas -->
                <div>
                    <label for="kapasitas" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                        Kapasitas (Orang / Unit)
                    </label>
                    <div>
                        <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $facility->kapasitas) }}" min="1"
                               class="w-full px-3.5 py-2.5 text-xs sm:text-sm kezak-input placeholder-slate-400 @error('kapasitas') border-red-500 @enderror"
                               placeholder="Contoh: 50">
                    </div>
                    <p class="mt-1 text-[11px] text-slate-500">Kosongkan jika fasilitas berupa alat bergerak.</p>
                    @error('kapasitas')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Fasilitas Pendukung / Deskripsi -->
            <div>
                <label for="deskripsi" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                    Fasilitas Pendukung & Kelengkapan
                </label>
                <div>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                              class="w-full px-3.5 py-2.5 text-xs sm:text-sm kezak-input placeholder-slate-400 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Deskripsikan sarana pendukung (AC, Proyektor HDMI, Sound System, Mic Wireless, Whiteboard, dll.)...">{{ old('deskripsi', $facility->deskripsi) }}</textarea>
                </div>
                @error('deskripsi')
                    <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/40">
                <a href="{{ route('admin.facilities.index') }}"
                   class="px-4 py-2.5 rounded-xl bg-white/70 hover:bg-white/95 border border-white/80 text-slate-700 text-xs sm:text-sm font-semibold transition-all shadow-2xs">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 kezak-btn-primary text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
