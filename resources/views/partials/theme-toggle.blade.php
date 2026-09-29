<button type="button"
        id="theme-toggle"
        onclick="toggleTheme()"
        class="theme-toggle-pill relative inline-flex items-center gap-1 p-1 rounded-xl transition-all cursor-pointer hover:scale-105 select-none"
        title="Ganti Mode Terang / Gelap"
        aria-label="Ganti Mode Terang / Gelap">
    
    <!-- Sun Icon (Light Mode - Aktif Berwarna Hijau Muda) -->
    <span id="theme-icon-sun" class="theme-icon-sun w-7 h-7 rounded-lg flex items-center justify-center transition-all duration-200" title="Mode Terang">
        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
        </svg>
    </span>

    <!-- Moon Icon (Dark Mode - Aktif Berwarna Hijau Emerald) -->
    <span id="theme-icon-moon" class="theme-icon-moon w-7 h-7 rounded-lg flex items-center justify-center transition-all duration-200" title="Mode Gelap">
        <svg class="w-4 h-4 text-slate-400 dark:text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
        </svg>
    </span>
</button>

<script>
    (function() {
        if (typeof window.applyTheme === 'undefined') {
            window.applyTheme = function(isDark) {
                const html = document.documentElement;
                const sunIcons = document.querySelectorAll('.theme-icon-sun, #theme-icon-sun');
                const moonIcons = document.querySelectorAll('.theme-icon-moon, #theme-icon-moon');
                const toggleBtns = document.querySelectorAll('.theme-toggle-pill, #theme-toggle');

                if (isDark) {
                    html.classList.add('dark');
                    try { localStorage.setItem('facilityhub_theme', 'dark'); } catch(e) {}
                    document.cookie = 'theme=dark;path=/;max-age=' + (60 * 60 * 24 * 365) + ';SameSite=Lax';
                    toggleBtns.forEach(function(btn) {
                        btn.setAttribute('aria-label', 'Beralih ke Mode Terang');
                        btn.setAttribute('title', 'Beralih ke Mode Terang');
                    });
                    sunIcons.forEach(function(icon) {
                        icon.className = 'theme-icon-sun w-7 h-7 rounded-lg flex items-center justify-center text-amber-500 hover:text-amber-400 transition-all duration-200';
                    });
                    moonIcons.forEach(function(icon) {
                        icon.className = 'theme-icon-moon w-7 h-7 rounded-lg flex items-center justify-center bg-emerald-600 text-emerald-100 shadow-xs transition-all duration-200';
                    });
                } else {
                    html.classList.remove('dark');
                    try { localStorage.setItem('facilityhub_theme', 'light'); } catch(e) {}
                    document.cookie = 'theme=light;path=/;max-age=' + (60 * 60 * 24 * 365) + ';SameSite=Lax';
                    toggleBtns.forEach(function(btn) {
                        btn.setAttribute('aria-label', 'Beralih ke Mode Gelap');
                        btn.setAttribute('title', 'Beralih ke Mode Gelap');
                    });
                    sunIcons.forEach(function(icon) {
                        icon.className = 'theme-icon-sun w-7 h-7 rounded-lg flex items-center justify-center bg-[#D1FAE5] border border-emerald-300/80 shadow-xs text-amber-500 transition-all duration-200';
                    });
                    moonIcons.forEach(function(icon) {
                        icon.className = 'theme-icon-moon w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 transition-all duration-200';
                    });
                }
            };

            window.toggleTheme = function() {
                const isDark = document.documentElement.classList.contains('dark');
                window.applyTheme(!isDark);
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    window.applyTheme(document.documentElement.classList.contains('dark'));
                });
            } else {
                window.applyTheme(document.documentElement.classList.contains('dark'));
            }
        }
    })();
</script>
