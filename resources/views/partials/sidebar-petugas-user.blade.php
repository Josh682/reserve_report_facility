<div class="pt-5 border-t border-white/30 dark:border-white/10 block space-y-3">
    <div class="flex items-center gap-3 px-2">
        <div class="w-9 h-9 rounded-2xl bg-[#0F5143] text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
            {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ auth()->user()->name ?? 'Petugas' }}</p>
            <p class="text-[10px] font-semibold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider truncate">
                Petugas Fasilitas
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-semibold text-rose-700 dark:text-rose-400 hover:bg-rose-50/70 dark:hover:bg-rose-950/30 border border-transparent hover:border-rose-200 dark:hover:border-rose-900 transition-all cursor-pointer">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span>Keluar Sistem</span>
        </button>
    </form>
</div>
