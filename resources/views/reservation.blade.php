@extends('layouts.app')

@section('title', 'Peminjaman Fasilitas — FacilityHub')
@section('page-title', 'Reservasi & Peminjaman Fasilitas')

@section('content')
<div class="space-y-6">

    {{-- HEADER & TOMBOL AKSI UTAMA (FROSTED GLASSMORPHISM) --}}
    <div class="p-6 rounded-2xl bg-white/80 dark:bg-[#0c1419]/80 backdrop-blur-md border border-slate-200/80 dark:border-teal-950/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 mb-2 border border-emerald-300/40">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Portal Peminjaman Ruang & Lab</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                Peminjaman Fasilitas Saya
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-xl">
                Ajukan peminjaman ruangan kampus, pantau status persetujuan dari petugas, serta kelola pembatalan mandiri maksimal H-1.
            </p>
        </div>

        <button type="button"
                id="toggleReservationForm"
                onclick="toggleForm()"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-[#0F5143] hover:bg-[#146353] shadow-cta transition-all shrink-0 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span id="toggleButtonText">Buat Pengajuan Baru</span>
            <kbd class="ml-1 hidden sm:inline-flex text-[10px] font-mono px-1.5 py-0.5 rounded-md bg-emerald-900/60 text-emerald-200 border border-emerald-700/50">N</kbd>
        </button>
    </div>

    {{-- ALERT STATUS NOTIFIKASI --}}
    @if (session('status'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 shadow-xs flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs sm:text-sm font-semibold text-emerald-900 dark:text-emerald-200">{{ session('status') }}</span>
        </div>
    @endif

    @if (session('status_error'))
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 shadow-xs flex items-start gap-3">
            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs sm:text-sm font-semibold text-rose-900 dark:text-rose-200">{{ session('status_error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-900 dark:text-rose-200 text-xs shadow-xs space-y-1">
            <strong class="font-bold block mb-1">Terdapat kendala pada pengajuan reservasi:</strong>
            <ul class="list-disc pl-5 space-y-0.5 font-mono text-[11px]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORMULIR PENGAJUAN PINJAM RUANG (TOGGLEABLE) --}}
    <div id="reservationFormWrapper"
         class="{{ ($selectedFacilityId || $errors->any()) ? 'block' : 'hidden' }} transition-all duration-300">
        <div class="p-6 sm:p-8 rounded-2xl bg-white/90 dark:bg-[#0c1419]/90 backdrop-blur-xl border border-[#0F5143]/30 shadow-lg">
            <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Formulir Peminjaman Fasilitas</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pilih ruangan, tentukan tanggal, dan tentukan slot jam operasional (07.00 - 20.00 WIB).</p>
                </div>
                <button type="button" onclick="toggleForm()" class="inline-flex items-center gap-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg cursor-pointer">
                    <kbd class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hidden sm:inline-flex">ESC</kbd>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('reservations.store') }}" class="space-y-5">
                @csrf

                {{-- Pilihan Fasilitas --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Fasilitas / Ruangan Kampus <span class="text-rose-500">*</span>
                    </label>
                    <select name="facility_id" required
                            class="block w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-[#0F5143]/50 focus:border-[#0F5143]">
                        <option value="">-- Pilih Fasilitas yang Akan Dipinjam --</option>
                        @foreach ($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ (old('facility_id', $selectedFacilityId) == $facility->id) ? 'selected' : '' }}>
                                {{ $facility->nama }} ({{ ucfirst(str_replace('_', ' ', $facility->tipe)) }} • {{ $facility->lokasi }} • Kapasitas: {{ $facility->kapasitas ? $facility->kapasitas.' org' : 'Fleksibel' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal & Jam Peminjaman --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Tanggal Pemakaian <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal"
                            min="{{ date('Y-m-d') }}"
                            value="{{ old('tanggal', request('tanggal', date('Y-m-d'))) }}"
                            required
                            class="block w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs sm:text-sm font-mono focus:ring-2 focus:ring-[#0F5143]/50 focus:border-[#0F5143]"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Jam Mulai (07.00 - 19.30) <span class="text-rose-500">*</span>
                        </label>
                        <select name="start_time" required
                                class="block w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs sm:text-sm font-mono focus:ring-2 focus:ring-[#0F5143]/50 focus:border-[#0F5143]">
                            <option value="">-- Jam Mulai --</option>
                            @php
                                $startTimes = ['07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30'];
                            @endphp
                            @foreach ($startTimes as $time)
                                <option value="{{ $time }}" {{ old('start_time') === $time ? 'selected' : '' }}>{{ $time }} WIB</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Jam Selesai (07.30 - 20.00) <span class="text-rose-500">*</span>
                        </label>
                        <select name="end_time" required
                                class="block w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs sm:text-sm font-mono focus:ring-2 focus:ring-[#0F5143]/50 focus:border-[#0F5143]">
                            <option value="">-- Jam Selesai --</option>
                            @php
                                $endTimes = ['07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00'];
                            @endphp
                            @foreach ($endTimes as $time)
                                <option value="{{ $time }}" {{ old('end_time') === $time ? 'selected' : '' }}>{{ $time }} WIB</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tujuan Penggunaan --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Tujuan Penggunaan & Keterangan Kegiatan <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        name="tujuan_penggunaan"
                        rows="3"
                        required
                        placeholder="Contoh: Perkuliahan pengganti mata kuliah Pemrograman Web, Rapat Kerja Organisasi BEM, Workshop UKM, dll."
                        class="block w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs sm:text-sm placeholder-slate-400 focus:ring-2 focus:ring-[#0F5143]/50 focus:border-[#0F5143] resize-y"
                    >{{ old('tujuan_penggunaan') }}</textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-[#0F5143] hover:bg-[#146353] shadow-cta transition-all cursor-pointer">
                        Kirim Pengajuan
                    </button>
                    <button type="button"
                            onclick="toggleForm()"
                            class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-white transition-colors cursor-pointer">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- DAFTAR RIWAYAT RESERVASI --}}
    <div class="p-6 rounded-2xl bg-white/80 dark:bg-[#0c1419]/80 backdrop-blur-md border border-slate-200/80 dark:border-teal-950/80 shadow-xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Riwayat Pengajuan Peminjaman</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftar permohonan ruangan yang Anda ajukan beserta status verifikasi petugas.</p>
            </div>
            <span class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400">
                Total: {{ $reservations->total() }} Pengajuan
            </span>
        </div>

        <div class="space-y-3">
            @forelse ($reservations as $res)
                <div class="p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/40 hover:border-[#0F5143]/50 transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-2 flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                #RES-{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                {{ $res->facility->nama ?? 'Fasilitas Tidak Ditemukan' }}
                            </h4>
                        </div>

                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                            <span class="flex items-center gap-1 font-mono text-[11px]">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $res->facility->lokasi ?? '-' }}
                            </span>
                            <span class="flex items-center gap-1 font-mono text-[11px]">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ \Illuminate\Support\Carbon::parse($res->tanggal)->translatedFormat('l, d F Y') }}
                            </span>
                            <span class="flex items-center gap-1 font-mono text-[11px] font-bold text-[#0F5143] dark:text-emerald-400">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ substr($res->start_time, 0, 5) }} – {{ substr($res->end_time, 0, 5) }} WIB
                            </span>
                        </div>

                        <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2">
                            <span class="font-bold text-slate-500 dark:text-slate-400">Tujuan:</span> {{ $res->tujuan_penggunaan }}
                        </p>

                        @if ($res->cancelled_reason)
                            <p class="text-[11px] text-rose-600 dark:text-rose-400 italic">
                                Catatan: {{ $res->cancelled_reason }}
                            </p>
                        @endif
                    </div>

                    {{-- Status Badge & Action Mandiri (H-1) --}}
                    <div class="flex items-center md:flex-col md:items-end justify-between gap-2.5 shrink-0">
                        @if ($res->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Disetujui
                            </span>
                        @elseif ($res->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-300 dark:border-amber-700">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Menunggu
                            </span>
                        @elseif ($res->status === 'rejected')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-300 dark:border-rose-700">
                                <span class="w-2 h-2 rounded-full bg-rose-600"></span> Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-700">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span> Dibatalkan
                            </span>
                        @endif

                        {{-- Tombol Batal Mandiri (Batas H-1 sesuai US 4 & ASSUMPTION.md 1.2) --}}
                        @if (in_array($res->status, ['pending', 'approved']))
                            @if ($res->tanggal->toDateString() > now()->toDateString())
                                <form method="POST" action="{{ route('reservations.cancel', $res->id) }}"
                                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan permohonan peminjaman ini (H-1)?');"
                                      class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="text-xs font-semibold text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 underline cursor-pointer"
                                            title="Pembatalan mandiri sebelum hari pelaksanaan">
                                        Batalkan Peminjaman (H-1)
                                    </button>
                                </form>
                            @else
                                <span class="text-[11px] text-slate-400 dark:text-slate-500 italic" title="Pembatalan pada hari-H hanya bisa dilakukan oleh petugas">
                                    Batas batal mandiri terlewat (H-1)
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-slate-500 dark:text-slate-400">
                    <svg class="w-10 h-10 mx-auto text-slate-300 dark:text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada pengajuan peminjaman fasilitas</p>
                    <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Buat Pengajuan Baru" di atas untuk meminjam ruangan kampus.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-3">
            {{ $reservations->links() }}
        </div>
    </div>

</div>

<script>
    function toggleForm() {
        const wrapper = document.getElementById('reservationFormWrapper');
        const text = document.getElementById('toggleButtonText');
        if (wrapper.classList.contains('hidden')) {
            wrapper.classList.remove('hidden');
            text.innerText = 'Tutup Formulir';
            wrapper.scrollIntoView({ behavior: 'smooth' });
        } else {
            wrapper.classList.add('hidden');
            text.innerText = 'Buat Pengajuan Baru';
        }
    }

    // Keyboard shortcut (N to toggle new reservation form, ESC to close)
    window.addEventListener('keydown', function(event) {
        if (event.key.toLowerCase() === 'n' && !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) {
            event.preventDefault();
            toggleForm();
        }
        if (event.key === 'Escape') {
            const wrapper = document.getElementById('reservationFormWrapper');
            if (wrapper && !wrapper.classList.contains('hidden')) {
                toggleForm();
            }
        }
    });
</script>
@endsection
