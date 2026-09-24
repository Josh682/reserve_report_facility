<!DOCTYPE html>
<html lang="id" class="h-full {{ request()->cookie('theme') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Fasilitas Kampus') — Portal Fasilitas</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.theme-script')
</head>

<body class="h-full font-sans antialiased bg-slate-50 dark:bg-[#061014] text-slate-800 dark:text-slate-100 transition-colors duration-150">
    <div class="min-h-screen flex flex-col lg:flex-row">

        {{-- Mobile Backdrop --}}
        <div id="app-sidebar-backdrop"
             class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-xs hidden lg:hidden transition-opacity"
             onclick="toggleAppSidebar()"></div>

        {{-- SIDEBAR --}}
        <aside id="app-sidebar"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-[#0b171c] border-r border-slate-200 dark:border-teal-950/70 flex flex-col transition-transform duration-200 -translate-x-full lg:translate-x-0 lg:static lg:z-auto">

            {{-- Brand Header --}}
            <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200 dark:border-teal-950/70 shrink-0">
                <a href="{{ route('facilities') }}" class="flex items-center gap-3 text-decoration-none group">
                    <div class="w-9 h-9 rounded-lg bg-teal-700 dark:bg-teal-600 text-white flex items-center justify-center font-black text-sm tracking-wider shadow-xs group-hover:bg-teal-800 dark:group-hover:bg-teal-500 transition-colors">
                        RF
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                            FacilityHub
                        </div>
                        <div class="text-[11px] font-medium text-teal-700 dark:text-teal-400">
                            Fasilitas Kampus
                        </div>
                    </div>
                </a>

                <button type="button"
                        class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800"
                        onclick="toggleAppSidebar()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Sidebar Navigation Links --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') || request()->routeIs('pengguna*') || request()->routeIs('admin*') || request()->routeIs('petugas*') ? 'bg-teal-50 dark:bg-teal-950/50 text-teal-800 dark:text-teal-300 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                {{-- Katalog Fasilitas --}}
                <a href="{{ route('facilities') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('facilities*') ? 'bg-teal-50 dark:bg-teal-950/50 text-teal-800 dark:text-teal-300 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Katalog Fasilitas</span>
                </a>

                {{-- Reservasi Saya --}}
                <a href="{{ route('reservation') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('reservation*') ? 'bg-teal-50 dark:bg-teal-950/50 text-teal-800 dark:text-teal-300 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Reservasi Saya</span>
                </a>

                {{-- Laporan Kerusakan --}}
                <a href="{{ route('report') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('report*') ? 'bg-teal-50 dark:bg-teal-950/50 text-teal-800 dark:text-teal-300 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Laporan Kerusakan</span>
                </a>
            </nav>

            {{-- Sidebar Footer --}}
            <div class="p-3 border-t border-slate-200 dark:border-teal-950/70 bg-slate-50/50 dark:bg-[#071317]/50 space-y-2">
                <div class="flex items-center justify-between px-2">
                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Tema Tampilan</span>
                    @include('partials.theme-toggle')
                </div>

                @auth
                    <div class="pt-2 border-t border-slate-200/60 dark:border-slate-800 flex items-center justify-between gap-2 px-1">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-teal-700 dark:text-teal-400 capitalize truncate">
                                {{ auth()->user()->role }} {{ auth()->user()->tipe_pengguna ? '('.auth()->user()->tipe_pengguna.')' : '' }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                    title="Keluar (Logout)"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="pt-2 border-t border-slate-200/60 dark:border-slate-800 flex flex-col gap-1.5">
                        <a href="{{ route('login') }}" class="w-full text-center py-1.5 px-3 rounded-lg text-xs font-semibold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/50 hover:bg-teal-100 transition-colors">
                            Masuk Akun
                        </a>
                    </div>
                @endauth
            </div>
        </aside>

        {{-- MAIN CONTENT WRAPPER --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            {{-- TOPBAR --}}
            <header class="h-16 bg-white dark:bg-[#0b171c] border-b border-slate-200 dark:border-teal-950/70 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0">
                <div class="flex items-center gap-3">
                    <button type="button"
                            class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
                            onclick="toggleAppSidebar()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">
                        @yield('page-title', 'Katalog Fasilitas Kampus')
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    @include('partials.theme-toggle')

                    @auth
                        <div class="flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-800">
                            <div class="w-8 h-8 rounded-full bg-teal-700 text-white flex items-center justify-center font-bold text-xs shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="hidden sm:block text-right">
                                <span class="block text-xs font-bold text-slate-900 dark:text-white leading-tight truncate max-w-[130px]">{{ auth()->user()->name }}</span>
                                <span class="block text-[10px] text-teal-700 dark:text-teal-400 capitalize">{{ auth()->user()->role }}</span>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-teal-700 hover:bg-teal-800 dark:bg-teal-600 dark:hover:bg-teal-500 transition-colors shadow-xs">
                            <span>Masuk</span>
                        </a>
                    @endauth
                </div>
            </header>

            {{-- PAGE CONTENT --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50 dark:bg-[#061014] transition-colors duration-150">
                <div class="max-w-7xl mx-auto space-y-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleAppSidebar() {
            const sidebar = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('app-sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
</body>
</html>