@extends('layouts.guest', [
    'title' => 'Daftar Akun — Fasilitas Kampus',
    'header' => 'Daftar Akun',
    'subheader' => 'Lengkapi data untuk mengajukan akses sistem reservasi & pelaporan fasilitas'
])

@section('content')
    <!-- Info Banner Verifikasi Admin (US 15 & ASSUMPTION.md) -->
    <div class="mb-6 rounded-lg bg-blue-50 dark:bg-blue-900/30 p-4 border border-blue-200 dark:border-blue-800">
        <div class="flex items-start">
            <div class="flex-shrink-0 text-blue-500 dark:text-blue-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-200">Verifikasi Administrator</h3>
                <p class="mt-1 text-xs text-blue-700 dark:text-blue-300">
                    Sesuai ketentuan, pendaftaran akun mandiri akan berstatus <strong>Pending</strong> dan perlu diverifikasi oleh Admin sebelum Anda dapat masuk ke dalam sistem.
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ url('/register') }}" class="space-y-5" novalidate>
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <div class="mt-1">
                <input id="name" name="name" type="text" autocomplete="name" required
                    value="{{ old('name') }}"
                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-gray-900 dark:text-white placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:bg-gray-700 sm:text-sm @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    placeholder="Contoh: Menza Isaiah">
            </div>
            @error('name')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Alamat Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Alamat Email <span class="text-red-500">*</span>
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autocomplete="email" required
                    value="{{ old('email') }}"
                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-gray-900 dark:text-white placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:bg-gray-700 sm:text-sm @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    placeholder="nama@kampus.test">
            </div>
            @error('email')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jenis Pengguna -->
        <div>
            <label for="tipe_pengguna" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Jenis Pengguna <span class="text-red-500">*</span>
            </label>
            <div class="mt-1">
                <select id="tipe_pengguna" name="tipe_pengguna" required
                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-gray-900 dark:text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:bg-gray-700 sm:text-sm @error('tipe_pengguna') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                    <option value="" disabled {{ old('tipe_pengguna') ? '' : 'selected' }}>-- Pilih Kategori Anda --</option>
                    <option value="mahasiswa" {{ old('tipe_pengguna') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="dosen" {{ old('tipe_pengguna') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="staf" {{ old('tipe_pengguna') == 'staf' ? 'selected' : '' }}>Staf / Tenaga Kependidikan</option>
                </select>
            </div>
            @error('tipe_pengguna')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kata Sandi -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Kata Sandi <span class="text-red-500">*</span>
            </label>
            <div class="mt-1">
                <input id="password" name="password" type="password" required minlength="8"
                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-gray-900 dark:text-white placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:bg-gray-700 sm:text-sm @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    placeholder="Minimal 8 karakter">
            </div>
            @error('password')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Konfirmasi Kata Sandi -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Ulangi Kata Sandi <span class="text-red-500">*</span>
            </label>
            <div class="mt-1">
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8"
                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-gray-900 dark:text-white placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:bg-gray-700 sm:text-sm"
                    placeholder="Ulangi kata sandi di atas">
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="pt-2">
            <button type="submit"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Daftar Akun Sekarang
            </button>
        </div>

        <!-- Tautan Masuk -->
        <div class="text-center pt-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">
                Sudah memiliki akun?
            </span>
            <a href="{{ route('login') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 ml-1">
                Masuk di sini
            </a>
        </div>
    </form>
@endsection
