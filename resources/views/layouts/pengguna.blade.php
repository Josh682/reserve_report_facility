<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Mahasiswa') — {{ config('app.name', 'FacilityHub') }}</title>

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
    @include('partials.theme-script')
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
    <div id="pengguna-sidebar-backdrop" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs hidden lg:hidden" onclick="togglePenggunaSidebar()"></div>

    <!-- ==========================================
         MASTER GLASS CONTAINER WINDOW
         ========================================== -->
    <div class="w-full max-w-[1420px] mx-auto bg-white/45 dark:bg-[#081411]/80 backdrop-blur-2xl border border-white/50 dark:border-white/10 shadow-[0_25px_60px_rgba(15,81,67,0.12)] dark:shadow-[0_25px_60px_rgba(0,0,0,0.85)] rounded-[32px] p-5 sm:p-7 lg:p-8 relative z-10 flex flex-col lg:flex-row gap-7 my-2 sm:my-6 min-h-[880px]">

        <!-- ==========================================
             LEFT SIDEBAR (FROSTED SIDEBAR)
             ========================================== -->
        <aside id="pengguna-sidebar-panel" class="w-full lg:w-64 shrink-0 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-white/40 dark:border-white/10 pb-6 lg:pb-0 lg:pr-6">
            <div class="space-y-6">
                <!-- Brand Header -->
                <div class="flex items-center justify-between pb-4 border-b border-white/30 dark:border-white/10">
                    <a href="{{ route('pengguna.dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-2xl bg-white/70 dark:bg-white/10 backdrop-blur-md border border-white/80 dark:border-white/15 shadow-xs flex items-center justify-center text-[#0F5143] dark:text-[#34D399] group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6 text-[#0F5143] dark:text-[#34D399]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <span class="font-extrabold text-xl tracking-tight text-slate-800 dark:text-white block leading-tight">FacilityHub</span>
                            <span class="text-[11px] font-semibold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider block">Portal Pengguna</span>
                        </div>
                    </a>

                    <!-- Mobile Menu Hamburger -->
                    <button type="button" class="lg:hidden p-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-white/40 dark:hover:bg-white/10" onclick="togglePenggunaSidebar()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Menu -->
                <nav id="pengguna-nav-links" class="space-y-1.5 hidden lg:block">
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
                <div class="pt-5 border-t border-white/30 dark:border-white/10 space-y-2 hidden lg:block">
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
            </div>

            <!-- Bottom: User & Logout -->
            <div class="pt-5 border-t border-white/30 dark:border-white/10 hidden lg:block space-y-3">
                <div class="flex items-center gap-3 px-2">
                    <div class="w-9 h-9 rounded-2xl bg-[#0F5143] text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                        <p class="text-[10px] font-semibold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider truncate">
                            {{ ucfirst(auth()->user()->tipe_pengguna ?? 'Mahasiswa') }}
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
                            @yield('title', 'Portal Pengguna')
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
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="text-left leading-tight hidden sm:block">
                            <span class="block text-xs font-bold text-slate-800 dark:text-white">{{ auth()->user()->name ?? 'Pengguna' }}</span>
                            <span class="block text-[10px] font-semibold text-emerald-800 dark:text-emerald-400">{{ ucfirst(auth()->user()->tipe_pengguna ?? 'Mahasiswa') }}</span>
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
        function togglePenggunaSidebar() {
            const nav = document.getElementById('pengguna-nav-links');
            const backdrop = document.getElementById('pengguna-sidebar-backdrop');
            if (nav) {
                nav.classList.toggle('hidden');
            }
            if (backdrop) {
                backdrop.classList.toggle('hidden');
            }
        }
    </script>
</body>
</html>
