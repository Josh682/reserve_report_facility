@extends('layouts.petugas')

@section('title', 'Antrean Reservasi Fasilitas')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-[#0F5143] to-[#146353] text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-100 mb-2 border border-emerald-400/30">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Portal Operasional Petugas — US 9 & US 10</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">Antrean Permohonan Reservasi</h1>
            <p class="mt-1 text-emerald-100/90 text-sm max-w-xl">
                Tinjau jadwal peminjaman ruangan, cegah bentrok waktu otomatis, dan lakukan pembatalan darurat jika terjadi kendala operasional mendadak.
            </p>
        </div>

        <!-- Quick Summary Counters -->
        <div class="flex items-center gap-3 shrink-0">
            <div class="px-4 py-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 text-center">
                <span class="block text-[11px] uppercase tracking-wider text-emerald-200 font-semibold">Menunggu</span>
                <span class="text-xl font-extrabold text-amber-300">{{ $counts['pending'] }}</span>
            </div>
            <div class="px-4 py-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 text-center">
                <span class="block text-[11px] uppercase tracking-wider text-emerald-200 font-semibold">Disetujui</span>
                <span class="text-xl font-extrabold text-white">{{ $counts['approved'] }}</span>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if (session('status'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 flex items-start gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1 text-sm font-medium text-emerald-900 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if (session('status_error'))
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 flex items-start gap-3 shadow-xs">
            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1 text-sm font-medium text-rose-900 dark:text-rose-200">
                {{ session('status_error') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 shadow-xs space-y-1">
            <strong class="text-xs uppercase font-bold text-rose-800 dark:text-rose-200 block">Terdapat kesalahan:</strong>
            <ul class="list-disc pl-5 text-xs text-rose-700 dark:text-rose-300">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Filter & Search Controls Card -->
    <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xs space-y-4">
        
        <!-- Status Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 dark:border-gray-700/80 pb-3">
            <a href="{{ route('petugas.reservations.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'pending' ? 'bg-[#0F5143] text-white shadow-xs' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <span>Menunggu Verifikasi</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'pending' ? 'bg-white text-[#0F5143]' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-200' }}">
                    {{ $counts['pending'] }}
                </span>
            </a>

            <a href="{{ route('petugas.reservations.index', array_merge(request()->except('page'), ['status' => 'approved'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'approved' ? 'bg-[#0F5143] text-white shadow-xs' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <span>Telah Disetujui</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'approved' ? 'bg-white text-[#0F5143]' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-200' }}">
                    {{ $counts['approved'] }}
                </span>
            </a>

            <a href="{{ route('petugas.reservations.index', array_merge(request()->except('page'), ['status' => 'rejected'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'rejected' ? 'bg-[#0F5143] text-white shadow-xs' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <span>Ditolak</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'rejected' ? 'bg-white text-[#0F5143]' : 'bg-rose-100 text-rose-800 dark:bg-rose-900/60 dark:text-rose-200' }}">
                    {{ $counts['rejected'] }}
                </span>
            </a>

            <a href="{{ route('petugas.reservations.index', array_merge(request()->except('page'), ['status' => 'cancelled'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'cancelled' ? 'bg-[#0F5143] text-white shadow-xs' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <span>Dibatalkan</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'cancelled' ? 'bg-white text-[#0F5143]' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                    {{ $counts['cancelled'] }}
                </span>
            </a>

            <a href="{{ route('petugas.reservations.index', array_merge(request()->except('page'), ['status' => 'all'])) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $statusFilter === 'all' ? 'bg-[#0F5143] text-white shadow-xs' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <span>Semua Status</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $statusFilter === 'all' ? 'bg-white text-[#0F5143]' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                    {{ $counts['total'] }}
                </span>
            </a>
        </div>

        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('petugas.reservations.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <input type="hidden" name="status" value="{{ $statusFilter }}">

            <!-- Search input -->
            <div class="sm:col-span-5 relative">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama pemohon, fasilitas, atau tujuan..."
                       class="w-full pl-9 pr-3 py-2 rounded-xl text-xs border border-gray-300 dark:border-gray-600 bg-gray-50/50 dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0F5143]/50 focus:border-[#0F5143]">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Facility select -->
            <div class="sm:col-span-3">
                <select name="facility_id"
                        class="w-full px-3 py-2 rounded-xl text-xs border border-gray-300 dark:border-gray-600 bg-gray-50/50 dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#0F5143]/50 focus:border-[#0F5143]">
                    <option value="">Semua Fasilitas</option>
                    @foreach ($facilities as $fac)
                        <option value="{{ $fac->id }}" {{ $facilityId == $fac->id ? 'selected' : '' }}>
                            {{ $fac->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date picker -->
            <div class="sm:col-span-2">
                <input type="date"
                       name="tanggal"
                       value="{{ $dateFilter }}"
                       class="w-full px-3 py-2 rounded-xl text-xs border border-gray-300 dark:border-gray-600 bg-gray-50/50 dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#0F5143]/50 focus:border-[#0F5143]">
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 py-2 px-3 rounded-xl bg-[#0F5143] hover:bg-[#146353] text-white text-xs font-bold transition-all shadow-xs text-center cursor-pointer">
                    Filter
                </button>
                @if ($search || $facilityId || $dateFilter)
                    <a href="{{ route('petugas.reservations.index', ['status' => $statusFilter]) }}"
                       class="p-2 rounded-xl border border-gray-200 dark:border-gray-600 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
                       title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Reservations Card Grid / List -->
    <div class="space-y-4">
        @forelse ($reservations as $reservation)
            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xs hover:border-[#0F5143]/40 transition-all flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                
                <!-- Main Info Section -->
                <div class="space-y-3 flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- ID Badge -->
                        <span class="px-2.5 py-0.5 rounded-lg text-[11px] font-mono font-bold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                            #RES-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}
                        </span>

                        <!-- Status Badge -->
                        @if ($reservation->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 border border-amber-300 dark:border-amber-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                Menunggu Persetujuan
                            </span>
                        @elseif ($reservation->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                Disetujui
                            </span>
                        @elseif ($reservation->status === 'rejected')
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300 border border-rose-300 dark:border-rose-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                Dibatalkan
                            </span>
                        @endif

                        <!-- Dosen Priority Indicator (Issue #27) -->
                        @if (optional($reservation->user)->tipe_pengguna === 'dosen')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 border border-indigo-300 dark:border-indigo-700">
                                <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Prioritas: Dosen
                            </span>
                        @endif
                    </div>

                    <!-- Facility & Schedule Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                        <div>
                            <span class="text-gray-400 text-[11px] block uppercase font-medium">Fasilitas / Ruangan:</span>
                            <span class="font-bold text-gray-900 dark:text-white text-sm">
                                {{ $reservation->facility->nama ?? '-' }}
                            </span>
                            <span class="text-gray-500 dark:text-gray-400 block text-[11px]">
                                Lokasi: {{ $reservation->facility->lokasi ?? '-' }}
                            </span>
                        </div>

                        <div>
                            <span class="text-gray-400 text-[11px] block uppercase font-medium">Jadwal Penggunaan:</span>
                            <span class="font-bold text-[#0F5143] dark:text-emerald-400 font-mono">
                                {{ \Illuminate\Support\Carbon::parse($reservation->tanggal)->translatedFormat('l, d F Y') }}
                            </span>
                            <span class="font-semibold text-gray-700 dark:text-gray-300 block font-mono">
                                {{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }} WIB
                            </span>
                        </div>

                        <div>
                            <span class="text-gray-400 text-[11px] block uppercase font-medium">Pemohon:</span>
                            <span class="font-semibold text-gray-900 dark:text-white block">
                                {{ $reservation->user->name ?? '-' }}
                            </span>
                            <span class="text-gray-500 dark:text-gray-400 text-[11px] block">
                                {{ $reservation->user->email ?? '-' }} ({{ ucfirst($reservation->user->tipe_pengguna ?? 'Umum') }})
                            </span>
                        </div>
                    </div>

                    <!-- Tujuan Penggunaan -->
                    <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-100 dark:border-gray-800 text-xs">
                        <span class="font-bold text-gray-700 dark:text-gray-300">Tujuan Peminjaman:</span>
                        <p class="text-gray-600 dark:text-gray-400 mt-0.5">
                            {{ $reservation->tujuan_penggunaan }}
                        </p>
                    </div>

                    <!-- Catatan Pembatalan / Penolakan jika ada -->
                    @if ($reservation->cancelled_reason)
                        <div class="p-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-900/40 text-xs text-rose-700 dark:text-rose-300">
                            <strong>Alasan / Catatan Petugas:</strong> {{ $reservation->cancelled_reason }}
                            @if ($reservation->processor)
                                <span class="text-[11px] text-gray-500 dark:text-gray-400 block mt-0.5">
                                    Diproses oleh: {{ $reservation->processor->name }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Action Controls Section -->
                <div class="flex sm:flex-col items-center sm:items-end justify-end gap-2.5 shrink-0 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100 dark:border-gray-700">
                    
                    @if ($reservation->status === 'pending')
                        <!-- Button Setujui (Approve) -->
                        <form method="POST" action="{{ route('petugas.reservations.approve', $reservation->id) }}"
                              onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI reservasi #{{ $reservation->id }}? Jadwal lain yang bentrok akan otomatis ditolak oleh sistem.');">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#0F5143] hover:bg-[#146353] shadow-xs transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Setujui (Approve)</span>
                            </button>
                        </form>

                        <!-- Button Tolak (Reject) with Reason Modal -->
                        <button type="button"
                                onclick="openRejectModal({{ $reservation->id }}, '{{ addslashes($reservation->facility->nama ?? 'Fasilitas') }}', '{{ addslashes($reservation->user->name ?? 'Pemohon') }}')"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-200 dark:border-rose-800 transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Tolak Permohonan</span>
                        </button>
                    
                    @elseif ($reservation->status === 'approved')
                        <!-- Button Pembatalan Darurat (US 10) -->
                        <button type="button"
                                onclick="openEmergencyCancelModal({{ $reservation->id }}, '{{ addslashes($reservation->facility->nama ?? 'Fasilitas') }}', '{{ addslashes($reservation->user->name ?? 'Pemohon') }}')"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:text-rose-300 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-800 transition-all cursor-pointer"
                                title="Pembatalan darurat jika terjadi kendala operasional mendadak (US 10)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Batal Darurat (US 10)</span>
                        </button>
                    @endif

                </div>
            </div>
        @empty
            <div class="p-12 text-center rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Tidak ada reservasi ditemukan</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Coba sesuaikan filter status, tanggal, atau kata kunci pencarian Anda.
                </p>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="pt-2">
            {{ $reservations->links() }}
        </div>
    </div>

</div>

<!-- Modal Tolak Permohonan Reservasi -->
<div id="rejectModal" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6 shadow-xl space-y-4">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Tolak Permohonan Reservasi</h3>
                <p id="rejectModalSub" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"></p>
            </div>
            <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="rejectForm" method="POST" action="">
            @csrf
            @method('PATCH')
            <div>
                <label for="alasan_penolakan" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Alasan Penolakan (Opsional / Edukatif untuk Pemohon):
                </label>
                <textarea id="alasan_penolakan"
                          name="alasan"
                          rows="3"
                          placeholder="Misal: Ruangan sedang disiapkan untuk acara Dies Natalis..."
                          class="w-full px-3 py-2 rounded-xl text-xs border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-rose-500/50"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4">
                <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:text-gray-800 dark:text-gray-300">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-xs cursor-pointer">
                    Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pembatalan Darurat (US 10) -->
<div id="emergencyCancelModal" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6 shadow-xl space-y-4">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-2 text-rose-600 dark:text-rose-400">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Pembatalan Darurat (US 10)</h3>
                    <p id="emergencyModalSub" class="text-xs text-gray-500 dark:text-gray-400"></p>
                </div>
            </div>
            <button type="button" onclick="closeEmergencyCancelModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <p class="text-xs text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 p-2.5 rounded-xl border border-amber-200 dark:border-amber-800">
            <strong>Peringatan:</strong> Reservasi ini sudah berstatus disetujui. Tindakan pembatalan darurat hanya boleh dilakukan jika terjadi kendala mendadak dan wajib menyertakan alasan minimal 10 karakter untuk transparansi audit.
        </p>

        <form id="emergencyForm" method="POST" action="">
            @csrf
            @method('PATCH')
            <div>
                <label for="cancelled_reason" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Alasan Pembatalan Darurat <span class="text-rose-500">* (Min. 10 karakter)</span>:
                </label>
                <textarea id="cancelled_reason"
                          name="cancelled_reason"
                          rows="3"
                          required
                          minlength="10"
                          placeholder="Jelaskan alasan mendadak secara detail, misal: Terjadi kebocoran pipa air darurat di lab..."
                          class="w-full px-3 py-2 rounded-xl text-xs border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-rose-500/50"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4">
                <button type="button" onclick="closeEmergencyCancelModal()"
                        class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:text-gray-800 dark:text-gray-300">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-xs cursor-pointer">
                    Batalkan Jadwal Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id, facility, user) {
        document.getElementById('rejectForm').action = '/petugas/reservations/' + id + '/reject';
        document.getElementById('rejectModalSub').innerText = facility + ' — Pemohon: ' + user;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    function openEmergencyCancelModal(id, facility, user) {
        document.getElementById('emergencyForm').action = '/petugas/reservations/' + id + '/emergency-cancel';
        document.getElementById('emergencyModalSub').innerText = facility + ' — Pemohon: ' + user;
        document.getElementById('emergencyModal').classList.remove('hidden');
    }

    function closeEmergencyCancelModal() {
        document.getElementById('emergencyCancelModal').classList.add('hidden');
    }
</script>
@endsection
