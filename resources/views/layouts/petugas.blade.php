<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel Petugas') — {{ config('app.name', 'FacilityHub') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @fonts

    <!-- Prevent FOUC: Apply dark mode immediately -->
    <script>
        (function() {
            try {
                function getTheme() {
                    const match = document.cookie.match(/(?:^|; )theme=([^;]*)/);
                    if (match) return decodeURIComponent(match[1]);
                    if (localStorage.getItem('facilityhub_theme')) return localStorage.getItem('facilityhub_theme');
                    return 'light';
                }
                if (getTheme() === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        /* =======================================================
           FACILITYHUB LIGHT & DARK MODE FROSTED GLASSMORPHISM STYLES
           ======================================================= */
        body {
            background-color: #EDF7F4 !important;
            background-image: 
                radial-gradient(ellipse at 15% 15%, rgba(52, 211, 153, 0.18) 0%, transparent 60%),
                radial-gradient(ellipse at 85% 85%, rgba(94, 234, 212, 0.18) 0%, transparent 60%),
                linear-gradient(135deg, #F0FAF7 0%, #E6F5F1 100%) !important;
            color: #1E293B;
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
        }

        .ambient-blob-1 { background-color: rgba(52, 211, 153, 0.25) !important; }
        .ambient-blob-2 { background-color: rgba(94, 234, 212, 0.25) !important; }
        .ambient-blob-3 { background-color: rgba(254, 240, 138, 0.15) !important; }

        .kezak-btn-primary {
            background-color: #0F5143 !important;
            color: #FFFFFF !important;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(15, 81, 67, 0.25);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .kezak-btn-primary:hover {
            background-color: #146353 !important;
            box-shadow: 0 6px 20px rgba(15, 81, 67, 0.35);
            transform: translateY(-1px);
        }
        .kezak-btn-primary:active {
            transform: translateY(0);
        }

        .kezak-input {
            border-radius: 12px;
            border: 1.5px solid rgba(148, 163, 184, 0.65);
            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03), 0 1px 2px rgba(255, 255, 255, 0.70);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            color: #0F172A;
        }
        .kezak-input::placeholder {
            color: #64748B;
        }
        .kezak-input:hover {
            background: rgba(255, 255, 255, 0.75);
            border-color: #0F5143;
        }
        .kezak-input:focus,
        .kezak-input:focus-within,
        .kezak-input:active,
        select.kezak-input:focus {
            background-color: #E8F8F3 !important;
            border-color: #10B981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25), inset 0 1px 1px rgba(0, 0, 0, 0.02) !important;
            outline: none !important;
        }

        /* Frosted Glass System Classes */
        .glass-shell {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.70);
            box-shadow: 0 25px 60px rgba(15, 81, 67, 0.08), inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.95);
        }
        .glass-card-main {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.62) 0%, rgba(255, 255, 255, 0.35) 100%);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.70);
            box-shadow: 0 10px 30px rgba(15, 81, 67, 0.05), inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.95), inset -1px 0 1px 0 rgba(255, 255, 255, 0.40);
        }
        .glass-card-interactive {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.65) 0%, rgba(255, 255, 255, 0.38) 100%);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.75);
            box-shadow: 0 8px 24px rgba(15, 81, 67, 0.04), inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.95), inset -1px 0 1px 0 rgba(255, 255, 255, 0.40);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card-interactive:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.80) 0%, rgba(255, 255, 255, 0.50) 100%);
            border-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 16px 36px rgba(15, 81, 67, 0.10), inset 0 1.5px 1.5px 0 #FFFFFF, inset -1px 0 1px 0 rgba(255, 255, 255, 0.60);
            transform: translateY(-2px);
        }
        .glass-card-nested {
            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(14px) saturate(160%);
            -webkit-backdrop-filter: blur(14px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.80);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03), inset 0 1px 1px 0 rgba(255, 255, 255, 0.90);
        }

        .theme-toggle-pill {
            background: rgba(255, 255, 255, 0.50);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04), inset 0 1px 1px rgba(255,255,255,0.9);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }
        .theme-icon-sun, .theme-icon-moon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        html:not(.dark) .theme-toggle-pill .theme-icon-sun,
        html:not(.dark) #theme-icon-sun {
            background-color: #D1FAE5 !important;
            border: 1px solid rgba(52, 211, 153, 0.6) !important;
            color: #F59E0B !important;
            box-shadow: 0 1px 3px rgba(16, 185, 129, 0.18), inset 0 1px 1px rgba(255, 255, 255, 0.8) !important;
        }
        html:not(.dark) .theme-toggle-pill .theme-icon-moon,
        html:not(.dark) #theme-icon-moon {
            background-color: transparent !important;
            border: 1px solid transparent !important;
            color: #64748B !important;
            box-shadow: none !important;
        }
        html:not(.dark) .theme-toggle-pill .theme-icon-moon:hover,
        html:not(.dark) #theme-icon-moon:hover {
            color: #334155 !important;
        }

        /* Light Mode Autofill / Autocomplete Override (Soft Mint Green #E8F8F3) */
        .kezak-input:-webkit-autofill,
        .kezak-input:-webkit-autofill:hover, 
        .kezak-input:-webkit-autofill:focus, 
        .kezak-input:-webkit-autofill:active,
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #E8F8F3 inset !important;
            box-shadow: 0 0 0 1000px #E8F8F3 inset !important;
            -webkit-text-fill-color: #0F5143 !important;
            color: #0F5143 !important;
            border-color: #10B981 !important;
            caret-color: #0F5143 !important;
            transition: background-color 5000s ease-in-out 0s;
        }
        .kezak-input:autofill,
        .kezak-input:autofill:hover,
        .kezak-input:autofill:focus,
        .kezak-input:autofill:active,
        input:autofill,
        input:autofill:hover,
        input:autofill:focus,
        input:autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #E8F8F3 inset !important;
            box-shadow: 0 0 0 1000px #E8F8F3 inset !important;
            -webkit-text-fill-color: #0F5143 !important;
            color: #0F5143 !important;
            border-color: #10B981 !important;
            caret-color: #0F5143 !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* --- DARK MODE (FULL DARK PAGE + FROSTED OBSIDIAN GLASS) --- */
        html.dark body {
            background-color: #040908 !important;
            background-image: 
                radial-gradient(ellipse at 50% 0%, #082620 0%, transparent 75%),
                radial-gradient(ellipse at 85% 85%, #051A16 0%, transparent 65%),
                linear-gradient(135deg, #040A09 0%, #020706 100%) !important;
            color: #F8FAFC !important;
        }

        html.dark .ambient-blob-1 { background-color: rgba(16, 185, 129, 0.12) !important; }
        html.dark .ambient-blob-2 { background-color: rgba(13, 148, 136, 0.10) !important; }
        html.dark .ambient-blob-3 { background-color: rgba(5, 150, 105, 0.08) !important; }

        html.dark .glass-shell {
            background: rgba(8, 20, 17, 0.75) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.85), inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.12) !important;
        }
        html.dark .glass-card-main {
            background: linear-gradient(135deg, rgba(12, 26, 22, 0.75) 0%, rgba(6, 16, 13, 0.85) 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.55), inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.12), inset -1px 0 1px 0 rgba(255, 255, 255, 0.04) !important;
        }
        html.dark .glass-card-interactive {
            background: linear-gradient(135deg, rgba(14, 30, 25, 0.70) 0%, rgba(8, 18, 15, 0.80) 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.45), inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.10), inset -1px 0 1px 0 rgba(255, 255, 255, 0.04) !important;
        }
        html.dark .glass-card-interactive:hover {
            background: linear-gradient(135deg, rgba(18, 38, 32, 0.82) 0%, rgba(10, 24, 20, 0.90) 100%) !important;
            border-color: rgba(52, 211, 153, 0.35) !important;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.70), inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.18), inset -1px 0 1px 0 rgba(255, 255, 255, 0.06) !important;
            transform: translateY(-2px);
        }
        html.dark .glass-card-nested {
            background: rgba(14, 32, 26, 0.60) !important;
            border: 1px solid rgba(255, 255, 255, 0.10) !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.35), inset 0 1px 1px 0 rgba(255, 255, 255, 0.08) !important;
        }

        html.dark .kezak-input {
            background: rgba(14, 32, 26, 0.60) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #FFFFFF !important;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.35), 0 1px 1px rgba(255, 255, 255, 0.04) !important;
        }
        html.dark .kezak-input::placeholder { color: #94A3B8 !important; }
        html.dark .kezak-input:hover {
            background: rgba(18, 42, 34, 0.75) !important;
            border-color: rgba(255, 255, 255, 0.25) !important;
        }
        html.dark .kezak-input:focus,
        html.dark .kezak-input:focus-within,
        html.dark .kezak-input:active,
        html.dark select.kezak-input:focus {
            background-color: rgba(16, 44, 36, 0.85) !important;
            border-color: #10B981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.35), inset 0 1px 1px rgba(0, 0, 0, 0.2) !important;
            color: #FFFFFF !important;
            outline: none !important;
        }
        html.dark select.kezak-input option {
            background-color: #071D18 !important;
            color: #F8FAFC !important;
        }

        /* Dark Mode Autofill / Autocomplete Override (Deep Emerald Green #062E25) */
        html.dark .kezak-input:-webkit-autofill,
        html.dark .kezak-input:-webkit-autofill:hover, 
        html.dark .kezak-input:-webkit-autofill:focus, 
        html.dark .kezak-input:-webkit-autofill:active,
        html.dark input:-webkit-autofill,
        html.dark input:-webkit-autofill:hover, 
        html.dark input:-webkit-autofill:focus, 
        html.dark input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #062E25 inset !important;
            box-shadow: 0 0 0 1000px #062E25 inset !important;
            -webkit-text-fill-color: #ECFDF5 !important;
            color: #ECFDF5 !important;
            border-color: #059669 !important;
            caret-color: #34D399 !important;
            transition: background-color 5000s ease-in-out 0s;
        }
        html.dark .kezak-input:autofill,
        html.dark .kezak-input:autofill:hover,
        html.dark .kezak-input:autofill:focus,
        html.dark .kezak-input:autofill:active,
        html.dark input:autofill,
        html.dark input:autofill:hover,
        html.dark input:autofill:focus,
        html.dark input:autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #062E25 inset !important;
            box-shadow: 0 0 0 1000px #062E25 inset !important;
            -webkit-text-fill-color: #ECFDF5 !important;
            color: #ECFDF5 !important;
            border-color: #059669 !important;
            caret-color: #34D399 !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        html.dark .theme-toggle-pill {
            background: rgba(0, 0, 0, 0.40) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.15) !important;
        }
        html.dark .theme-toggle-pill .theme-icon-sun,
        html.dark #theme-icon-sun {
            background-color: transparent !important;
            border: 1px solid transparent !important;
            color: #F59E0B !important;
            box-shadow: none !important;
        }
        html.dark .theme-toggle-pill .theme-icon-sun:hover,
        html.dark #theme-icon-sun:hover {
            color: #FBBF24 !important;
        }
        html.dark .theme-toggle-pill .theme-icon-moon,
        html.dark #theme-icon-moon {
            background-color: #059669 !important;
            border: 1px solid rgba(52, 211, 153, 0.4) !important;
            color: #A7F3D0 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3), inset 0 1px 1px rgba(255, 255, 255, 0.2) !important;
        }

        html.dark .kezak-btn-primary {
            background: linear-gradient(135deg, #0F5143 0%, #0B3D32 100%) !important;
            border: 1px solid rgba(52, 211, 153, 0.3) !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.15) !important;
        }
        html.dark .kezak-btn-primary:hover {
            background: linear-gradient(135deg, #146353 0%, #0F5143 100%) !important;
            border-color: rgba(52, 211, 153, 0.5) !important;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.25), inset 0 1px 1px rgba(255, 255, 255, 0.25) !important;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen relative overflow-x-hidden font-sans antialiased text-slate-800 dark:text-slate-100 p-3 sm:p-5 lg:p-7 flex flex-col justify-center" style="font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;">

    <!-- ==========================================
         AMBIENT BLOBS (Kunci Efek Kaca Frosted)
         ========================================== -->
    <div class="ambient-blob-1 fixed top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full blur-[120px] pointer-events-none -z-0"></div>
    <div class="ambient-blob-2 fixed bottom-[-10%] right-[-5%] w-[600px] h-[600px] rounded-full blur-[140px] pointer-events-none -z-0"></div>
    <div class="ambient-blob-3 fixed top-[40%] right-[30%] w-[350px] h-[350px] rounded-full blur-[100px] pointer-events-none -z-0"></div>

    <!-- Mobile Sidebar Backdrop -->
    <div id="petugas-sidebar-backdrop" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs hidden lg:hidden" onclick="togglePetugasSidebar()"></div>

    <!-- ==========================================
         MASTER GLASS CONTAINER WINDOW
         ========================================== -->
    <div class="w-full max-w-[1420px] mx-auto glass-shell rounded-[32px] p-5 sm:p-7 lg:p-8 relative z-10 flex flex-col lg:flex-row gap-7 my-2 sm:my-6 min-h-[880px]">

        <!-- ==========================================
             LEFT SIDEBAR (FROSTED SIDEBAR)
             ========================================== -->
        <aside id="petugas-sidebar-panel" class="w-full lg:w-64 shrink-0 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-white/40 dark:border-white/10 pb-6 lg:pb-0 lg:pr-6">
            <div class="space-y-6">
                <!-- Brand Header -->
                <div class="flex items-center justify-between pb-4 border-b border-white/30 dark:border-white/10">
                    <a href="{{ route('petugas.dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-2xl bg-white/70 dark:bg-white/10 backdrop-blur-md border border-white/80 dark:border-white/15 shadow-xs flex items-center justify-center text-[#0F5143] dark:text-[#34D399] group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6 text-[#0F5143] dark:text-[#34D399]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="font-extrabold text-xl tracking-tight text-slate-800 dark:text-white block leading-tight">FacilityHub</span>
                            <span class="text-[11px] font-semibold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider block">Petugas Fasilitas</span>
                        </div>
                    </a>

                    <!-- Mobile Menu Hamburger -->
                    <button type="button" class="lg:hidden p-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-white/40 dark:hover:bg-white/10" onclick="togglePetugasSidebar()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Menu -->
                <nav id="petugas-nav-links" class="space-y-1.5 hidden lg:block">
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

                    <!-- 2. Katalog Fasilitas -->
                    <a href="{{ route('petugas.facilities') }}"
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('petugas.facilities*') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5 font-medium text-sm' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('petugas.facilities*') ? 'text-white' : 'text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Katalog Fasilitas</span>
                    </a>

                    @if (Route::has('petugas.reservations.index'))
                        <!-- 3. Antrean Reservasi (Jika modul reservasi aktif) -->
                        <a href="{{ route('petugas.reservations.index') }}"
                           class="flex items-center justify-between px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('petugas.reservations.*') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5 font-medium text-sm' }}">
                            <div class="flex items-center gap-3.5">
                                <svg class="w-5 h-5 {{ request()->routeIs('petugas.reservations.*') ? 'text-white' : 'text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Antrean Reservasi</span>
                            </div>
                        </a>
                    @endif

                    @if (Route::has('petugas.reports.index'))
                        @php
                            $pendingReportsSidebar = \App\Models\Report::where('status', 'baru')->count();
                        @endphp
                        <!-- 4. Laporan Kerusakan (Jika modul laporan aktif) -->
                        <a href="{{ route('petugas.reports.index') }}"
                           class="flex items-center justify-between px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('petugas.reports.*') ? 'bg-[#0F5143] text-white shadow-md font-semibold text-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-white/40 dark:hover:bg-white/5 font-medium text-sm' }}">
                            <div class="flex items-center gap-3.5">
                                <svg class="w-5 h-5 {{ request()->routeIs('petugas.reports.*') ? 'text-white' : 'text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>Laporan Kerusakan</span>
                            </div>
                            @if ($pendingReportsSidebar > 0)
                                <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-800 dark:bg-rose-950/80 dark:text-rose-200 border border-rose-300 dark:border-rose-800 text-[11px] font-bold flex items-center justify-center shrink-0 shadow-xs" title="{{ $pendingReportsSidebar }} laporan baru">
                                    {{ $pendingReportsSidebar }}
                                </span>
                            @endif
                        </a>
                    @endif
                </nav>
            </div>

            <!-- Bottom: User & Logout -->
            <div class="pt-5 border-t border-white/30 dark:border-white/10 hidden lg:block space-y-3">
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
        </aside>

        <!-- ==========================================
             MAIN CONTENT AREA (TOPBAR + CONTENT)
             ========================================== -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Topbar (Glass Nav) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/30 dark:border-white/10 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                        @hasSection('header_title')
                            @yield('header_title')
                        @else
                            @yield('title', 'Panel Petugas')
                        @endif
                    </h1>
                    @hasSection('header_subtitle')
                        <p class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-400 mt-0.5">
                            @yield('header_subtitle')
                        </p>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 bg-white/60 dark:bg-white/5 backdrop-blur-md px-3.5 py-2 rounded-2xl border border-white/80 dark:border-white/10 shadow-xs">
                        <svg class="w-3.5 h-3.5 text-[#0F5143] dark:text-[#34D399]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>

                    @include('partials.theme-toggle')

                    <div class="flex items-center gap-2.5 bg-white/70 dark:bg-white/5 backdrop-blur-md border border-white/80 dark:border-white/10 pl-2 pr-3.5 py-1.5 rounded-2xl shadow-xs">
                        <div class="w-8 h-8 rounded-xl bg-[#0F5143] text-white flex items-center justify-center font-bold text-xs shadow-xs">
                            {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="text-left leading-tight hidden sm:block">
                            <span class="block text-xs font-bold text-slate-800 dark:text-white">{{ auth()->user()->name ?? 'Petugas' }}</span>
                            <span class="block text-[10px] font-semibold text-emerald-800 dark:text-emerald-400">Petugas Fasilitas</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flash Status Messages with Frosted Glass Styling -->
            @if (session('status'))
                <div class="mb-5 rounded-2xl bg-teal-500/15 backdrop-blur-xl border border-teal-400/40 p-4 flex items-start gap-3 text-teal-950 dark:text-teal-200 text-xs sm:text-sm shadow-[0_4px_16px_rgba(15,81,67,0.06),inset_0_1px_1px_rgba(255,255,255,0.8)]">
                    <svg class="w-5 h-5 text-[#0F5143] dark:text-[#34D399] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1 font-semibold">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @if (session('status_warning'))
                <div class="mb-5 rounded-2xl bg-amber-500/15 backdrop-blur-xl border border-amber-400/40 p-4 flex items-start gap-3 text-amber-950 dark:text-amber-200 text-xs sm:text-sm shadow-[0_4px_16px_rgba(217,119,6,0.06),inset_0_1px_1px_rgba(255,255,255,0.8)]">
                    <svg class="w-5 h-5 text-amber-700 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="flex-1 font-semibold">
                        {{ session('status_warning') }}
                    </div>
                </div>
            @endif

            @if (session('status_error'))
                <div class="mb-5 rounded-2xl bg-rose-500/15 backdrop-blur-xl border border-rose-400/40 p-4 flex items-start gap-3 text-rose-950 dark:text-rose-200 text-xs sm:text-sm shadow-[0_4px_16px_rgba(225,29,72,0.06),inset_0_1px_1px_rgba(255,255,255,0.8)]">
                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1 font-semibold">
                        {{ session('status_error') }}
                    </div>
                </div>
            @endif

            <div class="flex-1">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function togglePetugasSidebar() {
            const nav = document.getElementById('petugas-nav-links');
            const backdrop = document.getElementById('petugas-sidebar-backdrop');
            if (nav) {
                nav.classList.toggle('hidden');
            }
            if (backdrop) {
                backdrop.classList.toggle('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
