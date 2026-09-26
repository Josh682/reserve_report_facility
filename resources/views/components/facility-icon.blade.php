@props(['tipe' => 'aula', 'class' => 'w-6 h-6'])

@php
    $normalizedTipe = strtolower(trim((string) ($tipe ?? 'aula')));
@endphp

@if ($normalizedTipe === 'aula')
    <!-- AULA: Gedung Pertemuan / Grand Hall / Pillars & Pediment -->
    <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-6 9 6M4 9v11a1 1 0 001 1h14a1 1 0 001-1V9" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12v6m4-6v6m4-6v6M3 21h18" />
    </svg>
@elseif ($normalizedTipe === 'ruang_kelas' || $normalizedTipe === 'ruang kelas' || $normalizedTipe === 'kelas')
    <!-- RUANG KELAS: Papan Tulis / Presentasi Perkuliahan / Easel Chalkboard -->
    <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
    </svg>
@elseif ($normalizedTipe === 'laboratorium' || $normalizedTipe === 'lab')
    <!-- LABORATORIUM: Erlenmeyer Flask / Tabung Reaksi Kimia & Sains -->
    <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3h6m-5 0v5.586a1 1 0 01-.293.707l-5.414 5.414A2 2 0 006 18h12a2 2 0 001.707-3.293L14.293 9.293A1 1 0 0114 8.586V3M9 13h6" />
    </svg>
@elseif ($normalizedTipe === 'alat' || $normalizedTipe === 'peralatan')
    <!-- ALAT: Proyektor Multimedia / Perangkat Alat Presentasi & Perlengkapan -->
    <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
    </svg>
@elseif ($normalizedTipe === 'lapangan')
    <!-- LAPANGAN: Bola Olahraga / Basketball / Lapangan Olahraga Outdoor & Indoor -->
    <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.6 9h16.8M3.6 15h16.8M12 3a15.3 15.3 0 014 9 15.3 15.3 0 01-4 9 15.3 15.3 0 01-4-9 15.3 15.3 0 014-9z" />
    </svg>
@else
    <!-- DEFAULT: Gedung / Sarana Fasilitas Umum -->
    <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
    </svg>
@endif
