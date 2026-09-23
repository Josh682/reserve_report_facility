@extends('layouts.admin')

@section('title', 'Master Fasilitas')

@section('content')
@php
    $tipeNames = [
        'ruang_kelas' => 'Ruang Kelas',
        'aula' => 'Aula',
        'laboratorium' => 'Laboratorium',
        'alat' => 'Alat',
        'lapangan' => 'Lapangan',
    ];
@endphp

<div class="space-y-6">
    <!-- Top Action / Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Fasilitas Kampus</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola data sarana dan prasarana kampus yang dapat dipinjam oleh civitas akademika.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.facilities.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Fasilitas
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.facilities.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Search Input -->
            <div class="sm:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama fasilitas atau lokasi..."
                       class="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" />
            </div>

            <!-- Tipe Filter -->
            <div class="sm:col-span-3">
                <select name="tipe" class="w-full py-2 px-3 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Tipe</option>
                    <option value="ruang_kelas" @selected(request('tipe') === 'ruang_kelas')>Ruang Kelas</option>
                    <option value="aula" @selected(request('tipe') === 'aula')>Aula</option>
                    <option value="laboratorium" @selected(request('tipe') === 'laboratorium')>Laboratorium</option>
                    <option value="alat" @selected(request('tipe') === 'alat')>Alat</option>
                    <option value="lapangan" @selected(request('tipe') === 'lapangan')>Lapangan</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="sm:col-span-2">
                <select name="status" class="w-full py-2 px-3 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                    <option value="dalam_perbaikan" @selected(request('status') === 'dalam_perbaikan')>Dalam Perbaikan</option>
                    <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-lg text-sm font-medium transition-colors">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'tipe', 'status']))
                    <a href="{{ route('admin.facilities.index') }}"
                       class="px-3 py-2 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg text-sm font-medium transition-colors"
                       title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/75 dark:bg-gray-700/50 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-16">No / ID</th>
                        <th class="py-3.5 px-4">Nama Fasilitas</th>
                        <th class="py-3.5 px-4">Tipe</th>
                        <th class="py-3.5 px-4">Lokasi</th>
                        <th class="py-3.5 px-4">Kapasitas</th>
                        <th class="py-3.5 px-4">Status Operasional</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    @forelse ($facilities as $facility)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition-colors">
                            <!-- No / ID -->
                            <td class="py-3.5 px-4 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                    {{ $facilities->firstItem() ? $facilities->firstItem() + $loop->index : $loop->iteration }}
                                </span>
                                <span class="text-xs text-gray-400 dark:text-gray-500 block">#{{ $facility->id }}</span>
                            </td>

                            <!-- Nama Fasilitas -->
                            <td class="py-3.5 px-4 font-semibold text-gray-900 dark:text-white">
                                <div>{{ $facility->nama }}</div>
                                @if ($facility->deskripsi)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 font-normal mt-0.5">
                                        {{ $facility->deskripsi }}
                                    </p>
                                @endif
                            </td>

                            <!-- Tipe -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    {{ $tipeNames[$facility->tipe] ?? ucfirst(str_replace('_', ' ', $facility->tipe)) }}
                                </span>
                            </td>

                            <!-- Lokasi -->
                            <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300">
                                {{ $facility->lokasi }}
                            </td>

                            <!-- Kapasitas -->
                            <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                {{ $facility->kapasitas ? $facility->kapasitas . ' Orang' : '-' }}
                            </td>

                            <!-- Status Operasional & Quick Toggle -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if ($facility->status === 'aktif')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                            Aktif
                                        </span>
                                    @elseif ($facility->status === 'dalam_perbaikan')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300">
                                            Dalam Perbaikan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Nonaktif
                                        </span>
                                    @endif

                                    <!-- Quick Status Toggle Form -->
                                    <form action="{{ route('admin.facilities.status', $facility) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" title="Ubah status cepat"
                                                class="text-xs rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 py-0.5 px-1.5 focus:ring-1 focus:ring-indigo-500 cursor-pointer">
                                            <option value="aktif" @selected($facility->status === 'aktif')>Set: Aktif</option>
                                            <option value="dalam_perbaikan" @selected($facility->status === 'dalam_perbaikan')>Set: Perbaikan</option>
                                            <option value="nonaktif" @selected($facility->status === 'nonaktif')>Set: Nonaktif</option>
                                        </select>
                                    </form>
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.facilities.edit', $facility) }}"
                                       class="inline-flex items-center p-1.5 rounded-lg text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 transition-colors"
                                       title="Edit Fasilitas">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas \'{{ $facility->nama }}\'?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center p-1.5 rounded-lg text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-950/50 transition-colors"
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="p-3 bg-gray-100 dark:bg-gray-700/60 rounded-full text-gray-400 dark:text-gray-500">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">Tidak ada data fasilitas</p>
                                    <p class="text-sm">Tidak ditemukan fasilitas yang cocok dengan kriteria filter atau pencarian Anda.</p>
                                    @if (request()->hasAny(['search', 'tipe', 'status']))
                                        <a href="{{ route('admin.facilities.index') }}" class="mt-2 text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 font-medium">
                                            Bersihkan filter pencarian
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($facilities->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                {{ $facilities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
