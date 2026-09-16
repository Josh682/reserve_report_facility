<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Sistem Reservasi & Pelaporan Fasilitas Kampus' }}</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="h-full font-sans antialiased text-gray-900 dark:text-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-bold text-xl tracking-tight">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span>FasilitasKampus</span>
        </a>
        <h2 class="mt-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ $header ?? 'Selamat Datang' }}
        </h2>
        @isset($subheader)
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ $subheader }}
            </p>
        @endisset
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow sm:rounded-xl sm:px-10 border border-gray-200 dark:border-gray-700">
            @yield('content')
        </div>

        <p class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
            &copy; {{ date('Y') }} Sistem Reservasi & Pelaporan Fasilitas Kampus. Tugas Proyek PPK.
        </p>
    </div>
</body>
</html>
