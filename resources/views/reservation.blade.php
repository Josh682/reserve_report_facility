@extends('layouts.pengguna')

@section('title', 'Peminjaman Fasilitas — FacilityHub')
@section('header_title', 'Reservasi Fasilitas')
@section('header_subtitle', 'Ajukan peminjaman ruangan kampus, pantau status persetujuan, serta kelola pembatalan mandiri H-1')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- ==========================================
         1. HEADER & TOMBOL AKSI UTAMA (FROSTED HERO)
         ========================================== --}}
    <div class="bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-5 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-60 h-60 rounded-full bg-emerald-400/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 border border-emerald-500/20 text-[#0F5143] dark:text-[#34D399] mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Portal Peminjaman Ruang & Lab</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                Peminjaman Fasilitas Saya
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-300 max-w-xl leading-relaxed">
                Ajukan peminjaman ruangan kampus, pantau status persetujuan dari petugas, serta kelola pembatalan mandiri maksimal H-1.
            </p>
        </div>

        @if (!$openForm)
            <div class="relative z-10 shrink-0">
                <button type="button"
                        id="toggleReservationForm"
                        onclick="toggleForm()"
                        class="kezak-btn-primary inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-bold shadow-md cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span id="toggleButtonText">Buat Pengajuan Baru</span>
                    <kbd class="ml-1 hidden sm:inline-flex text-[10px] font-mono px-1.5 py-0.5 rounded-md bg-white/20 text-white border border-white/30">N</kbd>
                </button>
            </div>
        @endif
    </div>

    {{-- ALERT VALIDASI / ERROR --}}
    @if ($errors->any())
        <div class="p-5 rounded-2xl bg-rose-500/15 backdrop-blur-xl border border-rose-400/40 text-rose-950 dark:text-rose-200 text-xs shadow-xs space-y-1.5">
            <strong class="font-bold block text-sm mb-1">Terdapat kendala pada pengajuan reservasi:</strong>
            <ul class="list-disc pl-5 space-y-0.5 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ==========================================
         2. FORMULIR PENGAJUAN PINJAM RUANG (TOGGLEABLE)
         ========================================== --}}
    <div id="reservationFormWrapper"
         class="{{ ($selectedFacilityId || $openForm || $errors->any()) ? 'block' : 'hidden' }} transition-all duration-300">
        <div class="p-6 sm:p-8 rounded-3xl bg-white/80 dark:bg-white/5 backdrop-blur-xl border border-white/70 dark:border-white/10 shadow-lg space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-white/40 dark:border-white/10">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Formulir Peminjaman Fasilitas</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-0.5">Pilih ruangan, tentukan tanggal, dan tentukan slot jam operasional (07.00 - 20.00 WIB).</p>
                </div>
                @if (!$openForm)
                    <button type="button" onclick="toggleForm()" class="inline-flex items-center gap-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg cursor-pointer">
                        <kbd class="text-[10px] font-mono px-1.5 py-0.5 rounded-md bg-white/60 dark:bg-white/10 border border-white/80 dark:border-white/20 hidden sm:inline-flex">ESC</kbd>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>

            <form id="reservationForm" method="POST" action="{{ route('reservations.store') }}" class="space-y-5">
                @csrf

                {{-- Pilihan Fasilitas --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                        Fasilitas / Ruangan Kampus <span class="text-rose-500">*</span>
                    </label>
                    <select name="facility_id" required
                            class="kezak-input block w-full px-3.5 py-2.5 text-xs sm:text-sm">
                        <option value="">-- Pilih Fasilitas yang Akan Dipinjam --</option>
                        @foreach ($facilities as $facility)
                            <option value="{{ $facility->id }}" data-schedule-url="{{ route('facilities.schedule', $facility) }}" {{ (old('facility_id', $selectedFacilityId) == $facility->id) ? 'selected' : '' }}>
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
                            class="kezak-input block w-full px-3.5 py-2.5 text-xs sm:text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Jam Mulai (07.00 - 19.30) <span class="text-rose-500">*</span>
                        </label>
                        <select name="start_time" required disabled data-selected="{{ old('start_time') }}"
                                class="kezak-input block w-full px-3.5 py-2.5 text-xs sm:text-sm">
                            <option value="">-- Jam Mulai --</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">
                            Jam Selesai (07.30 - 20.00) <span class="text-rose-500">*</span>
                        </label>
                        <select name="end_time" required disabled data-selected="{{ old('end_time') }}"
                                class="kezak-input block w-full px-3.5 py-2.5 text-xs sm:text-sm">
                            <option value="">-- Jam Selesai --</option>
                        </select>
                    </div>
                </div>

                <div class="text-xs text-slate-600 dark:text-slate-300">
                    <p data-schedule-status role="status" aria-live="polite">Pilih ruangan dan tanggal untuk melihat jam yang tersedia.</p>
                    <button type="button" data-schedule-retry hidden class="font-bold underline mt-2">Coba muat jadwal lagi</button>
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
                        class="kezak-input block w-full px-3.5 py-2.5 text-xs sm:text-sm resize-y"
                    >{{ old('tujuan_penggunaan') }}</textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" disabled
                            class="kezak-btn-primary px-6 py-2.5 text-xs sm:text-sm font-bold shadow-md cursor-pointer">
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

    {{-- ==========================================
         3. DAFTAR RIWAYAT RESERVASI (FROSTED CARD LIST)
         ========================================== --}}
    <div class="rounded-3xl bg-white/65 dark:bg-white/5 backdrop-blur-xl border border-white/60 dark:border-white/10 p-6 sm:p-7 space-y-5 shadow-xs">
        <div class="flex items-center justify-between pb-4 border-b border-white/40 dark:border-white/10">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight">Riwayat Pengajuan Peminjaman</h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-0.5">Daftar permohonan ruangan yang Anda ajukan beserta status verifikasi petugas.</p>
            </div>
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-white/60 dark:bg-white/10 px-3 py-1 rounded-full border border-white/70 dark:border-white/10">
                Total: {{ $reservations->total() }} Pengajuan
            </span>
        </div>

        <div class="space-y-3.5">
            @forelse ($reservations as $res)
                <div class="p-5 rounded-2xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/70 dark:border-white/10 hover:border-emerald-500/50 hover:shadow-sm transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-2 flex-1 min-w-0">
                        <div class="flex items-center gap-2.5">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono font-bold uppercase tracking-wider bg-white/80 dark:bg-white/10 text-slate-700 dark:text-slate-300 border border-white/90 dark:border-white/15 shadow-2xs">
                                #RES-{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <div class="flex items-center gap-1.5 min-w-0">
                                <div class="w-6 h-6 rounded-lg bg-emerald-100/80 dark:bg-emerald-950/60 text-[#0F5143] dark:text-[#34D399] flex items-center justify-center shrink-0">
                                    <x-facility-icon :tipe="$res->facility->tipe ?? 'aula'" class="w-3.5 h-3.5" />
                                </div>
                                <h4 class="text-sm font-extrabold text-slate-900 dark:text-white truncate">
                                    {{ $res->facility->nama ?? 'Fasilitas Tidak Ditemukan' }}
                                </h4>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-600 dark:text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <span>{{ $res->facility->lokasi ?? '-' }}</span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ \Illuminate\Support\Carbon::parse($res->tanggal)->translatedFormat('l, d F Y') }}</span>
                            </span>
                            <span class="flex items-center gap-1.5 font-bold text-[#0F5143] dark:text-[#34D399]">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ substr($res->start_time, 0, 5) }} – {{ substr($res->end_time, 0, 5) }} WIB</span>
                            </span>
                        </div>

                        <p class="text-xs text-slate-700 dark:text-slate-300 line-clamp-2">
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
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Disetujui
                            </span>
                        @elseif ($res->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-300 dark:border-amber-800 shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Menunggu
                            </span>
                        @elseif ($res->status === 'rejected')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-300 dark:border-rose-800 shadow-2xs">
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
                                            class="text-xs font-bold text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 underline cursor-pointer"
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

    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('reservationFormWrapper');
        const text = document.getElementById('toggleButtonText');
        const params = new URLSearchParams(window.location.search);
        const shouldOpen = params.get('open_form') === '1' || params.has('facility_id');

        if (wrapper && shouldOpen && wrapper.classList.contains('hidden')) {
            wrapper.classList.remove('hidden');
            if (text) {
                text.innerText = 'Tutup Formulir';
            }
            wrapper.scrollIntoView({ behavior: 'smooth' });
        }

        if (shouldOpen) {
            const topToggle = document.getElementById('toggleReservationForm');
            if (topToggle) {
                topToggle.style.display = 'none';
            }
        }
    });

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
