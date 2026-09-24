<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistem Fasilitas Kampus')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

<div class="app-layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar">

        <div class="brand">
            <div class="brand-logo">RF</div>

            <div>
                <h2>FacilityHub</h2>
                <span>FSM Facility</span>
            </div>
        </div>


        <nav class="sidebar-menu">

            <a href="/facilities"
               class="menu-item {{ request()->is('facilities*') ? 'active' : '' }}">
                <span>⌂</span>
                Dashboard
            </a>

            <a href="/facilities"
               class="menu-item">
                <span>▦</span>
                Facilitys
            </a>

            <a href="/reservation"
               class="menu-item {{ request()->is('reservation*') ? 'active' : '' }}">
                <span>◷</span>
                My Reservation
            </a>

            <a href="/report"
               class="menu-item {{ request()->is('report*') ? 'active' : '' }}">
                <span>!</span>
                Reports
            </a>

        </nav>


        <div class="sidebar-bottom">

            <a href="#" class="menu-item">
                <span>⚙</span>
                Settings
            </a>

            @auth
                <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-item logout">
                    <span>↪</span>
                    Keluar (Logout)
                </a>
            @else
                <a href="{{ route('login') }}" class="menu-item" style="color: #2563eb; font-weight: 600;">
                    <span>↪</span>
                    Masuk (Login)
                </a>
            @endauth

        </div>

    </aside>


    {{-- MAIN --}}
    <main class="main-content">

        <header class="topbar">

            <div>
                <h3>@yield('page-title', 'Dashboard')</h3>
            </div>

            <div class="profile">
                @auth
                    <div class="avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>

                    <div class="profile-info">
                        <strong>{{ auth()->user()->name }}</strong>
                        <span>{{ ucfirst(auth()->user()->role) }} {{ auth()->user()->tipe_pengguna ? '('.ucfirst(auth()->user()->tipe_pengguna).')' : '' }}</span>
                    </div>
                @else
                    <a href="{{ route('login') }}" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
                        <div class="avatar" style="background: #f1f5f9; color: #64748b;">
                            👤
                        </div>
                        <div class="profile-info">
                            <strong style="color: #2563eb;">Masuk Akun</strong>
                            <span>Pengunjung Kampus</span>
                        </div>
                    </a>
                @endauth

            </div>

        </header>


        <section class="page-content">

            @yield('content')

        </section>

    </main>

</div>

</body>
</html>