@php
    $sidebarPendingCount = $sidebarPendingCount ?? ($stats['pending_users'] ?? \App\Models\User::where('status_akun', 'pending')->count());
    $sidebarDamagedCount = $sidebarDamagedCount ?? ($stats['dalam_perbaikan'] ?? \App\Models\Facility::where('status', 'dalam_perbaikan')->count());
@endphp

<!-- Navigation Menu -->
<nav class="space-y-1.5 block">
    <!-- 1. Dashboard -->
    <a href="{{ route('admin.dashboard') }}"
       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('admin.dashboard') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 hover:bg-white/40 font-medium text-sm' }}">
        <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-600 group-hover:text-slate-900' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2"/>
            <rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2"/>
            <rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2"/>
            <rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2"/>
        </svg>
        <span>Dashboard</span>
    </a>

    <!-- 2. Pengelolaan Fasilitas -->
    <a href="{{ route('admin.facilities.index') }}"
       class="flex items-center justify-between px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('admin.facilities.*') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 hover:bg-white/40 font-medium text-sm' }}">
        <div class="flex items-center gap-3.5">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.facilities.*') ? 'text-white' : 'text-slate-600 group-hover:text-slate-900' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Pengelolaan Fasilitas</span>
        </div>
        @if ($sidebarDamagedCount > 0)
            <span class="w-5 h-5 rounded-full bg-rose-500 text-white text-[11px] font-bold flex items-center justify-center shrink-0 shadow-xs" title="{{ $sidebarDamagedCount }} fasilitas rusak">
                {{ $sidebarDamagedCount }}
            </span>
        @endif
    </a>

    <!-- 3. Pengelolaan Akun -->
    <a href="{{ route('admin.users.index') }}"
       class="flex items-center justify-between px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('admin.users.*') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 hover:bg-white/40 font-medium text-sm' }}"
       title="Verifikasi Akun ({{ $sidebarPendingCount }} Menunggu Verifikasi)">
        <div class="flex items-center gap-3.5">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-600 group-hover:text-slate-900' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Pengelolaan Akun</span>
        </div>
        @if ($sidebarPendingCount > 0)
            <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-800 border border-amber-300 text-[11px] font-bold flex items-center justify-center shrink-0 shadow-xs" title="{{ $sidebarPendingCount }} akun pending">
                {{ $sidebarPendingCount }}
            </span>
        @endif
    </a>

    <!-- 4. Rekapitulasi & Ekspor -->
    <a href="{{ route('admin.rekap.index') }}"
       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('admin.rekap.*') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 hover:bg-white/40 font-medium text-sm' }}">
        <svg class="w-5 h-5 {{ request()->routeIs('admin.rekap.*') ? 'text-white' : 'text-slate-600 group-hover:text-slate-900' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <span>Rekapitulasi & Ekspor</span>
    </a>
</nav>

<!-- Aksi Cepat Admin -->
<div class="pt-5 border-t border-white/30 space-y-2 block">
    <div class="flex items-center justify-between px-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">
        <span>Aksi Cepat Admin</span>
    </div>

    <!-- Shortcut 1: Tambah Akun Langsung -->
    <a href="{{ route('admin.users.create') }}"
       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/60 hover:bg-white/85 border border-white/70 text-slate-700 text-xs font-semibold transition-all shadow-2xs group"
       title="Tambahkan Akun Langsung">
        <div class="flex items-center gap-2.5">
            <div class="w-6 h-6 rounded-lg bg-emerald-100/90 text-emerald-800 flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <span class="group-hover:text-[#0F5143] transition-colors">Tambah Akun Langsung</span>
        </div>
        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    <!-- Shortcut 2: Tambah Fasilitas Langsung -->
    <a href="{{ route('admin.facilities.create') }}"
       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/60 hover:bg-white/85 border border-white/70 text-slate-700 text-xs font-semibold transition-all shadow-2xs group"
       title="Tambahkan Fasilitas Langsung">
        <div class="flex items-center gap-2.5">
            <div class="w-6 h-6 rounded-lg bg-teal-100/90 text-teal-800 flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="group-hover:text-[#0F5143] transition-colors">Tambah Fasilitas Langsung</span>
        </div>
        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>
