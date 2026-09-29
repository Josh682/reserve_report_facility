@php
    $pendingReservationsSidebar = \App\Models\Reservation::where('status', 'pending')->count();
    $pendingReportsSidebar = \App\Models\Report::where('status', 'baru')->count();
@endphp

<!-- Navigation Menu -->
<nav class="space-y-1.5 block">
    <!-- 1. Dashboard Petugas -->
    <a href="{{ route('petugas.dashboard') }}"
       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('petugas.dashboard') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5 font-medium text-sm' }}">
        <svg class="w-5 h-5 {{ request()->routeIs('petugas.dashboard') ? 'text-white' : 'text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2"/>
            <rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2"/>
            <rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2"/>
            <rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2"/>
        </svg>
        <span>Dashboard Petugas</span>
    </a>

    <!-- 2. Antrean Reservasi -->
    <a href="{{ route('petugas.reservations.index') }}"
       class="flex items-center justify-between px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('petugas.reservations.*') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5 font-medium text-sm' }}">
        <div class="flex items-center gap-3.5">
            <svg class="w-5 h-5 {{ request()->routeIs('petugas.reservations.*') ? 'text-white' : 'text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Antrean Reservasi</span>
        </div>
        @if ($pendingReservationsSidebar > 0)
            <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-800 border border-amber-300 text-[11px] font-bold flex items-center justify-center shrink-0 shadow-xs" title="{{ $pendingReservationsSidebar }} reservasi pending">
                {{ $pendingReservationsSidebar }}
            </span>
        @endif
    </a>

    <!-- 3. Laporan Fasilitas (US 8, 11, 12) -->
    <a href="{{ route('petugas.reports.index') }}"
       class="flex items-center justify-between px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('petugas.reports.*') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5 font-medium text-sm' }}">
        <div class="flex items-center gap-3.5">
            <svg class="w-5 h-5 {{ request()->routeIs('petugas.reports.*') ? 'text-white' : 'text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>Laporan Fasilitas</span>
        </div>
        @if ($pendingReportsSidebar > 0)
            <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-800 border border-rose-300 text-[11px] font-bold flex items-center justify-center shrink-0 shadow-xs" title="{{ $pendingReportsSidebar }} laporan baru">
                {{ $pendingReportsSidebar }}
            </span>
        @endif
    </a>
</nav>
