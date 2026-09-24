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

            <a href="/login" class="menu-item logout">
                <span>↪</span>
                Logout
            </a>

        </div>

    </aside>


    {{-- MAIN --}}
    <main class="main-content">

        <header class="topbar">

            <div>
                <h3>@yield('page-title', 'Dashboard')</h3>
            </div>

            <div class="profile">

                <div class="avatar">
                    VC
                </div>

                <div class="profile-info">
                    <strong>Vela Cherina</strong>
                    <span>Pengguna</span>
                </div>

            </div>

        </header>


        <section class="page-content">

            @yield('content')

        </section>

    </main>

</div>

</body>
</html>