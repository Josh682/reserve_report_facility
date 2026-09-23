@extends('layouts.admin')

@section('title', 'Manajemen Pengguna & Verifikasi')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Manajemen Pengguna</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Verifikasi pendaftaran akun mandiri baru dan kelola seluruh akun pengguna serta petugas kampus.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Akun Baru
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <!-- Tab 1: Pending Verification -->
            <a href="{{ route('admin.users.index', ['tab' => 'pending']) }}"
               class="inline-flex items-center gap-2 py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $currentTab === 'pending' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                <span>Menunggu Verifikasi</span>
                @if ($pendingCount > 0)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300">
                        {{ $pendingCount }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-normal bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                        0
                    </span>
                @endif
            </a>

            <!-- Tab 2: All Registered Users -->
            <a href="{{ route('admin.users.index', ['tab' => 'all']) }}"
               class="inline-flex items-center gap-2 py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $currentTab === 'all' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                <span>Semua Akun Terdaftar</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-normal bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                    {{ $allCount }}
                </span>
            </a>
        </nav>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">
            <input type="hidden" name="tab" value="{{ $currentTab }}">

            <!-- Search Field -->
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari berdasarkan nama atau alamat email..."
                       class="block w-full pl-10 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
            </div>

            @if ($currentTab === 'all')
                <!-- Filter Role -->
                <div class="w-full md:w-48">
                    <select name="role"
                            class="block w-full py-2 px-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Role</option>
                        <option value="pengguna" {{ $roleFilter === 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                        <option value="petugas" {{ $roleFilter === 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="w-full md:w-48">
                    <select name="status"
                            class="block w-full py-2 px-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="verified" {{ $statusFilter === 'verified' ? 'selected' : '' }}>Verified (Aktif)</option>
                        <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                    </select>
                </div>
            @endif

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
                    Filter
                </button>
                @if ($search || $roleFilter || $statusFilter)
                    <a href="{{ route('admin.users.index', ['tab' => $currentTab]) }}"
                       class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Content Table Based on Active Tab -->
    @if ($currentTab === 'pending')
        <!-- Tab 1: Pendaftar Pending (Verifikasi US 15) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xs">
            @if ($pendingUsers->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto w-12 h-12 rounded-full bg-green-100 dark:bg-green-950/60 text-green-600 dark:text-green-400 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Tidak Ada Antrean Verifikasi</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Semua pendaftar mandiri telah diproses atau belum ada pendaftar baru.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-750 text-gray-600 dark:text-gray-300 font-semibold uppercase text-xs tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Nama Pendaftar</th>
                                <th scope="col" class="px-6 py-3.5">Alamat Email</th>
                                <th scope="col" class="px-6 py-3.5">Tipe Pengguna</th>
                                <th scope="col" class="px-6 py-3.5">Tanggal Daftar</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                            @foreach ($pendingUsers as $user)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                            {{ ucfirst($user->tipe_pengguna ?? 'Mahasiswa') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-xs">
                                        {{ $user->created_at->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                        <!-- Form Setujui -->
                                        <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui akun {{ $user->name }}?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Setujui
                                            </button>
                                        </form>

                                        <!-- Form Tolak -->
                                        <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menolak akun {{ $user->name }}?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Tolak
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($pendingUsers->hasPages())
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $pendingUsers->appends(['tab' => 'pending', 'search' => $search])->links() }}
                    </div>
                @endif
            @endif
        </div>
    @else
        <!-- Tab 2: Semua Akun Terdaftar (US 13, 14, 15) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xs">
            @if ($allUsers->isEmpty())
                <div class="p-12 text-center">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ditemukan data pengguna yang cocok dengan kriteria filter.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-750 text-gray-600 dark:text-gray-300 font-semibold uppercase text-xs tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Nama</th>
                                <th scope="col" class="px-6 py-3.5">Email</th>
                                <th scope="col" class="px-6 py-3.5">Role</th>
                                <th scope="col" class="px-6 py-3.5">Tipe Pengguna</th>
                                <th scope="col" class="px-6 py-3.5">Status Akun</th>
                                <th scope="col" class="px-6 py-3.5">Tanggal Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                            @foreach ($allUsers as $user)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->role === 'admin')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300">
                                                Admin
                                            </span>
                                        @elseif ($user->role === 'petugas')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">
                                                Petugas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                                Pengguna
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                        {{ $user->tipe_pengguna ? ucfirst($user->tipe_pengguna) : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->status_akun === 'verified')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                Verified
                                            </span>
                                        @elseif ($user->status_akun === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-xs">
                                        {{ $user->created_at->translatedFormat('d M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($allUsers->hasPages())
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $allUsers->appends(['tab' => 'all', 'search' => $search, 'role' => $roleFilter, 'status' => $statusFilter])->links() }}
                    </div>
                @endif
            @endif
        </div>
    @endif
</div>
@endsection
