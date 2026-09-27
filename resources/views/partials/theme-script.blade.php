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

    window.updateThemeIcons = function (isDark) {
        if (typeof isDark === 'undefined') {
            isDark = document.documentElement.classList.contains('dark');
        }
        document.querySelectorAll('.theme-toggle-sun').forEach(el => {
            el.classList.toggle('hidden', !isDark);
        });
        document.querySelectorAll('.theme-toggle-moon').forEach(el => {
            el.classList.toggle('hidden', isDark);
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
