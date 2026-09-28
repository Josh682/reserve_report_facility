<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--[if gte mso 9]>
    @php
    echo '<xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Rekapitulasi Fasilitas</x:Name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>';
    @endphp
    <![endif]-->
    <style>
        body {
            font-family: Calibri, 'Segoe UI', Arial, sans-serif;
            font-size: 11pt;
            color: #0f172a;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #94a3b8;
            padding: 8px 10px;
            vertical-align: middle;
        }
        th {
            background-color: #0F5143;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
        }
        .header-title {
            font-size: 16pt;
            font-weight: bold;
            color: #0F5143;
            text-align: left;
            border: none;
            padding-bottom: 4px;
        }
        .header-subtitle {
            font-size: 12pt;
            font-weight: bold;
            color: #334155;
            text-align: left;
            border: none;
            padding-bottom: 10px;
        }
        .meta-table td {
            border: none;
            padding: 4px 6px;
            font-size: 10pt;
        }
        .meta-label {
            font-weight: bold;
            color: #475569;
            width: 140px;
        }
        .section-header {
            font-size: 12pt;
            font-weight: bold;
            color: #0F5143;
            background-color: #e6f4f1;
            padding: 8px 10px;
            border: 1px solid #0F5143;
            text-align: left;
        }
        .kpi-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-size: 10pt;
            text-align: center;
        }
        .kpi-table td {
            background-color: #f8fafc;
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
            color: #0F5143;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .bg-total {
            background-color: #e2e8f0;
            font-weight: bold;
        }
        .badge-success {
            color: #15803d;
            font-weight: bold;
        }
        .badge-danger {
            color: #b91c1c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Title & Metadata -->
    <table>
        <tr>
            <td colspan="8" class="header-title">LAPORAN REKAPITULASI OKUPANSI FASILITAS &amp; FREKUENSI KERUSAKAN</td>
        </tr>
        <tr>
            <td colspan="8" class="header-subtitle">Biro Pengelolaan Sarana dan Prasarana Kampus — FacilityHub</td>
        </tr>
    </table>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Periode Filter</td>
            <td>: {{ $periodeLabel }}</td>
            <td class="meta-label">Waktu Ekspor</td>
            <td>: {{ now()->format('d/m/Y H:i:s') }} WIB</td>
        </tr>
        <tr>
            <td class="meta-label">Dicetak Oleh</td>
            <td>: {{ auth()->user()->name ?? 'Administrator' }}</td>
            <td class="meta-label">Status Sistem</td>
            <td>: Terverifikasi Operasional</td>
        </tr>
    </table>

    <!-- Ringkasan KPI Eksekutif -->
    <table>
        <tr>
            <th colspan="6" class="section-header">RINGKASAN METRIK UTAMA (KPI)</th>
        </tr>
    </table>
    <table class="kpi-table">
        <tr>
            <th>Total Jam Penggunaan</th>
            <th>Total Reservasi Disetujui</th>
            <th>Fasilitas Teraktif</th>
            <th>Total Laporan Insiden</th>
            <th>Insiden Selesai</th>
            <th>Tingkat Resolusi</th>
        </tr>
        <tr>
            <td>{{ $occupancySummary['total_jam'] }} Jam</td>
            <td>{{ number_format($occupancySummary['total_reservasi']) }} Reservasi</td>
            <td>{{ $occupancySummary['fasilitas_paling_sering'] }}</td>
            <td>{{ number_format($damageSummary['total_insiden']) }} Laporan</td>
            <td>{{ number_format($damageSummary['total_selesai']) }} Selesai</td>
            <td>{{ $damageSummary['persentase_resolusi'] }}%</td>
        </tr>
    </table>

    <!-- Bagian A: Tabel Rekapitulasi Okupansi Fasilitas -->
    <table>
        <tr>
            <th colspan="8" class="section-header">BAGIAN A: TABEL REKAPITULASI OKUPANSI FASILITAS</th>
        </tr>
        <tr>
            <th style="width: 40px;">No</th>
            <th>Nama Fasilitas</th>
            <th>Tipe</th>
            <th>Lokasi</th>
            <th>Kapasitas</th>
            <th>Total Booking</th>
            <th>Total Jam Pakai</th>
            <th>Pemesan Teraktif</th>
        </tr>
        @forelse($occupancyData as $index => $item)
            <tr style="background-color: {{ $index % 2 === 1 ? '#f8fafc' : '#ffffff' }};">
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-left" style="font-weight: 600;">{{ $item['facility_nama'] }}</td>
                <td class="text-center">{{ ucfirst($item['facility_tipe']) }}</td>
                <td class="text-left">{{ $item['facility_lokasi'] }}</td>
                <td class="text-center">{{ number_format($item['facility_kapasitas']) }} Orang</td>
                <td class="text-center">{{ number_format($item['total_reservasi']) }}</td>
                <td class="text-center">{{ $item['total_jam'] }} Jam</td>
                <td class="text-left">{{ $item['pemesan_terbanyak'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center" style="color: #64748b; font-style: italic; padding: 14px;">Tidak ada data okupansi fasilitas pada periode ini.</td>
            </tr>
        @endforelse
        <tr class="bg-total">
            <td colspan="5" class="text-right" style="font-weight: bold;">TOTAL KESELURUHAN:</td>
            <td class="text-center" style="font-weight: bold;">{{ number_format($occupancyData->sum('total_reservasi')) }}</td>
            <td class="text-center" style="font-weight: bold;">{{ round($occupancyData->sum('total_jam'), 1) }} Jam</td>
            <td class="text-center">-</td>
        </tr>
    </table>

    <!-- Bagian B: Tabel Frekuensi Kerusakan per Fasilitas -->
    <table>
        <tr>
            <th colspan="10" class="section-header">BAGIAN B: TABEL FREKUENSI KERUSAKAN PER FASILITAS</th>
        </tr>
        <tr>
            <th style="width: 40px;">No</th>
            <th>Nama Fasilitas</th>
            <th>Lokasi</th>
            <th>Total Laporan</th>
            <th>Fisik</th>
            <th>Kebersihan</th>
            <th>Lainnya</th>
            <th>Selesai</th>
            <th>Belum Selesai</th>
            <th>% Resolusi</th>
        </tr>
        @forelse($damageData as $index => $item)
            <tr style="background-color: {{ $index % 2 === 1 ? '#f8fafc' : '#ffffff' }};">
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-left" style="font-weight: 600;">{{ $item['facility_nama'] }}</td>
                <td class="text-left">{{ $item['facility_lokasi'] }}</td>
                <td class="text-center" style="font-weight: bold;">{{ number_format($item['total_laporan']) }}</td>
                <td class="text-center">{{ number_format($item['kerusakan']) }}</td>
                <td class="text-center">{{ number_format($item['kebersihan']) }}</td>
                <td class="text-center">{{ number_format($item['lainnya']) }}</td>
                <td class="text-center badge-success">{{ number_format($item['selesai']) }}</td>
                <td class="text-center {{ $item['belum_selesai'] > 0 ? 'badge-danger' : '' }}">{{ number_format($item['belum_selesai']) }}</td>
                <td class="text-center" style="font-weight: bold;">{{ $item['tingkat_penyelesaian'] }}%</td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center" style="color: #64748b; font-style: italic; padding: 14px;">Tidak ada catatan laporan kendala/kerusakan pada periode ini.</td>
            </tr>
        @endforelse
        <tr class="bg-total">
            <td colspan="3" class="text-right" style="font-weight: bold;">TOTAL:</td>
            <td class="text-center" style="font-weight: bold;">{{ number_format($damageData->sum('total_laporan')) }}</td>
            <td class="text-center" style="font-weight: bold;">{{ number_format($damageData->sum('kerusakan')) }}</td>
            <td class="text-center" style="font-weight: bold;">{{ number_format($damageData->sum('kebersihan')) }}</td>
            <td class="text-center" style="font-weight: bold;">{{ number_format($damageData->sum('lainnya')) }}</td>
            <td class="text-center badge-success" style="font-weight: bold;">{{ number_format($damageData->sum('selesai')) }}</td>
            <td class="text-center badge-danger" style="font-weight: bold;">{{ number_format($damageData->sum('belum_selesai')) }}</td>
            <td class="text-center" style="font-weight: bold;">{{ $damageSummary['persentase_resolusi'] }}%</td>
        </tr>
    </table>

    <!-- Bagian C: Tabel Kerusakan per Lokasi/Gedung -->
    <table>
        <tr>
            <th colspan="5" class="section-header">BAGIAN C: TABEL KERUSAKAN PER LOKASI / GEDUNG</th>
        </tr>
        <tr>
            <th style="width: 40px;">No</th>
            <th>Lokasi Gedung</th>
            <th>Total Insiden</th>
            <th>Selesai</th>
            <th>% Resolusi</th>
        </tr>
        @forelse($locationDamageData as $index => $item)
            <tr style="background-color: {{ $index % 2 === 1 ? '#f8fafc' : '#ffffff' }};">
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-left" style="font-weight: 600;">{{ $item['lokasi'] }}</td>
                <td class="text-center" style="font-weight: bold;">{{ number_format($item['total_insiden']) }}</td>
                <td class="text-center badge-success">{{ number_format($item['selesai']) }}</td>
                <td class="text-center" style="font-weight: bold;">{{ $item['tingkat_penyelesaian'] }}%</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center" style="color: #64748b; font-style: italic; padding: 14px;">Tidak ada riwayat per gedung pada periode ini.</td>
            </tr>
        @endforelse
        <tr class="bg-total">
            <td colspan="2" class="text-right" style="font-weight: bold;">TOTAL:</td>
            <td class="text-center" style="font-weight: bold;">{{ number_format($locationDamageData->sum('total_insiden')) }}</td>
            <td class="text-center badge-success" style="font-weight: bold;">{{ number_format($locationDamageData->sum('selesai')) }}</td>
            <td class="text-center" style="font-weight: bold;">{{ $damageSummary['persentase_resolusi'] }}%</td>
        </tr>
    </table>
</body>
</html>
