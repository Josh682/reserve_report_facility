<button type="button"
        onclick="window.toggleTheme()"
        aria-label="Ganti mode gelap/terang"
        title="Ganti Mode Tampilan (Terang / Gelap)"
        class="inline-flex items-center justify-center p-2 rounded-xs border border-slate-200 dark:border-teal-950/80 text-slate-600 dark:text-teal-300 bg-white dark:bg-[#0c1419] hover:bg-slate-100 dark:hover:bg-teal-950/40 hover:text-teal-700 dark:hover:text-teal-200 focus:outline-none focus:ring-1 focus:ring-teal-500 transition-colors cursor-pointer shadow-none">
    <!-- Sun icon (shown when dark) -->
    <svg class="w-4 h-4 theme-toggle-sun hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <!-- Moon icon (shown when light) -->
    <svg class="w-4 h-4 theme-toggle-moon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
</button>
