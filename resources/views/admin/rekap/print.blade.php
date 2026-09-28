<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Laporan Rekapitulasi Fasilitas — FacilityHub</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Times+New+Roman&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        /* Base Screen Styles */
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .screen-topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .print-container {
            max-width: 210mm;
            min-height: 297mm;
            margin: 24px auto 48px auto;
            background: #ffffff;
            padding: 20mm 18mm;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Kop Surat */
        .kop-wrapper {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 14px;
            border-bottom: 3px double #0f172a;
            margin-bottom: 18px;
        }

        .kop-logo {
            width: 72px;
            height: 72px;
            flex-shrink: 0;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-univ-sub {
            font-size: 11pt;
            font-weight: 600;
            letter-spacing: 0.05em;
            color: #334155;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.2;
        }

        .kop-univ-main {
            font-size: 14pt;
            font-weight: 800;
            letter-spacing: 0.03em;
            color: #0f172a;
            text-transform: uppercase;
            margin: 2px 0;
            line-height: 1.2;
        }

        .kop-biro {
            font-size: 13pt;
            font-weight: 800;
            color: #0F5143;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin: 2px 0;
            line-height: 1.2;
        }

        .kop-address {
            font-size: 8pt;
            color: #64748b;
            margin-top: 4px;
            line-height: 1.4;
        }

        /* Report Header & Meta */
        .report-title-box {
            text-align: center;
            margin-bottom: 18px;
        }

        .report-title {
            font-size: 13pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #0f172a;
            margin: 0 0 4px 0;
        }

        .report-number {
            font-size: 8.5pt;
            color: #64748b;
            font-weight: 600;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 8.5pt;
        }

        .meta-item-label {
            color: #64748b;
            font-size: 7.5pt;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.03em;
            margin-bottom: 2px;
        }

        .meta-item-value {
            font-weight: 700;
            color: #0f172a;
        }

        /* KPI Executive Grid */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }

        .kpi-card {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 6px;
            text-align: center;
            background: #ffffff;
        }

        .kpi-card-title {
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 3px;
            line-height: 1.1;
        }

        .kpi-card-value {
            font-size: 11pt;
            font-weight: 800;
            color: #0F5143;
            line-height: 1.2;
        }

        /* Section Headings */
        .section-header {
            font-size: 9.5pt;
            font-weight: 800;
            color: #0F5143;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin: 18px 0 8px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Report Tables */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-bottom: 16px;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #94a3b8;
            padding: 5px 7px;
            vertical-align: middle;
        }

        .report-table th {
            background-color: #0F5143;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .report-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .report-table tfoot tr {
            background-color: #e2e8f0;
            font-weight: 700;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }

        /* Signatures */
        .signature-section {
            margin-top: 28px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 200px;
            text-align: center;
            font-size: 8.5pt;
        }

        .signature-space {
            height: 64px;
        }

        .signature-name {
            font-weight: 800;
            color: #0f172a;
            border-bottom: 1px solid #0f172a;
            padding-bottom: 2px;
            display: inline-block;
        }

        .signature-nip {
            font-size: 8pt;
            color: #475569;
            margin-top: 2px;
        }

        /* Formal Document Footer */
        .doc-footer {
            margin-top: 24px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            font-size: 7pt;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Print Media Styles */
        @page {
            size: A4 portrait;
            margin: 12mm 10mm 14mm 10mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #ffffff !important;
                color: #0f172a !important;
                margin: 0 !important;
                padding: 0 !important;
                font-size: 8pt !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .print-container {
                max-width: none !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            .report-table th {
                background-color: #0F5143 !important;
                color: #ffffff !important;
            }

            .kpi-card {
                border-color: #cbd5e1 !important;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            .signature-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Screen Action Bar (Hidden on Print) -->
    <header class="screen-topbar no-print">
        <div style="display: flex; align-items: center; gap: 14px;">
            <a href="{{ route('admin.rekap.index', request()->query()) }}"
               style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: #f1f5f9; color: #334155; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; border: 1px solid #cbd5e1;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Rekapitulasi
            </a>
            <div>
                <h1 style="font-size: 14px; font-weight: 700; margin: 0; color: #0f172a;">Pratinjau Cetak Rekapitulasi Dokumen A4</h1>
                <p style="font-size: 12px; color: #64748b; margin: 0;">Sistem Pengelolaan Fasilitas Kampus (FacilityHub)</p>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 10px;">
            <button type="button"
                    onclick="window.print()"
                    style="display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; background: #0F5143; color: #ffffff; border-radius: 8px; border: none; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 2px 4px rgba(15,81,67,0.2);">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Dokumen (Ctrl+P)
            </button>
        </div>
    </header>

    <!-- Main Printable Sheet -->
    <main class="print-container">
        <!-- 1. Formal University Letterhead (Kop Surat Kampus) -->
        <div class="kop-wrapper">
            <div class="kop-logo">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;">
                    <polygon points="50,4 92,26 92,74 50,96 8,74 8,26" stroke="#0F5143" stroke-width="5" fill="#f0fdf4"/>
                    <circle cx="50" cy="50" r="28" stroke="#0F5143" stroke-width="3" fill="#ffffff"/>
                    <path d="M50 28 L50 72 M28 50 L72 50" stroke="#0F5143" stroke-width="3" stroke-linecap="round"/>
                    <path d="M35 35 L65 65 M35 65 L65 35" stroke="#059669" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="50" cy="50" r="7" fill="#0F5143"/>
                </svg>
            </div>
            <div class="kop-text">
                <p class="kop-univ-sub">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</p>
                <h2 class="kop-univ-main">Universitas Sarana Prasarana Nusantara</h2>
                <h1 class="kop-biro">Biro Sarana dan Prasarana</h1>
                <p class="kop-address">
                    Gedung Rektorat Terpadu Lt. 2, Kampus Induk Nusantara | Telepon: (021) 7888-0123 Ext. 204<br>
                    Laman: www.sarpras-nusantara.ac.id | Pos-el: sarpras@kampus.ac.id | Kode Pos 16424
                </p>
            </div>
        </div>

        <!-- 2. Official Title & Number -->
        <div class="report-title-box">
            <h2 class="report-title">LAPORAN REKAPITULASI OKUPANSI FASILITAS &amp; FREKUENSI KERUSAKAN</h2>
            <div class="report-number">Nomor: B/{{ date('Ymd') }}/UN1.SARPRAS/LOG/{{ date('Y') }}</div>
        </div>

        <!-- 3. Report Metadata Grid -->
        <div class="meta-grid">
            <div>
                <div class="meta-item-label">Rentang Periode Laporan</div>
                <div class="meta-item-value">{{ $periodeLabel }}</div>
            </div>
            <div>
                <div class="meta-item-label">Tanggal &amp; Waktu Cetak</div>
                <div class="meta-item-value">{{ now()->isoFormat('D MMMM Y, HH:mm') ?? now()->format('d F Y, H:i') }} WIB</div>
            </div>
            <div>
                <div class="meta-item-label">Administrator Penerbit</div>
                <div class="meta-item-value">{{ auth()->user()->name ?? 'Administrator Sistem' }}</div>
            </div>
        </div>

        <!-- 4. Ringkasan KPI Eksekutif -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-card-title">Total Jam Pakai</div>
                <div class="kpi-card-value">{{ $occupancySummary['total_jam'] }} J</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-card-title">Total Reservasi</div>
                <div class="kpi-card-value">{{ number_format($occupancySummary['total_reservasi']) }}</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-card-title">Fasilitas Favorit</div>
                <div class="kpi-card-value" style="font-size: 8.5pt; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" title="{{ $occupancySummary['fasilitas_paling_sering'] }}">
                    {{ $occupancySummary['fasilitas_paling_sering'] }}
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-card-title">Total Laporan</div>
                <div class="kpi-card-value">{{ number_format($damageSummary['total_insiden']) }}</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-card-title">Insiden Selesai</div>
                <div class="kpi-card-value" style="color: #15803d;">{{ number_format($damageSummary['total_selesai']) }}</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-card-title">Rasio Resolusi</div>
                <div class="kpi-card-value">{{ $damageSummary['persentase_resolusi'] }}%</div>
            </div>
        </div>

        <!-- 5. Bagian A: Tabel Rekapitulasi Okupansi Fasilitas -->
        <div class="section-header">
            <span>Bagian A: Rekapitulasi Okupansi Fasilitas</span>
        </div>
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 28px;">No</th>
                    <th style="text-align: left;">Nama Fasilitas</th>
                    <th style="width: 75px;">Tipe</th>
                    <th style="text-align: left;">Lokasi</th>
                    <th style="width: 55px;">Kapasitas</th>
                    <th style="width: 55px;">Booking</th>
                    <th style="width: 65px;">Total Jam</th>
                    <th style="text-align: left; width: 120px;">Pemesan Teraktif</th>
                </tr>
            </thead>
            <tbody>
                @forelse($occupancyData as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-left" style="font-weight: 600;">{{ $item['facility_nama'] }}</td>
                        <td class="text-center">{{ ucfirst($item['facility_tipe']) }}</td>
                        <td class="text-left">{{ $item['facility_lokasi'] }}</td>
                        <td class="text-center">{{ number_format($item['facility_kapasitas']) }}</td>
                        <td class="text-center" style="font-weight: 600;">{{ number_format($item['total_reservasi']) }}</td>
                        <td class="text-center" style="font-weight: 600;">{{ $item['total_jam'] }} J</td>
                        <td class="text-left">{{ $item['pemesan_terbanyak'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="color: #64748b; font-style: italic; padding: 10px;">
                            Tidak ditemukan reservasi yang disetujui pada rentang tanggal filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right">TOTAL KESELURUHAN:</td>
                    <td class="text-center">{{ number_format($occupancyData->sum('total_reservasi')) }}</td>
                    <td class="text-center">{{ round($occupancyData->sum('total_jam'), 1) }} J</td>
                    <td class="text-center">-</td>
                </tr>
            </tfoot>
        </table>

        <!-- 6. Bagian B: Tabel Frekuensi Kerusakan per Fasilitas -->
        <div class="section-header">
            <span>Bagian B: Frekuensi Kerusakan per Fasilitas</span>
        </div>
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 28px;">No</th>
                    <th style="text-align: left;">Nama Fasilitas</th>
                    <th style="text-align: left;">Lokasi</th>
                    <th style="width: 48px;">Total</th>
                    <th style="width: 40px;">Fisik</th>
                    <th style="width: 40px;">Kebersihan</th>
                    <th style="width: 40px;">Lainnya</th>
                    <th style="width: 45px;">Selesai</th>
                    <th style="width: 45px;">Belum</th>
                    <th style="width: 55px;">% Resolusi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($damageData as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-left" style="font-weight: 600;">{{ $item['facility_nama'] }}</td>
                        <td class="text-left">{{ $item['facility_lokasi'] }}</td>
                        <td class="text-center" style="font-weight: 700;">{{ number_format($item['total_laporan']) }}</td>
                        <td class="text-center">{{ number_format($item['kerusakan']) }}</td>
                        <td class="text-center">{{ number_format($item['kebersihan']) }}</td>
                        <td class="text-center">{{ number_format($item['lainnya']) }}</td>
                        <td class="text-center" style="color: #15803d; font-weight: 600;">{{ number_format($item['selesai']) }}</td>
                        <td class="text-center" style="color: {{ $item['belum_selesai'] > 0 ? '#b91c1c' : '#64748b' }};">{{ number_format($item['belum_selesai']) }}</td>
                        <td class="text-center" style="font-weight: 700;">{{ $item['tingkat_penyelesaian'] }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center" style="color: #64748b; font-style: italic; padding: 10px;">
                            Tidak terdapat laporan kendala fasilitas pada rentang tanggal yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">TOTAL LAPORAN:</td>
                    <td class="text-center">{{ number_format($damageData->sum('total_laporan')) }}</td>
                    <td class="text-center">{{ number_format($damageData->sum('kerusakan')) }}</td>
                    <td class="text-center">{{ number_format($damageData->sum('kebersihan')) }}</td>
                    <td class="text-center">{{ number_format($damageData->sum('lainnya')) }}</td>
                    <td class="text-center" style="color: #15803d;">{{ number_format($damageData->sum('selesai')) }}</td>
                    <td class="text-center" style="color: #b91c1c;">{{ number_format($damageData->sum('belum_selesai')) }}</td>
                    <td class="text-center">{{ $damageSummary['persentase_resolusi'] }}%</td>
                </tr>
            </tfoot>
        </table>

        <!-- 7. Bagian C: Tabel Kerusakan per Lokasi/Gedung -->
        <div class="section-header">
            <span>Bagian C: Tabel Kerusakan per Lokasi / Gedung</span>
        </div>
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 28px;">No</th>
                    <th style="text-align: left;">Lokasi Gedung</th>
                    <th style="width: 90px;">Total Insiden</th>
                    <th style="width: 90px;">Selesai</th>
                    <th style="width: 90px;">% Resolusi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locationDamageData as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-left" style="font-weight: 600;">{{ $item['lokasi'] }}</td>
                        <td class="text-center" style="font-weight: 700;">{{ number_format($item['total_insiden']) }}</td>
                        <td class="text-center" style="color: #15803d; font-weight: 600;">{{ number_format($item['selesai']) }}</td>
                        <td class="text-center" style="font-weight: 700;">{{ $item['tingkat_penyelesaian'] }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center" style="color: #64748b; font-style: italic; padding: 10px;">
                            Tidak ada insiden tercatat pada kluster gedung manapun pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right">TOTAL:</td>
                    <td class="text-center">{{ number_format($locationDamageData->sum('total_insiden')) }}</td>
                    <td class="text-center" style="color: #15803d;">{{ number_format($locationDamageData->sum('selesai')) }}</td>
                    <td class="text-center">{{ $damageSummary['persentase_resolusi'] }}%</td>
                </tr>
            </tfoot>
        </table>

        <!-- 8. Kolom Tanda Tangan Pengesahan Pimpinan Sarana Prasarana -->
        <div class="signature-section">
            <div class="signature-box">
                <div>Mengetahui / Disiapkan oleh,</div>
                <div style="font-size: 8pt; color: #475569;">Administrator Sistem</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ auth()->user()->name ?? 'Administrator Sistem' }}</div>
                <div class="signature-nip">NIP/ID: {{ auth()->user()->id ? 'ADM-'.str_pad((string) auth()->user()->id, 4, '0', STR_PAD_LEFT) : 'ADM-0001' }}</div>
            </div>

            <div class="signature-box">
                <div>{{ now()->translatedFormat('d F Y') ?? now()->format('d F Y') }}</div>
                <div style="font-weight: 700; color: #0f172a;">Mengesahkan,</div>
                <div style="font-size: 8pt; color: #475569;">Kepala Biro Sarana dan Prasarana</div>
                <div class="signature-space"></div>
                <div class="signature-name">Dr. Ir. H. Bambang Sudarsono, M.T.</div>
                <div class="signature-nip">NIP. 19750812 200212 1 001</div>
            </div>
        </div>

        <!-- 9. Formal Footer Note -->
        <footer class="doc-footer">
            <span>Dokumen dicetak melalui FacilityHub — Sistem Manajemen Terpadu Sarana Prasarana Kampus</span>
            <span>Halaman 1 / Resmi</span>
        </footer>
    </main>

    <!-- Skrip Otomatis Cetak Jendela Browser (window.print()) -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Berikan jeda sejenak untuk rendering layout & styling sebelum membuka dialog print otomatis
            setTimeout(() => {
                window.print();
            }, 400);
        });
    </script>
</body>
</html>
