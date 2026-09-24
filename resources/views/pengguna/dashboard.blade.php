@extends('layouts.pengguna')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="space-y-6">

    {{-- WELCOME BANNER (SWISS HIGH-DENSITY MINIMAL) --}}
    <div class="p-6 rounded-xs bg-white dark:bg-[#0c1419] border border-slate-200 dark:border-teal-950/80 shadow-none flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded-xs text-[11px] font-mono uppercase tracking-wider bg-teal-50 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border border-teal-200/60 dark:border-teal-800/40 mb-2">
                <span class="w-1.5 h-1.5 rounded-xs bg-teal-500"></span>
                <span>Akun Terverifikasi — {{ ucfirst(auth()->user()->tipe_pengguna ?? 'Mahasiswa') }}</span>
            </div>
            <h1 class="text-xl font-black uppercase tracking-tight text-slate-900 dark:text-white">
                Selamat Datang, {{ auth()->user()->name }}
            </h1>
            <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 max-w-2xl leading-relaxed">
                Cek fasilitas kampus yang siap digunakan, ajukan peminjaman ruangan mandiri, dan pantau status verifikasi jadwal secara transparan.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('facilities') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xs text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors border border-slate-200 dark:border-slate-700">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Lihat Katalog</span>
            </a>
            <a href="{{ route('reservation') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xs text-xs font-semibold uppercase tracking-wider text-white bg-teal-700 hover:bg-teal-800 dark:bg-teal-600 dark:hover:bg-teal-500 shadow-none transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Pinjam Ruangan</span>
            </a>
        </div>
    </div>

    {{-- STATS METRIK KPI (MONOSPACE METRICS) --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Fasilitas Siap Pakai --}}
        <a href="{{ route('facilities') }}" class="p-5 rounded-xs bg-white dark:bg-[#0c1419] border border-slate-200 dark:border-teal-950/80 shadow-none flex items-center justify-between hover:border-teal-600/70 transition-colors group">
            <div>
                <span class="text-[11px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Fasilitas Siap Pakai</span>
                <span class="text-3xl font-mono font-black text-teal-700 dark:text-teal-400 mt-1 block">{{ $stats['aktif'] ?? 0 }}</span>
                <span class="text-[11px] font-mono text-slate-400 mt-0.5 block group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors uppercase">Buka katalog &rarr;</span>
            </div>
            <div class="p-3 rounded-xs bg-teal-50 dark:bg-teal-950/50 text-teal-700 dark:text-teal-400 border border-teal-200/60 dark:border-teal-800/40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </a>

        {{-- Peminjaman Disetujui --}}
        <a href="{{ route('reservation') }}" class="p-5 rounded-xs bg-white dark:bg-[#0c1419] border border-slate-200 dark:border-teal-950/80 shadow-none flex items-center justify-between hover:border-emerald-600/70 transition-colors group">
            <div>
                <span class="text-[11px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Peminjaman Disetujui</span>
                <span class="text-3xl font-mono font-black text-emerald-600 dark:text-emerald-400 mt-1 block">{{ $stats['my_approved'] ?? 0 }}</span>
                <span class="text-[11px] font-mono text-slate-400 mt-0.5 block uppercase">Jadwal reservasi aktif</span>
            </div>
            <div class="p-3 rounded-xs bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </a>

        {{-- Menunggu Persetujuan --}}
        <a href="{{ route('reservation') }}" class="p-5 rounded-xs bg-white dark:bg-[#0c1419] border border-slate-200 dark:border-teal-950/80 shadow-none flex items-center justify-between hover:border-amber-600/70 transition-colors group">
            <div>
                <span class="text-[11px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Menunggu Persetujuan</span>
                <span class="text-3xl font-mono font-black text-amber-600 dark:text-amber-400 mt-1 block">{{ $stats['my_pending'] ?? 0 }}</span>
                <span class="text-[11px] font-mono text-slate-400 mt-0.5 block uppercase">Verifikasi petugas</span>
            </div>
            <div class="p-3 rounded-xs bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </a>
    </div>

    {{-- FASILITAS REKOMENDASI --}}
    <div class="p-6 rounded-xs bg-white dark:bg-[#0c1419] border border-slate-200 dark:border-teal-950/80 shadow-none space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h3 class="text-base font-bold uppercase tracking-tight text-slate-900 dark:text-white">Fasilitas Kampus Siap Digunakan</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pilih ruangan yang siap pakai untuk kegiatan akademik maupun organisasi.</p>
            </div>
            <a href="{{ route('facilities') }}" class="text-xs font-mono font-semibold uppercase text-teal-700 dark:text-teal-400 hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse ($availableFacilities as $facility)
                <div class="p-4 rounded-xs border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                    <div>
                        <div class="flex items-center justify-between gap-1 mb-2">
                            <span class="px-2 py-0.5 rounded-xs text-[10px] font-mono font-bold uppercase tracking-wider bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">
                                {{ str_replace('_', ' ', $facility->tipe) }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-[10px] font-mono uppercase tracking-wider font-semibold text-emerald-600 dark:text-emerald-400">
                                <span class="w-1.5 h-1.5 rounded-xs bg-emerald-500"></span> Aktif
                            </span>
                        </div>
                        <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $facility->nama }}</h4>
                        <div class="mt-2 space-y-1 text-xs text-slate-500 dark:text-slate-400">
                            <p class="truncate font-mono text-[11px]">⌖ {{ $facility->lokasi }}</p>
                            @if ($facility->kapasitas)
                                <p class="font-mono text-[11px]">♟ {{ $facility->kapasitas }} org</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <a href="{{ route('facilities') }}" class="text-xs font-mono uppercase text-slate-500 hover:text-slate-700 dark:text-slate-400 font-semibold">
                            Jadwal
                        </a>
                        <a href="{{ route('reservation', ['facility_id' => $facility->id]) }}"
                           class="inline-flex items-center gap-1 px-3 py-1 rounded-xs text-xs font-semibold uppercase tracking-wider text-white bg-teal-700 hover:bg-teal-800 dark:bg-teal-600 shadow-none transition-colors">
                            <span>+ Pinjam</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-sm font-mono text-slate-500 dark:text-slate-400 uppercase">
                    Belum ada fasilitas aktif yang tersedia saat ini.
                </div>
            @endforelse
        </div>
    </div>

    {{-- TABEL PENGAJUAN TERAKHIR SAYA --}}
    <div class="p-6 rounded-xs bg-white dark:bg-[#0c1419] border border-slate-200 dark:border-teal-950/80 shadow-none space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h3 class="text-base font-bold uppercase tracking-tight text-slate-900 dark:text-white">Riwayat Pengajuan Reservasi Terakhir</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pantau status persetujuan dari petugas.</p>
            </div>
            <a href="{{ route('reservation') }}" class="text-xs font-mono font-semibold uppercase text-teal-700 dark:text-teal-400 hover:underline">
                Kelola Semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="text-[11px] font-mono uppercase bg-slate-50 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Fasilitas / Ruangan</th>
                        <th class="py-3 px-4">Waktu Pemakaian</th>
                        <th class="py-3 px-4">Tujuan Kegiatan</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($recentReservations as $res)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                            <td class="py-3 px-4 font-bold text-slate-900 dark:text-white">
                                {{ $res->facility->nama ?? 'Fasilitas #' . $res->facility_id }}
                                <span class="block text-xs font-mono font-normal text-slate-400">{{ $res->facility->lokasi ?? '' }}</span>
                            </td>
                            <td class="py-3 px-4 font-mono text-xs">
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">
                                    {{ \Illuminate\Support\Carbon::parse($res->tanggal)->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-slate-400">
                                    {{ substr($res->start_time, 0, 5) }} - {{ substr($res->end_time, 0, 5) }} WIB
                                </span>
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-600 dark:text-slate-300 max-w-xs truncate" title="{{ $res->tujuan_penggunaan }}">
                                {{ $res->tujuan_penggunaan }}
                            </td>
                            <td class="py-3 px-4">
                                @if ($res->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-xs text-[10px] font-mono uppercase tracking-wider font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-xs bg-emerald-500"></span> Disetujui
                                    </span>
                                @elseif ($res->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-xs text-[10px] font-mono uppercase tracking-wider font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200/60">
                                        <span class="w-1.5 h-1.5 rounded-xs bg-amber-500 animate-pulse"></span> Menunggu
                                    </span>
                                @elseif ($res->status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-xs text-[10px] font-mono uppercase tracking-wider font-bold bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-200/60">
                                        <span class="w-1.5 h-1.5 rounded-xs bg-rose-500"></span> Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-xs text-[10px] font-mono uppercase tracking-wider font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-xs font-mono text-slate-500 dark:text-slate-400 uppercase">
                                Belum ada riwayat reservasi yang diajukan.
                                <a href="{{ route('reservation') }}" class="text-teal-700 dark:text-teal-400 font-bold ml-1 hover:underline">
                                    Ajukan ruangan &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
