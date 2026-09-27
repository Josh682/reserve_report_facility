@extends('layouts.admin')

@section('title', 'Tambah Akun Baru')
@section('header_title', 'Tambah Akun Baru')

@section('content')
<div class="w-full space-y-6">
    <!-- Form Card with Frosted Glassmorphism (Sized same as facility form) -->
    <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-8 relative">
        <!-- Tombol Back Kecil di Pojok Kanan Atas dalam Form (Icon Only with Light Green Border) -->
        <a href="{{ route('admin.users.index') }}"
           class="absolute top-5 right-5 sm:top-6 sm:right-6 w-9 h-9 rounded-xl bg-white/80 hover:bg-emerald-50/80 border border-emerald-400 hover:border-emerald-500 text-emerald-800 hover:text-emerald-900 flex items-center justify-center transition-all shadow-xs group"
           title="Kembali ke Daftar">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="sr-only">Kembali ke Daftar</span>
        </a>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <!-- Role Selection -->
            <div>
                <label class="block text-xs sm:text-sm font-bold text-slate-800 mb-2 pr-12 sm:pr-14">
                    Role Akun <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Option 1: Petugas -->
                    <label class="relative flex items-center p-4 rounded-2xl border transition-all cursor-pointer {{ old('role') === 'petugas' ? 'border-emerald-500 bg-emerald-50/80 ring-2 ring-emerald-500/20' : 'border-white/80 bg-white/70 hover:bg-emerald-50/40' }} shadow-2xs">
                        <input type="radio"
                               name="role"
                               value="petugas"
                               {{ old('role') === 'petugas' ? 'checked' : '' }}
                               onchange="toggleTipePengguna(this.value)"
                               class="text-[#0F5143] focus:ring-[#10B981] h-4 w-4">
                        <div class="ml-3">
                            <span class="block text-xs sm:text-sm font-bold text-slate-900">Petugas Fasilitas</span>
                            <span class="block text-[11px] text-slate-600">Pengelola reservasi, validasi laporan, & jadwal operasional</span>
                        </div>
                    </label>

                    <!-- Option 2: Pengguna -->
                    <label class="relative flex items-center p-4 rounded-2xl border transition-all cursor-pointer {{ old('role', 'pengguna') === 'pengguna' ? 'border-emerald-500 bg-emerald-50/80 ring-2 ring-emerald-500/20' : 'border-white/80 bg-white/70 hover:bg-emerald-50/40' }} shadow-2xs">
                        <input type="radio"
                               name="role"
                               value="pengguna"
                               {{ old('role', 'pengguna') === 'pengguna' ? 'checked' : '' }}
                               onchange="toggleTipePengguna(this.value)"
                               class="text-[#0F5143] focus:ring-[#10B981] h-4 w-4">
                        <div class="ml-3">
                            <span class="block text-xs sm:text-sm font-bold text-slate-900">Pengguna (Civitas)</span>
                            <span class="block text-[11px] text-slate-600">Mahasiswa, dosen, atau staf peminjam fasilitas</span>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipe Pengguna (Conditional) -->
            <div id="tipe-pengguna-wrapper" class="{{ old('role', 'pengguna') === 'pengguna' ? 'block' : 'hidden' }}">
                <label for="tipe_pengguna" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                    Kategori / Tipe Civitas <span class="text-red-500">*</span>
                </label>
                <div>
                    <select name="tipe_pengguna"
                            id="tipe_pengguna"
                            class="block w-full px-3.5 py-2.5 text-xs sm:text-sm kezak-input font-medium @error('tipe_pengguna') border-red-500 @enderror">
                        <option value="">-- Pilih Kategori Civitas --</option>
                        <option value="mahasiswa" {{ old('tipe_pengguna', 'mahasiswa') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="dosen" {{ old('tipe_pengguna') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="staf" {{ old('tipe_pengguna') === 'staf' ? 'selected' : '' }}>Staf / Tenaga Kependidikan</option>
                    </select>
                </div>
                @error('tipe_pengguna')
                    <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <div>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autocomplete="name"
                           placeholder="Contoh: Budi Santoso, S.Kom."
                           class="block w-full px-3.5 py-2.5 text-xs sm:text-sm kezak-input placeholder-slate-400 @error('name') border-red-500 @enderror">
                </div>
                @error('name')
                    <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat Email -->
            <div>
                <label for="email" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                    Alamat Email Kampus <span class="text-red-500">*</span>
                </label>
                <div>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           placeholder="nama@kampus.ac.id"
                           class="block w-full px-3.5 py-2.5 text-xs sm:text-sm kezak-input placeholder-slate-400 @error('email') border-red-500 @enderror">
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid 2 Kolom: Password & Konfirmasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                        Kata Sandi <span class="text-red-500">*</span>
                    </label>
                    <div>
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               minlength="8"
                               autocomplete="new-password"
                               placeholder="Minimal 8 karakter"
                               class="block w-full px-3.5 py-2.5 text-xs sm:text-sm kezak-input placeholder-slate-400 @error('password') border-red-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs sm:text-sm font-bold text-slate-800 mb-1.5">
                        Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                    </label>
                    <div>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               required
                               minlength="8"
                               autocomplete="new-password"
                               placeholder="Ulangi kata sandi"
                               class="block w-full px-3.5 py-2.5 text-xs sm:text-sm kezak-input placeholder-slate-400">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/40">
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2.5 rounded-xl bg-white/70 hover:bg-white/95 border border-white/80 text-slate-700 text-xs sm:text-sm font-semibold transition-all shadow-2xs">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 kezak-btn-primary text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all">
                    Daftarkan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleTipePengguna(role) {
        const wrapper = document.getElementById('tipe-pengguna-wrapper');
        const select = document.getElementById('tipe_pengguna');

        if (role === 'petugas') {
            wrapper.classList.add('hidden');
            select.removeAttribute('required');
        } else {
            wrapper.classList.remove('hidden');
            select.setAttribute('required', 'required');
        }
    }
</script>
@endsection
