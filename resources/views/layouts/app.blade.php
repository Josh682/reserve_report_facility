<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Katalog Fasilitas Kampus') — FacilityHub</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        /* =======================================================
           FACILITYHUB LIGHT MODE FROSTED GLASSMORPHISM STYLES
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
            border: 1.5px solid #94A3B8;
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03), 0 1px 2px rgba(255, 255, 255, 0.65);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            color: #0F172A;
        }
        .kezak-input::placeholder {
            color: #64748B;
        }
        .kezak-input:hover {
            background: #FFFFFF;
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
    </style>
    @stack('styles')
</head>
<body class="min-h-screen relative overflow-x-hidden font-sans antialiased text-slate-800 p-3 sm:p-5 lg:p-7 flex flex-col justify-center">

    <!-- ==========================================
         AMBIENT BLOBS (Kunci Efek Kaca Frosted)
         ========================================== -->
    <div class="ambient-blob-1 fixed top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full blur-[120px] pointer-events-none -z-0"></div>
    <div class="ambient-blob-2 fixed bottom-[-10%] right-[-5%] w-[600px] h-[600px] rounded-full blur-[140px] pointer-events-none -z-0"></div>
    <div class="ambient-blob-3 fixed top-[40%] right-[30%] w-[350px] h-[350px] rounded-full blur-[100px] pointer-events-none -z-0"></div>

    <!-- ==========================================
         MASTER FLOATING GLASS CONTAINER WINDOW
         (Selaras dengan Admin Dashboard & DESIGN.md)
         ========================================== -->
    <div class="w-full max-w-[1420px] mx-auto bg-white/45 backdrop-blur-2xl border border-white/50 shadow-[0_25px_60px_rgba(15,81,67,0.12)] rounded-[32px] p-5 sm:p-7 lg:p-8 relative z-10 flex flex-col gap-6 sm:gap-8 my-2 sm:my-6 min-h-[880px]">

        <!-- ==========================================
             TOP HEADER DALAM MASTER CARD
             ========================================== -->
        <header class="flex items-center justify-between pb-5 border-b border-white/40 gap-4">
            
            <!-- Brand Logo & Title -->
            <a href="{{ route('facilities') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-white/80 backdrop-blur-md border border-white/90 shadow-xs flex items-center justify-center text-[#0F5143] group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6 text-[#0F5143]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-lg sm:text-xl tracking-tight text-slate-800">FacilityHub</span>
                        <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-500/15 border border-teal-500/20 text-[#0F5143]">
                            Katalog Fasilitas
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 hidden sm:block">Sistem Reservasi & Pelaporan Fasilitas Kampus</p>
                </div>
            </a>

            <!-- Auth Status / Actions -->
            <div class="flex items-center gap-2.5">
                @auth
                    <div class="flex items-center gap-3 bg-white/70 backdrop-blur-md border border-white/80 shadow-2xs rounded-2xl p-1.5 pr-3">
                        <div class="w-8 h-8 rounded-xl bg-[#0F5143] text-white flex items-center justify-center font-bold text-xs shadow-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:block text-left text-xs">
                            <strong class="text-slate-800 font-bold block leading-tight">{{ auth()->user()->name }}</strong>
                            <span class="text-[10px] text-slate-500">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                    </div>

                    @php
                        $dashboardRoute = match (auth()->user()->role) {
                            'admin' => route('admin.dashboard'),
                            'petugas' => route('petugas.dashboard'),
                            default => route('pengguna.dashboard'),
                        };
                    @endphp

                    <a href="{{ $dashboardRoute }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-[#0F5143] hover:bg-[#146353] shadow-xs hover:shadow-md transition-all">
                        Dashboard Saya
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50/80 transition-colors cursor-pointer" title="Keluar (Logout)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-slate-700 hover:text-slate-900 bg-white/70 hover:bg-white/95 border border-white/80 transition-all shadow-2xs">
                        Masuk Akun
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-white bg-[#0F5143] hover:bg-[#146353] shadow-xs hover:shadow-md transition-all">
                        Daftar Akun
                    </a>
                @endauth
            </div>
        </header>

        <!-- ==========================================
             MAIN CONTENT AREA (DALAM MASTER CARD)
             ========================================== -->
        <main class="flex-1 w-full">
            @yield('content')
        </main>

        <!-- ==========================================
             FROSTED GLASS FOOTER (DALAM MASTER CARD)
             ========================================== -->
        <footer class="pt-5 border-t border-white/40 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-700">FacilityHub</span>
                <span>•</span>
                <span>Sistem Reservasi & Pelaporan Fasilitas Kampus</span>
            </div>
            <div>
                <span>© {{ date('Y') }} FSM Facility. Hak Cipta Dilindungi.</span>
            </div>
        </footer>

    </div>

    @stack('scripts')
</body>
</html>