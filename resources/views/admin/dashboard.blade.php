<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Admin — FacilityHub</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gradient-to-br from-[#0F3830] via-[#1E5247] to-[#0A2621] min-h-screen relative overflow-x-hidden font-sans antialiased text-slate-800 p-3 sm:p-5 lg:p-7 flex flex-col justify-center" style="font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;">

    <!-- ==========================================
         AMBIENT BLOBS (Kunci Efek Kaca Frosted)
         ========================================== -->
    <div class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full bg-emerald-400/20 blur-[120px] pointer-events-none -z-0"></div>
    <div class="fixed bottom-[-10%] right-[-5%] w-[600px] h-[600px] rounded-full bg-teal-300/25 blur-[140px] pointer-events-none -z-0"></div>
    <div class="fixed top-[40%] right-[30%] w-[350px] h-[350px] rounded-full bg-amber-200/10 blur-[100px] pointer-events-none -z-0"></div>

    <!-- Backdrop untuk Modal / Slide-over Mobile -->
    <div id="general-backdrop" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs hidden" onclick="closeAllModals()"></div>

    <!-- ==========================================
         MASTER GLASS CONTAINER WINDOW
         ========================================== -->
    <div class="w-full max-w-[1420px] mx-auto bg-white/45 backdrop-blur-2xl border border-white/50 shadow-[0_25px_60px_rgba(0,0,0,0.22)] rounded-[32px] p-5 sm:p-7 lg:p-8 relative z-10 flex flex-col lg:flex-row gap-7 my-2 sm:my-6 min-h-[880px]">

        <!-- ==========================================
             A. LEFT SIDEBAR (FROSTED SIDEBAR)
             ========================================== -->
        <aside id="sidebar-panel" class="w-full lg:w-64 shrink-0 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-white/40 pb-6 lg:pb-0 lg:pr-6">
            <div class="space-y-6">
                <!-- Brand Header -->
                <div class="flex items-center justify-between pb-4 border-b border-white/30">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-2xl bg-white/70 backdrop-blur-md border border-white/80 shadow-xs flex items-center justify-center text-[#0F5143] group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6 text-[#0F5143]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span class="font-extrabold text-xl tracking-tight text-slate-800">FacilityHub</span>
                    </a>

                    <!-- Mobile Menu Hamburger -->
                    <button type="button" class="lg:hidden p-2 rounded-xl text-slate-700 hover:bg-white/40" onclick="toggleSidebar()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Menu (Hanya Dashboard, Pengelolaan Fasilitas, dan Pengelolaan Akun) -->
                <nav id="nav-links" class="space-y-1.5 hidden lg:block">
                    <!-- 1. Dashboard (Active) -->
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl bg-[#0F5143] text-white shadow-md font-semibold text-sm transition-all group">
                        <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2"/>
                            <rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2"/>
                            <rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2"/>
                            <rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- 2. Pengelolaan Fasilitas -->
                    <a href="{{ route('admin.facilities.index') }}"
                       class="flex items-center justify-between px-4 py-3 rounded-2xl text-slate-700 hover:bg-white/40 transition-all font-medium text-sm group">
                        <div class="flex items-center gap-3.5">
                            <svg class="w-5 h-5 text-slate-600 group-hover:text-slate-900 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>Pengelolaan Fasilitas</span>
                        </div>
                        @if (($stats['dalam_perbaikan'] ?? 0) > 0)
                            <span class="w-5 h-5 rounded-full bg-rose-500 text-white text-[11px] font-bold flex items-center justify-center shrink-0 shadow-xs" title="{{ $stats['dalam_perbaikan'] }} fasilitas rusak">
                                {{ $stats['dalam_perbaikan'] }}
                            </span>
                        @endif
                    </a>

                    <!-- 3. Pengelolaan Akun (Verifikasi & Manajemen Pengguna) -->
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center justify-between px-4 py-3 rounded-2xl text-slate-700 hover:bg-white/40 transition-all font-medium text-sm group"
                       title="Verifikasi Akun ({{ $stats['pending_users'] ?? 0 }} Menunggu Verifikasi)">
                        <div class="flex items-center gap-3.5">
                            <svg class="w-5 h-5 text-slate-600 group-hover:text-slate-900 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Pengelolaan Akun</span>
                        </div>
                        @if (($stats['pending_users'] ?? 0) > 0)
                            <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-800 border border-amber-300 text-[11px] font-bold flex items-center justify-center shrink-0 shadow-xs" title="{{ $stats['pending_users'] }} akun pending">
                                {{ $stats['pending_users'] }}
                            </span>
                        @endif
                    </a>
                </nav>

                <!-- Aksi Cepat Admin (Shortcut Langsung ke Form Tambah Akun & Tambah Fasilitas) -->
                <div class="pt-5 border-t border-white/30 space-y-2 hidden lg:block">
                    <div class="flex items-center justify-between px-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <span>Aksi Cepat Admin</span>
                    </div>

                    <!-- Shortcut 1: Tambah Akun Langsung -->
                    <a href="{{ route('admin.users.create') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/60 hover:bg-white/85 border border-white/70 text-slate-700 text-xs font-semibold transition-all shadow-2xs group"
                       title="Tambahkan Akun Langsung">
                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-lg bg-emerald-100/90 text-emerald-800 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <span class="group-hover:text-[#0F5143] transition-colors">Tambah Akun Langsung</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <!-- Shortcut 2: Tambah Fasilitas Langsung -->
                    <a href="{{ route('admin.facilities.create') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/60 hover:bg-white/85 border border-white/70 text-slate-700 text-xs font-semibold transition-all shadow-2xs group"
                       title="Tambahkan Fasilitas Langsung">
                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-lg bg-teal-100/90 text-teal-800 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="group-hover:text-[#0F5143] transition-colors">Tambah Fasilitas Langsung</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Bottom: Logout Form Terproteksi -->
            <div class="pt-5 border-t border-white/30 hidden lg:block">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-semibold text-rose-700 hover:bg-rose-50/70 border border-transparent hover:border-rose-200 transition-all cursor-pointer">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ==========================================
             MAIN CONTENT AREA (TOPBAR + 12-COL GRID)
             ========================================== -->
        <main class="flex-1 flex flex-col min-w-0">

            <!-- ==========================================
                 B. TOP NAVBAR (Fungsional & Interaktif)
                 ========================================== -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
                <!-- Sapaan Dinamis & Judul Dashboard -->
                <div>
                    <p class="text-xs sm:text-sm font-medium text-slate-600 flex items-center gap-1.5">
                        <span>Welcome back, {{ auth()->user()->name ?? 'Admin' }}</span>
                        <span>👋</span>
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                        Dashboard Admin
                    </h1>
                </div>

                <!-- Kontrol Kanan: Pencarian Fasilitas, Notifikasi Aktual, Profil Admin -->
                <div class="flex items-center gap-3">
                    <!-- Form Pencarian Langsung ke Katalog Fasilitas -->
                    <form action="{{ route('admin.facilities.index') }}" method="GET" class="relative flex items-center">
                        <input type="text" name="search" placeholder="Cari fasilitas, lokasi..."
                               class="w-36 sm:w-52 lg:w-60 pl-9 pr-3 py-2 rounded-2xl bg-white/70 backdrop-blur-md border border-white/80 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0F5143] focus:bg-white transition-all shadow-xs">
                        <button type="submit" class="absolute left-2.5 text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>

                    <!-- Tombol Notifikasi dengan Popover Interaktif -->
                    <div class="relative">
                        <button type="button" onclick="toggleNotifications()" aria-label="Notifications"
                                class="w-10 h-10 rounded-2xl bg-white/70 backdrop-blur-md border border-white/80 shadow-xs flex items-center justify-center text-slate-600 hover:bg-white/95 relative transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        @php
                            $totalNotif = ($stats['pending_users'] ?? 0) + ($stats['dalam_perbaikan'] ?? 0);
                        @endphp
                        @if ($totalNotif > 0)
                            <span class="w-2 h-2 rounded-full bg-emerald-500 absolute top-2.5 right-2.5 ring-2 ring-white"></span>
                        @endif
                    </button>

                    <!-- Notification Popover Dropdown -->
                    <div id="notification-popover" class="hidden absolute right-0 mt-3 w-80 sm:w-96 bg-white/90 backdrop-blur-xl border border-white/80 shadow-2xl rounded-3xl p-5 z-50">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200/60">
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-sm text-slate-800">Notifikasi Admin</h4>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                                    {{ $totalNotif }} Agenda
                                </span>
                            </div>
                            <button type="button" onclick="toggleNotifications()" class="text-xs text-slate-400 hover:text-slate-600">✕</button>
                        </div>

                        <div class="divide-y divide-slate-100 mt-2 max-h-72 overflow-y-auto space-y-1">
                            <!-- Notifikasi 1: Akun Pending -->
                            @if (($stats['pending_users'] ?? 0) > 0)
                                <div class="py-2.5 flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800">{{ $stats['pending_users'] }} Akun Menunggu Verifikasi</p>
                                        <p class="text-[11px] text-slate-500">Pendaftar baru membutuhkan persetujuan.</p>
                                        <a href="{{ route('admin.users.index', ['tab' => 'pending']) }}" class="text-[11px] font-bold text-[#0F5143] hover:underline mt-1 inline-block">
                                            Verifikasi Akun Sekarang →
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <!-- Notifikasi 2: Fasilitas dalam Perbaikan -->
                            @if (($stats['dalam_perbaikan'] ?? 0) > 0)
                                <div class="py-2.5 flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-800 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800">{{ $stats['dalam_perbaikan'] }} Fasilitas Dalam Perbaikan</p>
                                        <p class="text-[11px] text-slate-500">Unit sarana sedang dalam masa pemeliharaan.</p>
                                        <a href="{{ route('admin.facilities.index', ['status' => 'dalam_perbaikan']) }}" class="text-[11px] font-bold text-[#0F5143] hover:underline mt-1 inline-block">
                                            Cek Status Fasilitas →
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if ($totalNotif === 0)
                                <div class="py-6 text-center text-slate-500 text-xs">
                                    Semua akun terverifikasi dan fasilitas prima.
                                </div>
                            @endif
                        </div>
                        </div>
                    </div>

                    <!-- Avatar Admin User Pill (Dengan Dropdown Profil) -->
                    <div class="relative">
                        <button type="button" onclick="toggleProfileDropdown()"
                                class="bg-white/70 backdrop-blur-md border border-white/80 rounded-2xl px-3 py-1.5 shadow-xs flex items-center gap-2.5 hover:bg-white/90 transition-all cursor-pointer">
                            <div class="w-8 h-8 rounded-full overflow-hidden bg-[#0F5143] text-white flex items-center justify-center text-xs font-bold border border-white/80 shrink-0">
                                <span>{{ strtoupper(substr(auth()->user()->name ?? 'AU', 0, 2)) }}</span>
                            </div>
                            <div class="text-left hidden sm:block">
                                <div class="text-xs font-bold text-slate-800 leading-tight">
                                    {{ auth()->user()->name ?? 'Admin User' }}
                                </div>
                                <span class="text-[10px] text-slate-500 uppercase font-semibold">Administrator</span>
                            </div>
                        </button>

                        <!-- Profil Dropdown -->
                        <div id="profile-dropdown" class="hidden absolute right-0 mt-3 w-64 bg-white/90 backdrop-blur-xl border border-white/80 shadow-2xl rounded-3xl p-4 z-50">
                            <div class="pb-3 border-b border-slate-200/60">
                                <p class="text-xs font-bold text-slate-800">{{ auth()->user()->name ?? 'Administrator' }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email ?? 'admin@kampus.test' }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800 mt-2">
                                    Super Admin
                                </span>
                            </div>
                            <div class="py-2 space-y-1 text-xs">
                                <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-xl text-slate-700 hover:bg-white/80 transition-colors">
                                    Kelola Pengguna
                                </a>
                                <a href="{{ route('admin.facilities.index') }}" class="block px-3 py-2 rounded-xl text-slate-700 hover:bg-white/80 transition-colors">
                                    Pengelolaan Fasilitas
                                </a>
                            </div>
                            <div class="pt-2 border-t border-slate-200/60">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-rose-700 hover:bg-rose-50 transition-colors cursor-pointer">
                                        Keluar Sistem
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 C. MAIN GRID CONTENT (12 KOLOM - DATA AKTUAL)
                 ========================================== -->
            <div class="grid grid-cols-12 gap-6 relative z-10 mt-6">

                <!-- ----------------------------------------------------
                     CARD 1: Upcoming Major Event/Reservation (Col Span 7)
                     ---------------------------------------------------- -->
                <div class="col-span-12 xl:col-span-7 bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 transition-all relative z-10 flex flex-col justify-between">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm sm:text-base font-bold text-slate-800">Upcoming Major Event/Reservation</h3>
                        <a href="{{ route('reservation') }}" class="text-xs font-semibold text-teal-800 hover:text-teal-900 transition-colors">
                            Lihat Kalender
                        </a>
                    </div>

                    <!-- Inner Card with Facility Icon & Data Aktual -->
                    <div class="bg-white/75 backdrop-blur-md border border-white/85 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-5 shadow-xs">
                        @php
                            $upcomingFacility = $upcomingReservation->facility ?? null;
                            $upcomingTipe = $upcomingFacility->tipe ?? 'aula';
                        @endphp

                        <!-- Event / Facility Icon Square (Dinamis Berdasarkan Tipe Fasilitas) -->
                        <div class="w-12 h-12 sm:w-13 sm:h-13 rounded-2xl bg-[#0F5143] text-white flex items-center justify-center shrink-0 shadow-xs" title="Tipe Fasilitas: {{ ucfirst(str_replace('_', ' ', $upcomingTipe)) }}">
                            <x-facility-icon :tipe="$upcomingTipe" class="w-6 h-6 sm:w-7 sm:h-7" />
                        </div>

                        <!-- Details (Dinamis dari Database atau Fallback) -->
                        <div class="min-w-0 flex-1">
                            @if (isset($upcomingReservation) && $upcomingReservation)
                                <div class="flex items-center gap-2">
                                    <h4 class="text-base sm:text-lg font-bold text-slate-900 leading-snug truncate">
                                        {{ $upcomingReservation->tujuan_penggunaan ?: 'Penggunaan Fasilitas Kampus' }}
                                    </h4>
                                    @if ($upcomingReservation->status === 'approved')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Disetujui</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Menunggu</span>
                                    @endif
                                </div>
                                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5">
                                    {{ $upcomingReservation->facility->nama ?? 'Aula Utama' }}
                                    @if (isset($upcomingReservation->facility->lokasi))
                                        • <span class="text-slate-400">{{ $upcomingReservation->facility->lokasi }}</span>
                                    @endif
                                </p>
                                <p class="text-xs font-semibold text-slate-600 mt-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>
                                        {{ \Carbon\Carbon::parse($upcomingReservation->tanggal)->translatedFormat('d M Y') }},
                                        {{ substr((string)$upcomingReservation->start_time, 0, 5) }} - {{ substr((string)$upcomingReservation->end_time, 0, 5) }} WIB
                                    </span>
                                </p>
                            @else
                                <h4 class="text-base sm:text-lg font-bold text-slate-900 leading-snug">
                                    Graduation Ceremony Prep
                                </h4>
                                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5">
                                    Aula Utama
                                </p>
                                <p class="text-xs font-semibold text-slate-600 mt-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>11 Nov 2026, 09:00</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ----------------------------------------------------
                     CARD 2: Weekly Reservation Statistic (Col Span 5 - Data Aktual)
                     ---------------------------------------------------- -->
                @php
                    $resTotal = $stats['total_reservations'] ?? 0;
                    $resApproved = $stats['approved_reservations'] ?? 0;
                    $resPending = $stats['pending_reservations'] ?? 0;
                    $resRejected = $stats['rejected_reservations'] ?? 0;

                    // Hitung rasio bar progress sesuai angka statistik aktual (0% jika tidak ada data)
                    $resDenominator = max($resTotal, ($resApproved + $resPending + $resRejected));
                    $pctApproved = $resDenominator > 0 ? min(100, (int) round(($resApproved / $resDenominator) * 100)) : 0;
                    $pctPending = $resDenominator > 0 ? min(100, (int) round(($resPending / $resDenominator) * 100)) : 0;
                    $pctRejected = $resDenominator > 0 ? min(100, (int) round(($resRejected / $resDenominator) * 100)) : 0;
                @endphp
                <div class="col-span-12 xl:col-span-5 bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 transition-all relative z-10 flex flex-col justify-between">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm sm:text-base font-bold text-slate-800">Weekly Reservation Statistic</h3>
                        <a href="{{ route('reservation') }}" class="text-xs font-semibold text-teal-800 hover:text-teal-900 transition-colors">
                            Lihat Semua Statistik
                        </a>
                    </div>

                    <!-- Progress Bars (Berdasarkan Angka Database Nyata) -->
                    <div class="space-y-2.5 my-auto">
                        <!-- Disetujui (Deep Teal) -->
                        <div class="flex items-center gap-3">
                            <span class="w-20 text-xs font-medium text-slate-600">Disetujui</span>
                            <div class="flex-1 h-3 rounded-full bg-slate-200/70 overflow-hidden p-0.5">
                                <div class="h-full rounded-full bg-[#0F5143] transition-all duration-500 {{ $pctApproved > 0 ? '' : 'opacity-0' }}" style="width: {{ $pctApproved }}%"></div>
                            </div>
                            <span class="w-8 text-right text-xs font-bold text-slate-800">{{ $resApproved }}</span>
                        </div>

                        <!-- Menunggu (Amber) -->
                        <div class="flex items-center gap-3">
                            <span class="w-20 text-xs font-medium text-slate-600">Menunggu</span>
                            <div class="flex-1 h-3 rounded-full bg-slate-200/70 overflow-hidden p-0.5">
                                <div class="h-full rounded-full bg-[#F59E0B] transition-all duration-500 {{ $pctPending > 0 ? '' : 'opacity-0' }}" style="width: {{ $pctPending }}%"></div>
                            </div>
                            <span class="w-8 text-right text-xs font-bold text-slate-800">{{ $resPending }}</span>
                        </div>

                        <!-- Ditolak (Rose) -->
                        <div class="flex items-center gap-3">
                            <span class="w-20 text-xs font-medium text-slate-600">Ditolak</span>
                            <div class="flex-1 h-3 rounded-full bg-slate-200/70 overflow-hidden p-0.5">
                                <div class="h-full rounded-full bg-[#C2410C] transition-all duration-500 {{ $pctRejected > 0 ? '' : 'opacity-0' }}" style="width: {{ $pctRejected }}%"></div>
                            </div>
                            <span class="w-8 text-right text-xs font-bold text-slate-800">{{ $resRejected }}</span>
                        </div>
                    </div>

                    <!-- Summary Counts Footer Aktual -->
                    <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs text-slate-600 font-medium">
                        <span>Total: <strong class="text-slate-900">{{ $resTotal }}</strong></span>
                        <span>Disetujui: <strong class="text-[#0F5143]">{{ $resApproved }}</strong></span>
                        <span>Menunggu: <strong class="text-[#B45309]">{{ $resPending }}</strong></span>
                        <span>Ditolak: <strong class="text-[#B91C1C]">{{ $resRejected }}</strong></span>
                    </div>
                </div>

                <!-- ----------------------------------------------------
                     CARD 3: Status Ketersediaan Fasilitas Utama (Col Span 7 - Data Aktual)
                     ---------------------------------------------------- -->
                <div class="col-span-12 xl:col-span-7 bg-white/65 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl p-6 transition-all relative z-10">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm sm:text-base font-bold text-slate-800">Status Ketersediaan Fasilitas Utama</h3>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold text-teal-800 bg-teal-50/90 border border-teal-200/80">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-600 animate-pulse"></span>
                            Real-time
                        </span>
                    </div>

                    <!-- Mini Table / Facility List dari Database -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-200/60 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                    <th class="pb-2.5 pl-1 w-10">#</th>
                                    <th class="pb-2.5">Team</th>
                                    <th class="pb-2.5 text-center">Status</th>
                                    <th class="pb-2.5 text-right pr-2">Orang</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/70 text-xs sm:text-sm">
                                @if (isset($mainFacilities) && $mainFacilities->count() > 0)
                                    @foreach ($mainFacilities as $facility)
                                        <tr class="hover:bg-white/40 transition-colors">
                                            <td class="py-3 pl-1">
                                                <div class="w-8 h-8 rounded-xl bg-white/80 border border-white shadow-2xs flex items-center justify-center text-slate-700">
                                                    <x-facility-icon :tipe="$facility->tipe" class="w-4 h-4 text-[#0F5143]" />
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="font-semibold text-slate-800">{{ $facility->nama }}</div>
                                                <div class="text-[11px] text-slate-400">{{ $facility->lokasi }}</div>
                                            </td>
                                            <td class="py-3 text-center">
                                                @if ($facility->status === 'aktif')
                                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100/80 text-emerald-800 border border-emerald-200">
                                                        Aktif
                                                    </span>
                                                @elseif ($facility->status === 'dalam_perbaikan')
                                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100/80 text-rose-800 border border-rose-200">
                                                        Perbaikan
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100/80 text-slate-700 border border-slate-200">
                                                        Nonaktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-right pr-2 font-medium text-slate-600">
                                                {{ $facility->kapasitas ? $facility->kapasitas . ' orang' : ($facility->tipe === 'alat' ? '1 unit' : 'Aktif') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <!-- Fallback rows default -->
                                    <tr class="hover:bg-white/40 transition-colors">
                                        <td class="py-3 pl-1">
                                            <div class="w-8 h-8 rounded-xl bg-white/80 border border-white shadow-2xs flex items-center justify-center text-slate-700">
                                                <x-facility-icon tipe="aula" class="w-4 h-4 text-[#0F5143]" />
                                            </div>
                                        </td>
                                        <td class="py-3 font-semibold text-slate-800">Aula Utama</td>
                                        <td class="py-3 text-center">
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100/80 text-emerald-800 border border-emerald-200">Aktif</span>
                                        </td>
                                        <td class="py-3 text-right pr-2 font-medium text-slate-600">500 orang</td>
                                    </tr>
                                    <tr class="hover:bg-white/40 transition-colors">
                                        <td class="py-3 pl-1">
                                            <div class="w-8 h-8 rounded-xl bg-white/80 border border-white shadow-2xs flex items-center justify-center text-slate-700">
                                                <x-facility-icon tipe="laboratorium" class="w-4 h-4 text-[#0F5143]" />
                                            </div>
                                        </td>
                                        <td class="py-3 font-semibold text-slate-800">Lab Komputer</td>
                                        <td class="py-3 text-center">
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100/80 text-rose-800 border border-rose-200">Perbaikan</span>
                                        </td>
                                        <td class="py-3 text-right pr-2 font-medium text-slate-600">40 orang</td>
                                    </tr>
                                    <tr class="hover:bg-white/40 transition-colors">
                                        <td class="py-3 pl-1">
                                            <div class="w-8 h-8 rounded-xl bg-white/80 border border-white shadow-2xs flex items-center justify-center text-slate-700">
                                                <x-facility-icon tipe="lapangan" class="w-4 h-4 text-[#0F5143]" />
                                            </div>
                                        </td>
                                        <td class="py-3 font-semibold text-slate-800">Lapangan Futsal</td>
                                        <td class="py-3 text-center">
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100/80 text-emerald-800 border border-emerald-200">Aktif</span>
                                        </td>
                                        <td class="py-3 text-right pr-2 font-medium text-slate-600">Aktif</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Link Fungsional -->
                    <div class="mt-3 pt-2 text-right">
                        <a href="{{ route('admin.facilities.index') }}" class="text-xs font-semibold text-teal-800 hover:text-teal-900 transition-colors inline-flex items-center gap-1">
                            <span>Cek Ketersediaan Selengkapnya</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- ----------------------------------------------------
                     CARD 4: Metric KPI 2x2 (Col Span 5 - Nilai Aktual)
                     ---------------------------------------------------- -->
                @php
                    $occupancyValue = $stats['occupancy_rate'] ?? 0;
                    $dashOffset = 88 - (88 * ($occupancyValue / 100));
                @endphp
                <div class="col-span-12 xl:col-span-5 grid grid-cols-2 gap-4">
                    <!-- Metric 1: Rata-rata Okupansi / Rasio Kesiapan -->
                    <div class="bg-white/70 backdrop-blur-md border border-white/80 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center gap-2.5">
                            <!-- Circular Donut Arc SVG Dinamis -->
                            <div class="relative w-8 h-8 shrink-0 flex items-center justify-center">
                                <svg class="w-8 h-8 -rotate-90" viewBox="0 0 36 36">
                                    <circle cx="18" cy="18" r="14" fill="none" stroke="#E2E8F0" stroke-width="3.5" />
                                    <circle cx="18" cy="18" r="14" fill="none" stroke="#0F5143" stroke-width="3.5"
                                            stroke-dasharray="88" stroke-dashoffset="{{ $dashOffset }}" stroke-linecap="round" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 leading-tight">Rata-rata Okupansi</span>
                        </div>
                        <div class="mt-3">
                            <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $occupancyValue }}%</span>
                            <p class="text-[10px] text-slate-500 mt-0.5">{{ $stats['aktif'] ?? 0 }} dari {{ $stats['total'] ?? 0 }} unit siap</p>
                        </div>
                    </div>

                    <!-- Metric 2: Laporan Kerusakan Baru -->
                    <a href="{{ route('report') }}" class="bg-white/70 backdrop-blur-md border border-white/80 rounded-2xl p-4 shadow-sm flex flex-col justify-between hover:bg-white/90 transition-all group">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-amber-100/90 text-amber-700 border border-amber-200/80 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 leading-tight group-hover:text-[#0F5143] transition-colors">Laporan Kerusakan Baru</span>
                        </div>
                        <div class="mt-3">
                            <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['new_reports'] ?? 0 }}</span>
                            <p class="text-[10px] text-slate-500 mt-0.5">Menunggu perbaikan</p>
                        </div>
                    </a>

                    <!-- Metric 3: Fasilitas dalam Perbaikan -->
                    <a href="{{ route('admin.facilities.index', ['status' => 'dalam_perbaikan']) }}" class="bg-white/70 backdrop-blur-md border border-white/80 rounded-2xl p-4 shadow-sm flex flex-col justify-between hover:bg-white/90 transition-all group">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-orange-100/90 text-orange-700 border border-orange-200/80 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 leading-tight group-hover:text-[#0F5143] transition-colors">Fasilitas dalam Perbaikan</span>
                        </div>
                        <div class="mt-3">
                            <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['dalam_perbaikan'] ?? 0 }}</span>
                            <p class="text-[10px] text-slate-500 mt-0.5">Sedang pemeliharaan</p>
                        </div>
                    </a>

                    <!-- Metric 4: Laporan Terselesaikan -->
                    <div class="bg-white/70 backdrop-blur-md border border-white/80 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100/90 text-emerald-700 border border-emerald-200/80 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 leading-tight">Laporan Terselesaikan</span>
                        </div>
                        <div class="mt-3">
                            <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['resolved_reports'] ?? 0 }}</span>
                            <p class="text-[10px] text-slate-500 mt-0.5">Penanganan sukses</p>
                        </div>
                    </div>
                </div>


            </div>
        </main>
    </div>

    <!-- ==========================================
         MODAL PENGATURAN SISTEM
         ========================================== -->
    <div id="settings-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="w-full max-w-lg bg-white/95 backdrop-blur-2xl border border-white/80 shadow-2xl rounded-3xl p-6 sm:p-7 relative z-50">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#0F5143] text-white flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Pengaturan Sistem FacilityHub</h3>
                        <p class="text-xs text-slate-500">Konfigurasi operasional dan profil admin</p>
                    </div>
                </div>
                <button type="button" onclick="toggleSettingsModal()" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
            </div>

            <div class="py-4 space-y-4 text-xs">
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200">
                    <p class="font-bold text-slate-800 text-sm">Akun Administrator Aktif</p>
                    <div class="mt-2 grid grid-cols-2 gap-2 text-slate-600">
                        <div>Nama: <strong class="text-slate-900">{{ auth()->user()->name ?? 'Admin' }}</strong></div>
                        <div>Email: <strong class="text-slate-900">{{ auth()->user()->email ?? 'admin@kampus.test' }}</strong></div>
                        <div>Role: <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">Super Admin</span></div>
                        <div>Zona Waktu: <strong class="text-slate-900">Asia/Jakarta (WIB)</strong></div>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200">
                    <p class="font-bold text-slate-800 text-sm">Aturan Slot Operasional (DESIGN.md)</p>
                    <p class="text-slate-600 mt-1">
                        Jam operasional: <strong>07.00 - 20.00 WIB</strong> (26 slot tetap x 30 menit). Reservasi dan pelaporan terikat pada matriks fasilitas.
                    </p>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200 flex justify-end gap-2.5">
                <button type="button" onclick="toggleSettingsModal()"
                        class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition-colors">
                    Tutup
                </button>
                <a href="{{ route('admin.facilities.create') }}"
                   class="px-4 py-2 rounded-xl bg-[#0F5143] text-white font-semibold text-xs hover:bg-[#0B3D32] transition-colors">
                    Tambah Fasilitas Baru
                </a>
            </div>
        </div>
    </div>

    <!-- ==========================================
         JAVASCRIPT INTERAKTIF TOPBAR & SIDEBAR
         ========================================== -->
    <script>
        function toggleSidebar() {
            const navLinks = document.getElementById('nav-links');
            const backdrop = document.getElementById('general-backdrop');
            const isHidden = navLinks.classList.contains('hidden');

            if (isHidden) {
                navLinks.classList.remove('hidden');
                backdrop.classList.remove('hidden');
            } else {
                navLinks.classList.add('hidden');
                backdrop.classList.add('hidden');
            }
        }

        function toggleNotifications() {
            const popover = document.getElementById('notification-popover');
            const profile = document.getElementById('profile-dropdown');
            if (profile && !profile.classList.contains('hidden')) {
                profile.classList.add('hidden');
            }
            if (popover) {
                popover.classList.toggle('hidden');
            }
        }

        function toggleProfileDropdown() {
            const profile = document.getElementById('profile-dropdown');
            const notif = document.getElementById('notification-popover');
            if (notif && !notif.classList.contains('hidden')) {
                notif.classList.add('hidden');
            }
            if (profile) {
                profile.classList.toggle('hidden');
            }
        }

        function toggleSettingsModal() {
            const modal = document.getElementById('settings-modal');
            const backdrop = document.getElementById('general-backdrop');
            modal.classList.toggle('hidden');
            backdrop.classList.toggle('hidden');
        }

        function closeAllModals() {
            const modal = document.getElementById('settings-modal');
            const backdrop = document.getElementById('general-backdrop');
            const navLinks = document.getElementById('nav-links');
            const notif = document.getElementById('notification-popover');
            const profile = document.getElementById('profile-dropdown');

            if (modal) modal.classList.add('hidden');
            if (backdrop) backdrop.classList.add('hidden');
            if (navLinks && window.innerWidth < 1024) navLinks.classList.add('hidden');
            if (notif) notif.classList.add('hidden');
            if (profile) profile.classList.add('hidden');
        }

        // Tutup popover jika klik di luar elemen
        document.addEventListener('click', function(event) {
            const notifBtn = event.target.closest('[aria-label="Notifications"]');
            const notifPopover = document.getElementById('notification-popover');
            const profileBtn = event.target.closest('button[onclick="toggleProfileDropdown()"]');
            const profileDropdown = document.getElementById('profile-dropdown');

            if (!notifBtn && notifPopover && !notifPopover.contains(event.target)) {
                notifPopover.classList.add('hidden');
            }

            if (!profileBtn && profileDropdown && !profileDropdown.contains(event.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
