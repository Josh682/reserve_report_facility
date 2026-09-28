@extends('layouts.admin')

@section('title', 'Rekapitulasi Operasional & Analitik')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Rekapitulasi & Analitik Fasilitas</h1>
            <p class="text-sm text-slate-500">Rekapitulasi Okupansi dan Frekuensi Kerusakan</p>
        </div>
    </div>

    {{-- Filter Rentang Waktu (Stub) --}}
    <div data-testid="filter-params" class="p-4 bg-white/70 rounded-xl">
        <span data-testid="filter-preset">{{ $filterParams['preset'] }}</span>
        <span data-testid="filter-start">{{ $filterParams['start_date'] ?? 'all' }}</span>
        <span data-testid="filter-end">{{ $filterParams['end_date'] ?? 'all' }}</span>
    </div>

    {{-- Ringkasan Okupansi --}}
    <div id="occupancy-section" class="p-4 bg-white/70 rounded-xl space-y-2">
        <h2 class="text-lg font-semibold text-slate-800">Rekapitulasi Okupansi</h2>
        <div data-testid="occupancy-total-jam">{{ $occupancySummary['total_jam'] }} jam</div>
        <div data-testid="occupancy-total-reservasi">{{ $occupancySummary['total_reservasi'] }}</div>
        <div data-testid="occupancy-fasilitas-terfavorit">{{ $occupancySummary['fasilitas_terfavorit'] }}</div>

        <ul class="divide-y divide-slate-100">
            @foreach($occupancyData as $item)
                <li data-testid="occupancy-facility-{{ $item['facility_id'] }}" class="py-1">
                    <span class="font-medium">{{ $item['facility_nama'] }}</span> - 
                    <span>{{ $item['total_jam'] }} jam</span> - 
                    <span>{{ $item['total_reservasi'] }} reservasi</span> - 
                    <span>Pemesan: {{ $item['pemesan_terbanyak'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Ringkasan Frekuensi Kerusakan --}}
    <div id="damage-section" class="p-4 bg-white/70 rounded-xl space-y-2">
        <h2 class="text-lg font-semibold text-slate-800">Frekuensi Kerusakan</h2>
        <div data-testid="damage-total-insiden">{{ $damageSummary['total_insiden'] }}</div>
        <div data-testid="damage-total-selesai">{{ $damageSummary['total_selesai'] }}</div>
        <div data-testid="damage-persentase-resolusi">{{ $damageSummary['persentase_resolusi'] }}%</div>
        <div data-testid="damage-lokasi-paling-rawan">{{ $damageSummary['lokasi_paling_rawan'] }}</div>

        <ul class="divide-y divide-slate-100">
            @foreach($damageData as $item)
                <li data-testid="damage-facility-{{ $item['facility_id'] }}" class="py-1">
                    <span class="font-medium">{{ $item['facility_nama'] }}</span> ({{ $item['facility_lokasi'] }}) - 
                    <span>{{ $item['total_laporan'] }} laporan</span> - 
                    <span>Selesai: {{ $item['selesai'] }}</span> - 
                    <span>Belum: {{ $item['belum_selesai'] }}</span> - 
                    <span>Resolusi: {{ $item['tingkat_penyelesaian'] }}%</span>
                </li>
            @endforeach
        </ul>

        <h3 class="text-md font-semibold text-slate-700 mt-4">Rekapitulasi per Lokasi Gedung</h3>
        <ul class="divide-y divide-slate-100">
            @foreach($locationDamageData as $item)
                <li data-testid="location-{{ Str::slug($item['lokasi']) }}" class="py-1">
                    <span class="font-medium">{{ $item['lokasi'] }}</span>: 
                    <span>{{ $item['total_laporan'] }} insiden</span>, 
                    <span>{{ $item['selesai'] }} selesai</span>, 
                    <span>{{ $item['belum_selesai'] }} belum selesai</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
