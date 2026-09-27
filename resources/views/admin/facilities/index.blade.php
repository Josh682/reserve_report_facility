@extends('layouts.admin')

@section('title', 'Pengelolaan Fasilitas')
@section('header_title', 'Pengelolaan Fasilitas')

@section('content')
@php
    $tipeNames = [
        'ruang_kelas' => 'Ruang Kelas',
        'aula' => 'Aula',
        'laboratorium' => 'Laboratorium',
        'alat' => 'Alat',
        'lapangan' => 'Lapangan',
    ];

    $totalCount = \App\Models\Facility::count();
    $aktifCount = \App\Models\Facility::where('status', 'aktif')->count();
    $repairCount = \App\Models\Facility::where('status', 'dalam_perbaikan')->count();
    $inactiveCount = \App\Models\Facility::where('status', 'nonaktif')->count();
@endphp

<div class="space-y-6">

    <!-- ==========================================
         1. TOP KPI SUMMARY CARDS (FROSTED GLASS)
         ========================================== -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Fasilitas -->
        <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-4 sm:p-5 flex items-center justify-between transition-all hover:bg-white/75">
            <div>
                <p class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Total Fasilitas</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-1">{{ $totalCount }}</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-teal-500/15 border border-teal-500/20 text-[#0F5143] flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>

        <!-- 2. Aktif (Tersedia) -->
        <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-4 sm:p-5 flex items-center justify-between transition-all hover:bg-white/75">
            <div>
                <p class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Aktif (Tersedia)</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-emerald-800 mt-1">{{ $aktifCount }}</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-emerald-500/15 border border-emerald-500/20 text-emerald-700 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- 3. Dalam Perbaikan -->
        <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-4 sm:p-5 flex items-center justify-between transition-all hover:bg-white/75">
            <div>
                <p class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Dalam Perbaikan</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-rose-700 mt-1">{{ $repairCount }}</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-rose-500/15 border border-rose-500/20 text-rose-700 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>

        <!-- 4. Nonaktif -->
        <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-4 sm:p-5 flex items-center justify-between transition-all hover:bg-white/75">
            <div>
                <p class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Nonaktif</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-slate-700 mt-1">{{ $inactiveCount }}</h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-slate-500/15 border border-slate-500/20 text-slate-600 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
        </div>
    </div>

    <!-- ==========================================
         2. SUBHEADER & ACTION CTA
         ========================================== -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-800">Daftar Fasilitas Kampus</h2>
            <p class="mt-0.5 text-xs sm:text-sm text-slate-600">
                Pilih fasilitas untuk mengedit rincian, mengatur kapasitas, atau mengubah status ketersediaan.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.facilities.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 kezak-btn-primary text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Fasilitas</span>
            </a>
        </div>
    </div>

    <!-- ==========================================
         3. FILTER & SEARCH BAR (FROSTED GLASS)
         ========================================== -->
    <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-4 sm:p-5">
        <form method="GET" action="{{ route('admin.facilities.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Search Input with Frosted Glass & Soft Light Green Focus -->
            <div class="sm:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama fasilitas atau lokasi..."
                       class="w-full pl-10 pr-3 py-2.5 text-xs sm:text-sm kezak-input placeholder-slate-400" />
            </div>

            <!-- Tipe Filter Dropdown -->
            <div class="sm:col-span-3">
                <select name="tipe" class="w-full py-2.5 px-3 text-xs sm:text-sm kezak-input font-medium">
                    <option value="">Semua Tipe Fasilitas</option>
                    <option value="ruang_kelas" @selected(request('tipe') === 'ruang_kelas')>Ruang Kelas</option>
                    <option value="aula" @selected(request('tipe') === 'aula')>Aula</option>
                    <option value="laboratorium" @selected(request('tipe') === 'laboratorium')>Laboratorium</option>
                    <option value="alat" @selected(request('tipe') === 'alat')>Alat</option>
                    <option value="lapangan" @selected(request('tipe') === 'lapangan')>Lapangan</option>
                </select>
            </div>

            <!-- Status Filter Dropdown -->
            <div class="sm:col-span-2">
                <select name="status" class="w-full py-2.5 px-3 text-xs sm:text-sm kezak-input font-medium">
                    <option value="">Semua Status</option>
                    <option value="aktif" @selected(request('status') === 'aktif')>Aktif (Tersedia)</option>
                    <option value="dalam_perbaikan" @selected(request('status') === 'dalam_perbaikan')>Dalam Perbaikan</option>
                    <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 inline-flex justify-center items-center px-4 py-2.5 kezak-btn-primary text-white text-xs sm:text-sm font-bold shadow-xs">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'tipe', 'status']))
                    <a href="{{ route('admin.facilities.index') }}"
                       class="px-3 py-2.5 rounded-xl bg-white/70 hover:bg-white/95 border border-white/80 text-slate-700 text-xs sm:text-sm font-semibold text-center transition-all shadow-2xs"
                       title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- ==========================================
         4. DATA TABLE CONTAINER (FROSTED GLASS)
         ========================================== -->
    <div class="bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/50 bg-white/40 text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                        <th class="py-4 px-4 w-12 text-center">No</th>
                        <th class="py-4 px-4">Nama Fasilitas & Kelengkapan</th>
                        <th class="py-4 px-4">Tipe</th>
                        <th class="py-4 px-4">Lokasi Gedung / Lantai</th>
                        <th class="py-4 px-4">Kapasitas</th>
                        <th class="py-4 px-4">Status Operasional</th>
                        <th class="py-4 px-4 text-center">Ubah Status</th>
                        <th class="py-4 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/40 text-xs sm:text-sm">
                    @forelse ($facilities as $facility)
                        <tr class="hover:bg-white/50 transition-colors">
                            <!-- No -->
                            <td class="py-4 px-4 font-semibold text-slate-500 text-center">
                                {{ $facilities->firstItem() + $loop->index }}
                            </td>

                            <!-- Nama Fasilitas & Deskripsi -->
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900 text-sm">
                                    {{ $facility->nama }}
                                </div>
                                @if ($facility->deskripsi)
                                    <p class="mt-0.5 text-xs text-slate-500 line-clamp-1" title="{{ $facility->deskripsi }}">
                                        {{ $facility->deskripsi }}
                                    </p>
                                @endif
                            </td>

                            <!-- Tipe (dengan Semantic Facility Icon) -->
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-xl text-xs font-bold bg-white/70 backdrop-blur-md border border-white/80 shadow-2xs text-slate-700">
                                    <x-facility-icon :tipe="$facility->tipe" class="w-4 h-4 text-[#0F5143] shrink-0" />
                                    <span>{{ $tipeNames[$facility->tipe] ?? ucfirst(str_replace('_', ' ', $facility->tipe)) }}</span>
                                </span>
                            </td>

                            <!-- Lokasi -->
                            <td class="py-4 px-4 text-slate-700">
                                <div class="flex items-center gap-1.5 font-medium">
                                    <svg class="w-3.5 h-3.5 text-[#0F5143] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $facility->lokasi }}</span>
                                </div>
                            </td>

                            <!-- Kapasitas -->
                            <td class="py-4 px-4 text-slate-700 font-medium">
                                @if ($facility->kapasitas)
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-slate-900">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $facility->kapasitas }} orang
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Status Operasional (Frosted Badges with Pulsing Dots) -->
                            <td class="py-4 px-4">
                                @if ($facility->status === 'aktif')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @elseif ($facility->status === 'dalam_perbaikan')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-300 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                        Dalam Perbaikan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-300 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <!-- Quick Toggle Status -->
                            <td class="py-4 px-4 text-center">
                                <form method="POST" action="{{ route('admin.facilities.status', $facility) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                            class="py-1 px-2.5 text-xs kezak-input cursor-pointer font-bold bg-white/70">
                                        <option value="aktif" @selected($facility->status === 'aktif')>Set: Aktif</option>
                                        <option value="dalam_perbaikan" @selected($facility->status === 'dalam_perbaikan')>Set: Perbaikan</option>
                                        <option value="nonaktif" @selected($facility->status === 'nonaktif')>Set: Nonaktif</option>
                                    </select>
                                </form>
                            </td>

                            <!-- Aksi (Edit & Delete) -->
                            <td class="py-4 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.facilities.edit', $facility) }}"
                                       class="p-2 rounded-xl bg-white/70 hover:bg-white/95 border border-white/80 text-[#0F5143] hover:text-[#146353] transition-all shadow-2xs"
                                       title="Edit Fasilitas">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas {{ addslashes($facility->nama) }}?');"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 rounded-xl bg-white/70 hover:bg-rose-50 border border-white/80 text-rose-600 hover:text-rose-700 transition-all shadow-2xs cursor-pointer"
                                                title="Hapus Fasilitas">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-500">
                                <div class="max-w-xs mx-auto space-y-3">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-white/70 backdrop-blur-md border border-white/80 flex items-center justify-center text-slate-400 shadow-2xs">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-800">Tidak ada data fasilitas</p>
                                    <p class="text-xs text-slate-500">Data tidak ditemukan dengan filter yang dipilih atau belum ada fasilitas terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($facilities->hasPages())
            <div class="p-4 sm:p-5 border-t border-white/50 bg-white/40">
                {{ $facilities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
