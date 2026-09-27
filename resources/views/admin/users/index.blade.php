@extends('layouts.admin')

@section('title', 'Pengelolaan Akun')
@section('header_title', 'Pengelolaan Akun')

@section('content')
@php
    $petugasCount = $petugasCount ?? \App\Models\User::where('role', 'petugas')->count();
    $penggunaCount = $penggunaCount ?? \App\Models\User::where('role', 'pengguna')->count();
@endphp

<div class="space-y-6">
    <!-- ==========================================
         1. TOP KPI SUMMARY CARDS (FROSTED GLASS)
         ========================================== -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Jumlah Seluruh Akun -->
        <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-4 sm:p-5 flex items-center justify-between transition-all hover:bg-white/75">
            <div>
                <p class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Total Akun</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-1">{{ $allCount }}</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-teal-500/15 border border-teal-500/20 text-[#0F5143] flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <!-- 2. Jumlah Akun yang Statusnya Masih Pending -->
        <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-4 sm:p-5 flex items-center justify-between transition-all hover:bg-white/75">
            <div>
                <p class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Menunggu Verifikasi</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-amber-700 mt-1">{{ $pendingCount }}</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-amber-500/15 border border-amber-500/20 text-amber-700 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- 3. Jumlah Akun Petugas -->
        <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-4 sm:p-5 flex items-center justify-between transition-all hover:bg-white/75">
            <div>
                <p class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Akun Petugas</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-blue-800 mt-1">{{ $petugasCount }}</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-blue-500/15 border border-blue-500/20 text-blue-700 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>

        <!-- 4. Jumlah Akun Pengguna -->
        <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-4 sm:p-5 flex items-center justify-between transition-all hover:bg-white/75">
            <div>
                <p class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Akun Pengguna</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-emerald-800 mt-1">{{ $penggunaCount }}</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-emerald-500/15 border border-emerald-500/20 text-emerald-700 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-[#E2E8F0] gap-4">
        <nav class="-mb-px flex space-x-6 sm:space-x-8" aria-label="Tabs">
            <!-- Tab 1: Pending Verification -->
            <a href="{{ route('admin.users.index', ['tab' => 'pending']) }}"
               class="inline-flex items-center gap-2.5 py-3.5 px-1 border-b-2 text-xs sm:text-sm font-bold transition-colors {{ $currentTab === 'pending' ? 'border-[#0F5143] text-[#0F5143]' : 'border-transparent text-[#64748B] hover:text-[#0F172A] hover:border-gray-300' }}">
                <span>Menunggu Verifikasi</span>
                @if ($pendingCount > 0)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-[#EF4444] text-white">
                        {{ $pendingCount }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-[#64748B]">
                        0
                    </span>
                @endif
            </a>

            <!-- Tab 2: All Registered Users -->
            <a href="{{ route('admin.users.index', ['tab' => 'all']) }}"
               class="inline-flex items-center gap-2.5 py-3.5 px-1 border-b-2 text-xs sm:text-sm font-bold transition-colors {{ $currentTab === 'all' ? 'border-[#0F5143] text-[#0F5143]' : 'border-transparent text-[#64748B] hover:text-[#0F172A] hover:border-gray-300' }}">
                <span>Semua Akun Terdaftar</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-[#64748B]">
                    {{ $allCount }}
                </span>
            </a>
        </nav>

        <div class="pb-2.5 sm:pb-2">
            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 kezak-btn-primary text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Akun Baru</span>
            </a>
        </div>
    </div>

    <!-- Search & Filter Bar (Inputs with Soft Light Green focus) -->
    <div class="facility-card p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-3 items-center">
            <input type="hidden" name="tab" value="{{ $currentTab }}">

            <!-- Search Field -->
            <div class="flex-1 w-full relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#64748B]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari berdasarkan nama civitas atau alamat email..."
                       class="block w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm facility-input placeholder-gray-400">
            </div>

            @if ($currentTab === 'all')
                <!-- Filter Role -->
                <div class="w-full md:w-48">
                    <select name="role"
                            class="block w-full py-2.5 px-3 text-xs sm:text-sm facility-input">
                        <option value="">Semua Role</option>
                        <option value="pengguna" {{ $roleFilter === 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                        <option value="petugas" {{ $roleFilter === 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="w-full md:w-48">
                    <select name="status"
                            class="block w-full py-2.5 px-3 text-xs sm:text-sm facility-input">
                        <option value="">Semua Status</option>
                        <option value="verified" {{ $statusFilter === 'verified' ? 'selected' : '' }}>Verified (Aktif)</option>
                        <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                    </select>
                </div>
            @endif

            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit"
                        class="flex-1 md:flex-initial px-5 py-2.5 facility-btn-primary text-xs sm:text-sm font-bold shadow-xs">
                    Cari & Filter
                </button>
                @if ($search || $roleFilter || $statusFilter)
                    <a href="{{ route('admin.users.index', ['tab' => $currentTab]) }}"
                       class="px-3.5 py-2.5 facility-btn-secondary text-xs sm:text-sm font-semibold text-center"
                       title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TAB 1: PENDING VERIFICATION TABLE (User Story 15) -->
    @if ($currentTab === 'pending')
        <div class="facility-card overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F8FAFC] flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#EAB308]"></span>
                    <h3 class="text-sm font-bold text-[#0F172A]">Antrean Pendaftaran Akun Mandiri yang Menunggu Persetujuan</h3>
                </div>
                <span class="text-xs font-semibold text-[#64748B]">Total: {{ $pendingUsers->total() }} Akun</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#E2E8F0] bg-white text-[11px] font-bold text-[#64748B] uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-14">No</th>
                            <th class="py-3.5 px-4">Nama Lengkap</th>
                            <th class="py-3.5 px-4">Alamat Email</th>
                            <th class="py-3.5 px-4">Tipe Pengguna</th>
                            <th class="py-3.5 px-4">Tanggal Registrasi</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-right">Tindakan / Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0] text-xs sm:text-sm">
                        @forelse ($pendingUsers as $user)
                            <tr class="hover:bg-[#F2F9F7]/40 transition-colors">
                                <!-- No -->
                                <td class="py-4 px-4 font-semibold text-[#64748B]">
                                    {{ $pendingUsers->firstItem() + $loop->index }}
                                </td>

                                <!-- Nama Lengkap -->
                                <td class="py-4 px-4 font-bold text-[#0F172A]">
                                    {{ $user->name }}
                                </td>

                                <!-- Email -->
                                <td class="py-4 px-4 text-[#475569]">
                                    {{ $user->email }}
                                </td>

                                <!-- Tipe Pengguna -->
                                <td class="py-4 px-4">
                                    @if ($user->tipe_pengguna === 'mahasiswa')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            Mahasiswa
                                        </span>
                                    @elseif ($user->tipe_pengguna === 'dosen')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Dosen
                                        </span>
                                    @elseif ($user->tipe_pengguna === 'staf')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                            Staf
                                        </span>
                                    @else
                                        <span class="text-[#94A3B8]">-</span>
                                    @endif
                                </td>

                                <!-- Tanggal Registrasi -->
                                <td class="py-4 px-4 text-xs text-[#64748B]">
                                    {{ $user->created_at->translatedFormat('d M Y, H:i') }}
                                </td>

                                <!-- Status Badge (Conforming to DESIGN.md Section 2.3) -->
                                <td class="py-4 px-4 text-center">
                                    <span class="badge-status badge-status-sky">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#0369A1]"></span>
                                        Pending
                                    </span>
                                </td>

                                <!-- Action Buttons (Approve & Reject) -->
                                <td class="py-4 px-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <!-- Approve Button (Hijau) -->
                                        <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl font-bold text-xs bg-[#DCFCE7] text-[#15803D] border border-[#86EFAC] hover:bg-[#15803D] hover:text-white transition-all shadow-xs"
                                                    title="Setujui Akun Pengguna">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>Approve</span>
                                            </button>
                                        </form>

                                        <!-- Reject Button (Merah) -->
                                        <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menolak permohonan akun {{ addslashes($user->name) }}?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl font-bold text-xs bg-[#FEE2E2] text-[#B91C1C] border border-[#FCA5A5] hover:bg-[#B91C1C] hover:text-white transition-all shadow-xs"
                                                    title="Tolak Akun Pengguna">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span>Reject</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-[#64748B]">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="w-12 h-12 mx-auto rounded-full bg-[#DCFCE7] flex items-center justify-center text-[#15803D]">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-[#0F172A]">Semua Akun Telah Diverifikasi</p>
                                        <p class="text-xs text-[#64748B]">Tidak ada permohonan pendaftaran akun mandiri baru yang menunggu persetujuan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pendingUsers->hasPages())
                <div class="p-4 border-t border-[#E2E8F0] bg-white">
                    {{ $pendingUsers->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- TAB 2: ALL REGISTERED USERS (User Story 16) -->
    @if ($currentTab === 'all')
        <div class="facility-card overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F8FAFC] flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#0F5143]"></span>
                    <h3 class="text-sm font-bold text-[#0F172A]">Daftar Seluruh Akun Terdaftar dalam Sistem</h3>
                </div>
                <span class="text-xs font-semibold text-[#64748B]">Total: {{ $allUsers->total() }} Akun</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#E2E8F0] bg-white text-[11px] font-bold text-[#64748B] uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-14">No</th>
                            <th class="py-3.5 px-4">Nama Lengkap</th>
                            <th class="py-3.5 px-4">Alamat Email</th>
                            <th class="py-3.5 px-4">Role Akun</th>
                            <th class="py-3.5 px-4">Tipe Pengguna</th>
                            <th class="py-3.5 px-4">Status Akun</th>
                            <th class="py-3.5 px-4 text-right">Terdaftar Sejak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0] text-xs sm:text-sm">
                        @forelse ($allUsers as $user)
                            <tr class="hover:bg-[#F2F9F7]/40 transition-colors">
                                <!-- No -->
                                <td class="py-4 px-4 font-semibold text-[#64748B]">
                                    {{ $allUsers->firstItem() + $loop->index }}
                                </td>

                                <!-- Nama Lengkap -->
                                <td class="py-4 px-4 font-bold text-[#0F172A]">
                                    {{ $user->name }}
                                </td>

                                <!-- Email -->
                                <td class="py-4 px-4 text-[#475569]">
                                    {{ $user->email }}
                                </td>

                                <!-- Role -->
                                <td class="py-4 px-4">
                                    @if ($user->role === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-[#E6F4F1] text-[#0F5143] border border-[#107B67]/30">
                                            Admin
                                        </span>
                                    @elseif ($user->role === 'petugas')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            Petugas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-gray-100 text-[#475569]">
                                            Pengguna
                                        </span>
                                    @endif
                                </td>

                                <!-- Tipe Pengguna -->
                                <td class="py-4 px-4 text-xs font-medium text-[#475569]">
                                    {{ $user->tipe_pengguna ? ucfirst($user->tipe_pengguna) : '-' }}
                                </td>

                                <!-- Status Akun (Conforming to DESIGN.md Section 2.3) -->
                                <td class="py-4 px-4">
                                    @if ($user->status_akun === 'verified')
                                        <span class="badge-status badge-status-green">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#15803D]"></span>
                                            Verified
                                        </span>
                                    @elseif ($user->status_akun === 'pending')
                                        <span class="badge-status badge-status-sky">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#0369A1]"></span>
                                            Pending
                                        </span>
                                    @elseif ($user->status_akun === 'rejected')
                                        <span class="badge-status badge-status-red">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#B91C1C]"></span>
                                            Rejected
                                        </span>
                                    @else
                                        <span class="badge-status badge-status-slate">{{ $user->status_akun }}</span>
                                    @endif
                                </td>

                                <!-- Tanggal Terdaftar -->
                                <td class="py-4 px-4 text-right text-xs text-[#64748B]">
                                    {{ $user->created_at->translatedFormat('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-[#64748B]">
                                    <p class="text-sm font-semibold text-[#0F172A]">Tidak ada data akun yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($allUsers->hasPages())
                <div class="p-4 border-t border-[#E2E8F0] bg-white">
                    {{ $allUsers->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
