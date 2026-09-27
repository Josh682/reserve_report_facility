<!DOCTYPE html>
<html lang="id" class="h-full {{ request()->cookie('theme') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Mahasiswa') — {{ config('app.name', 'Portal Fasilitas Kampus') }}</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.theme-script')
</head>
<body class="h-full font-sans antialiased text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-[#080d11] transition-colors duration-150">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Mobile Sidebar Backdrop -->
        <div id="pengguna-sidebar-backdrop"
             class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-xs hidden lg:hidden"
             onclick="togglePenggunaSidebar()"></div>

        <!-- Sidebar Navigation (Swiss High-Density Minimal) -->
        <aside id="pengguna-sidebar"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-[#0c1419] border-r border-slate-200 dark:border-teal-950/80 flex flex-col transition-transform duration-200 -translate-x-full lg:translate-x-0 lg:static lg:z-auto">

            <!-- Brand Header -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200 dark:border-teal-950/80 bg-white dark:bg-[#0c1419]">
                <a href="{{ route('pengguna.dashboard') }}" class="flex items-center gap-3 group text-decoration-none">
                    <div class="w-8 h-8 rounded-xs bg-teal-700 dark:bg-teal-600 text-white flex items-center justify-center font-mono font-bold text-xs tracking-wider group-hover:bg-teal-800 dark:group-hover:bg-teal-500 transition-colors">
                        RF
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                            FacilityHub
                        </div>
                        <div class="text-[11px] font-mono uppercase tracking-wider text-teal-700 dark:text-teal-400">
                            Portal Pengguna
                        </div>
                    </div>
                </a>
                <button type="button" class="lg:hidden p-1.5 rounded-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800" onclick="togglePenggunaSidebar()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('pengguna.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xs text-xs font-semibold uppercase tracking-wider transition-colors {{ request()->routeIs('pengguna.dashboard') ? 'bg-teal-50 dark:bg-teal-950/50 text-teal-800 dark:text-teal-300 border-l-2 border-teal-600' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard Saya</span>
                </a>

                <!-- Katalog Fasilitas -->
                <a href="{{ route('facilities') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xs text-xs font-semibold uppercase tracking-wider transition-colors {{ request()->routeIs('facilities*') ? 'bg-teal-50 dark:bg-teal-950/50 text-teal-800 dark:text-teal-300 border-l-2 border-teal-600' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Katalog Fasilitas</span>
                </a>

                <!-- Reservasi Saya -->
                <a href="{{ route('reservation') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xs text-xs font-semibold uppercase tracking-wider transition-colors {{ request()->routeIs('reservation*') ? 'bg-teal-50 dark:bg-teal-950/50 text-teal-800 dark:text-teal-300 border-l-2 border-teal-600' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Reservasi Saya</span>
                </a>

                <!-- Laporan Kerusakan -->
                <a href="{{ route('report') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xs text-xs font-semibold uppercase tracking-wider transition-colors {{ request()->routeIs('report*') ? 'bg-teal-50 dark:bg-teal-950/50 text-teal-800 dark:text-teal-300 border-l-2 border-teal-600' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Laporan Kerusakan</span>
                </a>
            </nav>

            <!-- Bottom User & Theme section -->
            <div class="p-3 border-t border-slate-200 dark:border-teal-950/80 bg-slate-50/50 dark:bg-[#080d11]/50 space-y-2">
                <div class="flex items-center justify-between px-2">
                    <span class="text-[11px] font-mono uppercase tracking-wider text-slate-500 dark:text-slate-400">Tema</span>
                    @include('partials.theme-toggle')
                </div>

                <div class="pt-2 border-t border-slate-200/60 dark:border-slate-800 flex items-center justify-between gap-2 px-1">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-mono text-teal-700 dark:text-teal-400 uppercase tracking-wider truncate">
                            {{ auth()->user()->role }} ({{ auth()->user()->tipe_pengguna ?? 'mahasiswa' }})
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                title="Keluar (Logout)"
                                class="p-1.5 rounded-xs text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Navigation Bar (Raycast Subtle Blur) -->
            <header class="h-16 bg-white/95 dark:bg-[#0c1419]/95 backdrop-blur-md border-b border-slate-200 dark:border-teal-950/80 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0">
                <div class="flex items-center gap-3">
                    <button type="button" class="lg:hidden p-2 rounded-xs text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800" onclick="togglePenggunaSidebar()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white tracking-tight uppercase">
                        @yield('title', 'Dashboard Pengguna')
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    @include('partials.theme-toggle')

                    <div class="flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-800">
                        <div class="w-8 h-8 rounded-xs bg-teal-700 text-white flex items-center justify-center font-mono font-bold text-xs shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="hidden sm:block text-right">
                            <div class="text-xs font-bold text-slate-900 dark:text-white leading-tight truncate max-w-[130px]">
                                {{ auth()->user()->name ?? 'Pengguna' }}
                            </div>
                            <div class="text-[10px] font-mono text-teal-700 dark:text-teal-400 uppercase tracking-wider">
                                {{ auth()->user()->tipe_pengguna ?? 'Mahasiswa' }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50 dark:bg-[#080d11] transition-colors duration-150">
                <div class="max-w-7xl mx-auto space-y-6">
                    @if (session('status'))
                        <div class="rounded-xs bg-teal-50 dark:bg-teal-950/40 p-4 border border-teal-200 dark:border-teal-800 shadow-none">
                            <div class="flex items-start gap-3">
                                <div class="shrink-0 text-teal-600 dark:text-teal-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-teal-900 dark:text-teal-200">
                                        {{ session('status') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        function togglePenggunaSidebar() {
            const sidebar = document.getElementById('pengguna-sidebar');
            const backdrop = document.getElementById('pengguna-sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
</body>
</html>
