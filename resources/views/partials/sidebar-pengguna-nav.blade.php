<!-- Navigation Menu -->
<nav class="space-y-1.5 block">
    <!-- 1. Dashboard Saya -->
    <a href="{{ route('pengguna.dashboard') }}"
       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('pengguna.dashboard') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5 font-medium text-sm' }}">
        <svg class="w-5 h-5 {{ request()->routeIs('pengguna.dashboard') ? 'text-white' : 'text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2"/>
            <rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2"/>
            <rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2"/>
            <rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2"/>
        </svg>
        <span>Dashboard Saya</span>
    </a>

    <!-- 2. Katalog Fasilitas -->
    <a href="{{ route('facilities') }}"
       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('facilities*') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5 font-medium text-sm' }}">
        <svg class="w-5 h-5 {{ request()->routeIs('facilities*') ? 'text-white' : 'text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <span>Katalog Fasilitas</span>
    </a>

    <!-- 3. Peminjaman Ruang -->
    <a href="{{ route('reservation') }}"
       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('reservation*') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5 font-medium text-sm' }}">
        <svg class="w-5 h-5 {{ request()->routeIs('reservation*') ? 'text-white' : 'text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span>Peminjaman Ruang</span>
    </a>

    <!-- 4. Laporan Kendala -->
    <a href="{{ route('report') }}"
       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('report*') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5 font-medium text-sm' }}">
        <svg class="w-5 h-5 {{ request()->routeIs('report*') ? 'text-white' : 'text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span>Laporan Kendala</span>
    </a>
</nav>

<!-- Aksi Cepat Pengguna -->
<div class="pt-5 border-t border-white/30 dark:border-white/10 space-y-2 block">
    <div class="flex items-center justify-between px-2 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
        <span>Aksi Cepat</span>
    </div>

    <!-- Shortcut 1: Pinjam Ruangan -->
    <a href="{{ route('reservation') }}"
       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/60 dark:bg-white/5 hover:bg-white/85 dark:hover:bg-white/10 border border-white/70 dark:border-white/10 text-slate-700 dark:text-slate-300 text-xs font-semibold transition-all shadow-2xs group">
        <div class="flex items-center gap-2.5">
            <div class="w-6 h-6 rounded-lg bg-emerald-100/90 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span class="group-hover:text-[#0F5143] dark:group-hover:text-emerald-400 transition-colors">Pinjam Ruangan</span>
        </div>
        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    <!-- Shortcut 2: Lapor Kerusakan -->
    <a href="{{ route('report') }}"
       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/60 dark:bg-white/5 hover:bg-white/85 dark:hover:bg-white/10 border border-white/70 dark:border-white/10 text-slate-700 dark:text-slate-300 text-xs font-semibold transition-all shadow-2xs group">
        <div class="flex items-center gap-2.5">
            <div class="w-6 h-6 rounded-lg bg-rose-100/90 dark:bg-rose-950/60 text-rose-800 dark:text-rose-400 flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <span class="group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">Lapor Kerusakan</span>
        </div>
        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>
