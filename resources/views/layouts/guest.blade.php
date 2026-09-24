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
<body class="min-h-screen bg-slate-50 dark:bg-[#061014] font-sans antialiased text-slate-800 dark:text-slate-100 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 relative transition-colors duration-150">
    {{-- Floating Theme Toggle in top-right --}}
    <div class="absolute top-6 right-6">
        @include('partials.theme-toggle')
    </div>

    <div class="w-full max-w-md mx-auto text-center">
        <a href="{{ route('facilities') }}" class="inline-flex items-center gap-2.5 text-decoration-none group">
            <div class="w-10 h-10 rounded-xl bg-teal-700 dark:bg-teal-600 text-white flex items-center justify-center font-black text-base shadow-xs group-hover:bg-teal-800 transition-colors">
                RF
            </div>
            <span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white">FacilityHub</span>
        </a>
        <h2 class="mt-4 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
            {{ $header ?? 'Selamat Datang' }}
        </h2>
        @isset($subheader)
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                {{ $subheader }}
            </p>
        @endisset
    </div>

    <div class="mt-8 w-full max-w-md mx-auto">
        <div class="bg-white dark:bg-[#0b171c] py-8 px-6 sm:px-10 shadow-sm rounded-2xl border border-slate-200 dark:border-teal-950/70">
            @yield('content')
        </div>

        <p class="mt-6 text-center text-xs text-slate-400 dark:text-slate-500">
            &copy; {{ date('Y') }} Sistem Reservasi & Pelaporan Fasilitas Kampus.
        </p>
    </div>
</body>
</html>
