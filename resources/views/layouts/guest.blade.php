<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full {{ request()->cookie('theme') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Sistem Reservasi & Pelaporan Fasilitas Kampus' }}</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.theme-script')
</head>
<body class="min-h-screen bg-slate-50 dark:bg-[#080d11] font-sans antialiased text-slate-800 dark:text-slate-100 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 relative transition-colors duration-150">
    {{-- Floating Theme Toggle in top-right --}}
    <div class="absolute top-6 right-6">
        @include('partials.theme-toggle')
    </div>

    <div class="w-full max-w-md mx-auto text-center">
        <a href="{{ route('facilities') }}" class="inline-flex items-center gap-2.5 text-decoration-none group">
            <div class="w-9 h-9 rounded-xs bg-teal-700 dark:bg-teal-600 text-white flex items-center justify-center font-mono font-bold text-sm tracking-wider shadow-none group-hover:bg-teal-800 transition-colors">
                RF
            </div>
            <span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white uppercase">FacilityHub</span>
        </a>
        <h2 class="mt-4 text-2xl font-black tracking-tight text-slate-900 dark:text-white uppercase">
            {{ $header ?? 'Selamat Datang' }}
        </h2>
        @isset($subheader)
            <p class="mt-2 text-xs text-slate-600 dark:text-slate-400">
                {{ $subheader }}
            </p>
        @endisset
    </div>

    <div class="mt-8 w-full max-w-md mx-auto">
        <div class="bg-white dark:bg-[#0c1419] py-8 px-6 sm:px-10 shadow-none rounded-xs border border-slate-200 dark:border-teal-950/80">
            @yield('content')
        </div>

        <p class="mt-6 text-center text-xs font-mono text-slate-400 dark:text-slate-500">
            &copy; {{ date('Y') }} SISTEM RESERVASI & PELAPORAN FASILITAS KAMPUS.
        </p>
    </div>
</body>
</html>
