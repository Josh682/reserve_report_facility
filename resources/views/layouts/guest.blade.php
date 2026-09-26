<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'FacilityHub — Sistem Reservasi & Pelaporan Fasilitas Kampus' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @fonts

    <!-- Prevent FOUC: Apply dark mode immediately only if user explicitly saved 'dark' -->
    <script>
        (function() {
            try {
                if (localStorage.getItem('facilityhub_theme') === 'dark') {
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
           FACILITYHUB DUAL-MODE FROSTED GLASSMORPHISM STYLES
           ======================================================= */

        /* --- LIGHT MODE (RESTORED EXACTLY TO ORIGINAL) --- */
        body {
            background-color: #EDF7F4 !important;
            background-image: 
                radial-gradient(ellipse at 15% 15%, rgba(52, 211, 153, 0.18) 0%, transparent 60%),
                radial-gradient(ellipse at 85% 85%, rgba(94, 234, 212, 0.18) 0%, transparent 60%),
                linear-gradient(135deg, #F0FAF7 0%, #E6F5F1 100%) !important;
            color: #1E293B;
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .ambient-blob-1 { background-color: rgba(52, 211, 153, 0.25) !important; }
        .ambient-blob-2 { background-color: rgba(94, 234, 212, 0.25) !important; }
        .ambient-blob-3 { background-color: rgba(254, 240, 138, 0.15) !important; }
        .ambient-blob-focus-1 { background-color: rgba(52, 211, 153, 0.30) !important; }
        .ambient-blob-focus-2 { background-color: rgba(94, 234, 212, 0.30) !important; }
        .ambient-blob-focus-3 { background-color: rgba(254, 240, 138, 0.20) !important; }

        .auth-master-card {
            background: rgba(255, 255, 255, 0.40);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border: 1px solid rgba(255, 255, 255, 0.70);
            box-shadow: 0 25px 60px rgba(15, 81, 67, 0.08), 0 4px 16px rgba(0, 0, 0, 0.02);
        }

        .auth-glass-panel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.65) 0%, rgba(255, 255, 255, 0.38) 100%);
            backdrop-filter: blur(32px) saturate(190%);
            -webkit-backdrop-filter: blur(32px) saturate(190%);
            border-color: rgba(255, 255, 255, 0.60);
            box-shadow: 
                inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.95),
                inset -1px 0 1px 0 rgba(255, 255, 255, 0.40);
        }

        .auth-brand-icon {
            background: rgba(255, 255, 255, 0.70);
            border: 1px solid rgba(255, 255, 255, 0.90);
            color: #0F5143;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04), inset 0 1px 1px rgba(255,255,255,0.9);
        }
        .auth-brand-text { color: #1E293B; }

        .auth-title { color: #1E293B; }
        .auth-subtitle { color: #475569; }
        .auth-label { color: #334155; }
        .auth-asterisk { color: #0F5143; }
        .auth-remember-text { color: #475569; }
        .auth-footer { color: #64748B; }
        .auth-footer-link { color: #0F5143; }

        .theme-toggle-pill {
            background: rgba(255, 255, 255, 0.50);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04), inset 0 1px 1px rgba(255,255,255,0.9);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .auth-tab-track {
            background: rgba(15, 23, 42, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.70);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.03);
        }

        .auth-tab-btn {
            color: #475569;
            background: transparent;
            border: 1px solid transparent;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .auth-tab-btn:hover { color: #0F172A; }
        .auth-tab-btn.active {
            background: rgba(255, 255, 255, 0.95);
            color: #0F5143;
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06), inset 0 1px 1px rgba(255, 255, 255, 0.95);
        }

        .kezak-input {
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.85);
            background: rgba(255, 255, 255, 0.50);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 
                inset 0 1px 2px rgba(0, 0, 0, 0.03), 
                0 1px 2px rgba(255, 255, 255, 0.65);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            color: #0F172A;
        }
        .kezak-input::placeholder { color: #94A3B8; }
        .kezak-input:hover {
            background: rgba(255, 255, 255, 0.70);
            border-color: rgba(255, 255, 255, 0.95);
        }
        .kezak-input:focus,
        .kezak-input:focus-within,
        .kezak-input:active,
        select.kezak-input:focus,
        textarea.kezak-input:focus {
            background-color: #E8F8F3 !important;
            border-color: #10B981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.22), inset 0 1px 1px rgba(0, 0, 0, 0.02) !important;
            outline: none !important;
        }

        .auth-btn-primary {
            background-color: #0F5143;
            color: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(15, 81, 67, 0.25);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .auth-btn-primary:hover {
            background-color: #146353;
            box-shadow: 0 6px 20px rgba(15, 81, 67, 0.35);
            transform: translateY(-1px);
        }
        .auth-btn-primary:active { transform: translateY(0); }

        .auth-divider-line { border-top: 1px solid rgba(255, 255, 255, 0.50); }
        .auth-divider-badge {
            background: rgba(255, 255, 255, 0.60);
            border: 1px solid rgba(255, 255, 255, 0.70);
            color: #475569;
        }

        .auth-guest-btn {
            background: rgba(255, 255, 255, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.85);
            color: #1E293B;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04), inset 0 1px 1px rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transition: all 0.2s ease;
        }
        .auth-guest-btn:hover {
            background: rgba(255, 255, 255, 0.75);
            border-color: #5EEAD4;
        }

        .kezak-grid-bg {
            background-color: rgba(8, 51, 43, 0.95);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                radial-gradient(ellipse at 85% 15%, rgba(16, 123, 103, 0.4) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 85%, rgba(10, 38, 33, 0.75) 0%, transparent 70%);
            background-size: 36px 36px, 36px 36px, 100% 100%, 100% 100%;
        }

        .showcase-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 16px 38px -12px rgba(0, 20, 20, 0.35), 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.9);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
        }
        .showcase-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 22px 48px -12px rgba(0, 20, 20, 0.45), 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .showcase-card-plan { position: absolute; top: 5%; left: 8%; width: 290px; z-index: 10; }
        .showcase-card-funds { position: absolute; top: 16%; right: 4%; width: 295px; z-index: 5; }
        .showcase-card-capital { position: absolute; top: 36%; left: 4%; width: 275px; z-index: 15; }

        @media (max-width: 1280px) {
            .showcase-card-plan { left: 4%; width: 260px; }
            .showcase-card-funds { right: 2%; width: 260px; }
            .showcase-card-capital { top: 38%; left: 2%; width: 250px; }
        }

        /* Showcase Floating Cards - Light Mode */
        .showcase-text-primary { color: #193B3A; }
        .showcase-text-secondary { color: #4B5563; }
        .showcase-text-muted { color: #6B7280; }
        .showcase-card-pill { background-color: #F3F4F6; color: #6B7280; }
        .showcase-highlight-teal { color: #0F766E; }
        .showcase-highlight-amber { color: #B45309; }
        .showcase-badge-teal { background-color: #F0FDFA; color: #115E59; border: 1px solid rgba(45, 212, 191, 0.4); }
        .showcase-badge-green { background-color: #ECFDF5; color: #047857; }
        .showcase-badge-amber { background-color: #FFFBEB; color: #B45309; }
        .showcase-btn-secondary { background-color: rgba(220, 239, 239, 0.6); color: #0D9488; }
        .showcase-btn-secondary:hover { background-color: #DCEFEF; }
        .showcase-btn-primary { background-color: #003F3D; color: #FFFFFF; }
        .showcase-btn-primary:hover { background-color: #002B2A; }
        .showcase-card-border { border-color: #F3F4F6; }
        .showcase-progress-track { background-color: #F3F4F6; }
        .showcase-chart-track { stroke: #F3F4F6; }

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
        html.dark .ambient-blob-focus-1 { background-color: rgba(16, 185, 129, 0.15) !important; }
        html.dark .ambient-blob-focus-2 { background-color: rgba(13, 148, 136, 0.12) !important; }
        html.dark .ambient-blob-focus-3 { background-color: rgba(5, 150, 105, 0.10) !important; }

        html.dark .auth-master-card {
            background: rgba(8, 20, 17, 0.65) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.85) !important;
        }

        html.dark .auth-glass-panel {
            background: linear-gradient(135deg, rgba(12, 26, 22, 0.82) 0%, rgba(6, 15, 13, 0.92) 100%) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            box-shadow: 
                inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.12),
                inset -1px 0 1px 0 rgba(255, 255, 255, 0.05) !important;
        }

        html.dark .auth-brand-icon {
            background: rgba(255, 255, 255, 0.10) !important;
            border: 1px solid rgba(255, 255, 255, 0.20) !important;
            color: #34D399 !important;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.2) !important;
        }
        html.dark .auth-brand-text { color: #FFFFFF !important; }

        html.dark .auth-title { color: #FFFFFF !important; }
        html.dark .auth-subtitle { color: #94A3B8 !important; }
        html.dark .auth-label { color: #E2E8F0 !important; }
        html.dark .auth-asterisk { color: #34D399 !important; }
        html.dark .auth-remember-text { color: #CBD5E1 !important; }
        html.dark .auth-footer { color: #94A3B8 !important; }
        html.dark .auth-footer-link { color: #34D399 !important; }

        html.dark .theme-toggle-pill {
            background: rgba(0, 0, 0, 0.40) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.15) !important;
        }

        html.dark .auth-tab-track {
            background: rgba(0, 0, 0, 0.35) !important;
            border: 1px solid rgba(255, 255, 255, 0.10) !important;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3) !important;
        }

        html.dark .auth-tab-btn {
            color: #94A3B8;
        }
        html.dark .auth-tab-btn:hover { color: #F8FAFC; }
        html.dark .auth-tab-btn.active {
            background: #0F5143 !important;
            color: #FFFFFF !important;
            border: 1px solid rgba(16, 185, 129, 0.4) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
        }

        html.dark .kezak-input {
            background: rgba(14, 32, 26, 0.60) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #FFFFFF !important;
            box-shadow: 
                inset 0 1px 2px rgba(0, 0, 0, 0.35), 
                0 1px 1px rgba(255, 255, 255, 0.04) !important;
        }
        html.dark .kezak-input::placeholder { color: #64748B !important; }
        html.dark .kezak-input:hover {
            background: rgba(18, 42, 34, 0.75) !important;
            border-color: rgba(255, 255, 255, 0.20) !important;
        }
        html.dark .kezak-input:focus,
        html.dark .kezak-input:focus-within,
        html.dark .kezak-input:active,
        html.dark select.kezak-input:focus,
        html.dark textarea.kezak-input:focus {
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

        html.dark .auth-btn-primary {
            background-color: #0F5143 !important;
            border: 1px solid rgba(16, 185, 129, 0.3) !important;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.25) !important;
        }
        html.dark .auth-btn-primary:hover {
            background-color: #146353 !important;
            box-shadow: 0 6px 22px rgba(16, 185, 129, 0.35) !important;
        }

        html.dark .auth-divider-line { border-top: 1px solid rgba(255, 255, 255, 0.12) !important; }
        html.dark .auth-divider-badge {
            background: rgba(0, 0, 0, 0.45) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #94A3B8 !important;
        }

        html.dark .auth-guest-btn {
            background: rgba(255, 255, 255, 0.06) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #F1F5F9 !important;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.1) !important;
        }
        html.dark .auth-guest-btn:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: rgba(16, 185, 129, 0.4) !important;
        }

        html.dark .kezak-grid-bg {
            background-color: rgba(3, 18, 15, 0.96) !important;
            border: 1px solid rgba(255, 255, 255, 0.10) !important;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                radial-gradient(ellipse at 85% 15%, rgba(16, 123, 103, 0.3) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 85%, rgba(2, 10, 8, 0.95) 0%, transparent 70%) !important;
        }

        html.dark .showcase-card {
            background: rgba(8, 26, 22, 0.88) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            box-shadow: 0 16px 38px -12px rgba(0, 0, 0, 0.7), 0 4px 12px rgba(0, 0, 0, 0.4) !important;
            color: #F8FAFC !important;
        }
        html.dark .showcase-card:hover {
            box-shadow: 0 22px 48px -12px rgba(0, 0, 0, 0.85), 0 6px 16px rgba(0, 0, 0, 0.5) !important;
        }

        /* Showcase Floating Cards - Dark Mode (Crisp White Text & High Contrast) */
        html.dark .showcase-text-primary,
        html.dark .showcase-card [class*="193B3A"],
        html.dark .showcase-card .text-gray-800,
        html.dark .showcase-card .text-slate-800,
        html.dark .showcase-card .text-gray-700 {
            color: #FFFFFF !important;
        }

        html.dark .showcase-text-secondary,
        html.dark .showcase-card .text-gray-600,
        html.dark .showcase-card .text-slate-600 {
            color: #F1F5F9 !important;
        }

        html.dark .showcase-text-muted,
        html.dark .showcase-card .text-gray-500,
        html.dark .showcase-card .text-gray-400,
        html.dark .showcase-card .text-slate-400 {
            color: #CBD5E1 !important;
        }

        html.dark .showcase-card-pill {
            background-color: rgba(255, 255, 255, 0.12) !important;
            color: #FFFFFF !important;
        }

        html.dark .showcase-highlight-teal {
            color: #5EEAD4 !important;
        }
        html.dark .showcase-highlight-amber {
            color: #FCD34D !important;
        }

        html.dark .showcase-badge-teal {
            background-color: rgba(16, 185, 129, 0.20) !important;
            color: #6EE7B7 !important;
            border-color: rgba(16, 185, 129, 0.40) !important;
        }

        html.dark .showcase-badge-green,
        html.dark .showcase-card .bg-teal-50,
        html.dark .showcase-card .bg-emerald-50,
        html.dark .showcase-card .bg-emerald-100 {
            background-color: rgba(16, 185, 129, 0.20) !important;
            color: #6EE7B7 !important;
            border-color: rgba(16, 185, 129, 0.35) !important;
        }

        html.dark .showcase-badge-amber,
        html.dark .showcase-card .bg-amber-50 {
            background-color: rgba(245, 158, 11, 0.20) !important;
            color: #FCD34D !important;
            border-color: rgba(245, 158, 11, 0.35) !important;
        }

        html.dark .showcase-btn-secondary,
        html.dark .showcase-card [class*="DCEFEF"] {
            background-color: rgba(13, 148, 136, 0.25) !important;
            color: #5EEAD4 !important;
        }
        html.dark .showcase-btn-secondary:hover {
            background-color: rgba(13, 148, 136, 0.40) !important;
        }

        html.dark .showcase-btn-primary,
        html.dark .showcase-card [class*="003F3D"] {
            background-color: #0F5143 !important;
            border: 1px solid rgba(16, 185, 129, 0.35) !important;
            color: #FFFFFF !important;
        }
        html.dark .showcase-btn-primary:hover {
            background-color: #146353 !important;
        }

        html.dark .showcase-card-border,
        html.dark .showcase-card .border-gray-100 {
            border-color: rgba(255, 255, 255, 0.10) !important;
        }

        html.dark .showcase-progress-track,
        html.dark .showcase-card .bg-gray-100 {
            background-color: rgba(255, 255, 255, 0.10) !important;
        }

        html.dark .showcase-chart-track {
            stroke: rgba(255, 255, 255, 0.12) !important;
        }

        /* Smooth Tab Animations */
        .auth-tab-panel {
            display: block;
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.25s ease-out, transform 0.25s ease-out;
        }
        .auth-tab-panel.hidden {
            display: none !important;
            opacity: 0;
            transform: translateY(6px);
        }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden font-sans antialiased flex flex-col justify-center p-2 sm:p-4 md:p-6 lg:p-8">
    <!-- ==========================================
         AMBIENT BLOBS (Kunci Efek Kaca Frosted)
         ========================================== -->
    <div class="ambient-blob-1 fixed top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full blur-[120px] pointer-events-none -z-0"></div>
    <div class="ambient-blob-2 fixed bottom-[-10%] right-[-5%] w-[600px] h-[600px] rounded-full blur-[140px] pointer-events-none -z-0"></div>
    <div class="ambient-blob-3 fixed top-[40%] right-[30%] w-[350px] h-[350px] rounded-full blur-[100px] pointer-events-none -z-0"></div>
    
    <!-- Blobs terfokus di balik panel kiri untuk refraksi kaca beku yang kaya -->
    <div class="ambient-blob-focus-1 fixed top-[18%] left-[6%] w-[480px] h-[480px] rounded-full blur-[105px] pointer-events-none -z-0"></div>
    <div class="ambient-blob-focus-2 fixed bottom-[12%] left-[18%] w-[420px] h-[420px] rounded-full blur-[115px] pointer-events-none -z-0"></div>
    <div class="ambient-blob-focus-3 fixed top-[15%] left-[28%] w-[320px] h-[320px] rounded-full blur-[85px] pointer-events-none -z-0"></div>

    @yield('content')

    <!-- Theme Toggle Helper Script -->
    <script>
        function applyTheme(isDark) {
            const html = document.documentElement;
            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');
            const toggleBtn = document.getElementById('theme-toggle');

            if (isDark) {
                html.classList.add('dark');
                try { localStorage.setItem('facilityhub_theme', 'dark'); } catch(e) {}
                if (toggleBtn) {
                    toggleBtn.setAttribute('aria-label', 'Beralih ke Mode Terang');
                    toggleBtn.setAttribute('title', 'Beralih ke Mode Terang');
                }
                if (sunIcon && moonIcon) {
                    sunIcon.className = 'w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-200 transition-all duration-200';
                    moonIcon.className = 'w-7 h-7 rounded-lg flex items-center justify-center bg-emerald-600 text-white shadow-xs transition-all duration-200';
                }
            } else {
                html.classList.remove('dark');
                try { localStorage.setItem('facilityhub_theme', 'light'); } catch(e) {}
                if (toggleBtn) {
                    toggleBtn.setAttribute('aria-label', 'Beralih ke Mode Gelap');
                    toggleBtn.setAttribute('title', 'Beralih ke Mode Gelap');
                }
                if (sunIcon && moonIcon) {
                    sunIcon.className = 'w-7 h-7 rounded-lg flex items-center justify-center bg-white shadow-xs text-amber-500 transition-all duration-200';
                    moonIcon.className = 'w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 transition-all duration-200';
                }
            }
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            applyTheme(!isDark);
        }

        // Initialize toggle button state on DOM load
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            applyTheme(isDark);
        });
    </script>
</body>
</html>
