@extends('layouts.admin')

@section('title', 'Tambah Akun Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tambah Akun Baru</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Pendaftaran akun petugas operasional atau pengguna kampus langsung oleh Administrator.
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Info Notice -->
    <div class="rounded-xl bg-blue-50 dark:bg-blue-950/40 p-4 border border-blue-200 dark:border-blue-800">
        <div class="flex items-start gap-3">
            <div class="shrink-0 text-blue-600 dark:text-blue-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-sm text-blue-800 dark:text-blue-200">
                <p class="font-medium">Otorisasi Langsung Administrator</p>
                <p class="mt-0.5 text-xs text-blue-700 dark:text-blue-300">
                    Akun yang didaftarkan langsung oleh Administrator akan otomatis berstatus <span class="font-semibold underline">Verified (Aktif)</span> tanpa memerlukan persetujuan tambahan dan langsung dapat digunakan untuk masuk ke sistem.
                </p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <!-- Role Selection -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Role Akun <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Option 1: Petugas -->
                    <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors {{ old('role') === 'petugas' ? 'border-indigo-600 bg-indigo-50/30 dark:border-indigo-500 dark:bg-indigo-950/20' : 'border-gray-200 dark:border-gray-700' }}">
                        <input type="radio"
                               name="role"
                               value="petugas"
                               {{ old('role') === 'petugas' ? 'checked' : '' }}
                               onchange="toggleTipePengguna(this.value)"
                               class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                        <div class="ml-3">
                            <span class="block text-sm font-semibold text-gray-900 dark:text-white">Petugas Fasilitas</span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400">Pengelola reservasi, validasi kerusakan, dan pemeliharaan</span>
                        </div>
                    </label>

                    <!-- Option 2: Pengguna -->
                    <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors {{ old('role', 'pengguna') === 'pengguna' ? 'border-indigo-600 bg-indigo-50/30 dark:border-indigo-500 dark:bg-indigo-950/20' : 'border-gray-200 dark:border-gray-700' }}">
                        <input type="radio"
                               name="role"
                               value="pengguna"
                               {{ old('role', 'pengguna') === 'pengguna' ? 'checked' : '' }}
                               onchange="toggleTipePengguna(this.value)"
                               class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                        <div class="ml-3">
                            <span class="block text-sm font-semibold text-gray-900 dark:text-white">Pengguna (Civitas)</span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400">Mahasiswa, dosen, atau staf kampus peminjam fasilitas</span>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipe Pengguna (Conditional) -->
            <div id="tipe-pengguna-wrapper" class="{{ old('role', 'pengguna') === 'pengguna' ? 'block' : 'hidden' }}">
                <label for="tipe_pengguna" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">
                    Tipe Pengguna <span class="text-red-500">*</span>
                </label>
                <select name="tipe_pengguna"
                        id="tipe_pengguna"
                        class="block w-full px-3 py-2 text-sm border @error('tipe_pengguna') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Kategori Civitas --</option>
                    <option value="mahasiswa" {{ old('tipe_pengguna', 'mahasiswa') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="dosen" {{ old('tipe_pengguna') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="staf" {{ old('tipe_pengguna') === 'staf' ? 'selected' : '' }}>Staf / Tenaga Kependidikan</option>
                </select>
                @error('tipe_pengguna')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name') }}"
                       required
                       placeholder="Contoh: Muhammad Ihsan"
                       class="block w-full px-3 py-2 text-sm border @error('name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">
                    Alamat Email Kampus <span class="text-red-500">*</span>
                </label>
                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email') }}"
                       required
                       placeholder="Contoh: ihsan@kampus.ac.id"
                       class="block w-full px-3 py-2 text-sm border @error('email') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">
                        Kata Sandi <span class="text-red-500">*</span>
                    </label>
                    <input type="password"
                           name="password"
                           id="password"
                           required
                           placeholder="Minimal 8 karakter"
                           class="block w-full px-3 py-2 text-sm border @error('password') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">
                        Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                    </label>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           required
                           placeholder="Ulangi kata sandi"
                           class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    Simpan & Daftarkan Akun
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
            select.value = '';
        } else {
            wrapper.classList.remove('hidden');
            if (!select.value) {
                select.value = 'mahasiswa';
            }
        }
    }
</script>
@endsection
