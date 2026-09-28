<script>
    (function () {
        function getTheme() {
            try {
                const stored = localStorage.getItem('facilityhub_theme');
                if (stored === 'dark' || stored === 'light') {
                    return stored;
                }
            } catch (e) {}

            const match = document.cookie.match(/(?:^|; )theme=([^;]*)/);
            if (match) {
                return decodeURIComponent(match[1]);
            }
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                return 'dark';
            }
            return 'light';
        }

        const theme = getTheme();
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    })();

    window.toggleTheme = function () {
        const isDark = document.documentElement.classList.toggle('dark');
        const theme = isDark ? 'dark' : 'light';
        document.cookie = 'theme=' + theme + ';path=/;max-age=' + (60 * 60 * 24 * 365) + ';SameSite=Lax';
        try {
            localStorage.setItem('facilityhub_theme', theme);
        } catch (e) {}
        window.updateThemeIcons(isDark);
    };

    window.applyTheme = function (isDark) {
        if (isDark) {
            document.documentElement.classList.add('dark');
            try { localStorage.setItem('facilityhub_theme', 'dark'); } catch(e) {}
            document.cookie = 'theme=dark;path=/;max-age=' + (60 * 60 * 24 * 365) + ';SameSite=Lax';
        } else {
            document.documentElement.classList.remove('dark');
            try { localStorage.setItem('facilityhub_theme', 'light'); } catch(e) {}
            document.cookie = 'theme=light;path=/;max-age=' + (60 * 60 * 24 * 365) + ';SameSite=Lax';
        }
        window.updateThemeIcons(isDark);
    };

    window.updateThemeIcons = function (isDark) {
        if (typeof isDark === 'undefined') {
            isDark = document.documentElement.classList.contains('dark');
        }

        // Sun Icons: Active in Light Mode with soft light green (hijau muda) background
        document.querySelectorAll('.theme-icon-sun, #theme-icon-sun').forEach(el => {
            if (isDark) {
                el.className = 'theme-icon-sun w-7 h-7 rounded-lg flex items-center justify-center text-amber-500 hover:text-amber-400 transition-all duration-200';
            } else {
                el.className = 'theme-icon-sun w-7 h-7 rounded-lg flex items-center justify-center bg-[#D1FAE5] border border-emerald-300/80 shadow-xs text-amber-500 transition-all duration-200';
            }
        });

        // Moon Icons: Active in Dark Mode with emerald green background
        document.querySelectorAll('.theme-icon-moon, #theme-icon-moon').forEach(el => {
            if (isDark) {
                el.className = 'theme-icon-moon w-7 h-7 rounded-lg flex items-center justify-center bg-emerald-600 border border-emerald-400/40 text-emerald-100 shadow-xs transition-all duration-200';
            } else {
                el.className = 'theme-icon-moon w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 transition-all duration-200';
            }
        });

        // Pill buttons title / aria-label
        document.querySelectorAll('.theme-toggle-pill, #theme-toggle').forEach(btn => {
            btn.setAttribute('title', isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap');
            btn.setAttribute('aria-label', isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap');
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            window.updateThemeIcons();
        });
    } else {
        window.updateThemeIcons();
    }
</script>
