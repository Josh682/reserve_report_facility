<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RekapController extends Controller
{
    /**
     * Tampilkan halaman rekapitulasi operasional fasilitas, reservasi, dan pelaporan.
     */
    public function index(Request $request): View
    {
        $filterParams = $this->resolveDateFilter($request);

        $occupancyData = $this->getOccupancyData($filterParams['start_date'], $filterParams['end_date']);
        $occupancySummary = $this->getOccupancySummary($occupancyData);

        $damageData = $this->getDamageData($filterParams['start_date'], $filterParams['end_date']);
        $locationDamageData = $this->getLocationDamageData($damageData);
        $damageSummary = $this->getDamageSummary($damageData, $locationDamageData);

        $activeTab = $request->query('tab', 'okupansi');

        return view('admin.rekap.index', compact(
            'filterParams',
            'occupancyData',
            'occupancySummary',
            'damageData',
            'locationDamageData',
            'damageSummary',
            'activeTab'
        ));
    }

    /**
     * Resolusi rentang tanggal filter analitik (preset atau custom range).
     *
     * @return array{preset: string, start_date: ?string, end_date: ?string}
     */
    public function resolveDateFilter(Request $request): array
    {
        $preset = $request->query('preset');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if ($preset === '30_hari') {
            return [
                'preset' => '30_hari',
                'start_date' => Carbon::today()->subDays(30)->toDateString(),
                'end_date' => Carbon::today()->toDateString(),
            ];
        }

        if ($preset === 'semua') {
            return [
                'preset' => 'semua',
                'start_date' => null,
                'end_date' => null,
            ];
        }

        if ($preset === 'custom' || ($startDate !== null && $endDate !== null && ! in_array($preset, ['bulan_ini', '30_hari', 'semua'], true))) {
            $validStart = null;
            $validEnd = null;

            if ($startDate) {
                try {
                    $validStart = Carbon::parse($startDate)->toDateString();
                } catch (\Throwable) {
                    $validStart = null;
                }
            }

            if ($endDate) {
                try {
                    $validEnd = Carbon::parse($endDate)->toDateString();
                } catch (\Throwable) {
                    $validEnd = null;
                }
            }

            if ($validStart && $validEnd && $validStart > $validEnd) {
                [$validStart, $validEnd] = [$validEnd, $validStart];
            }

            return [
                'preset' => 'custom',
                'start_date' => $validStart ?? Carbon::today()->startOfMonth()->toDateString(),
                'end_date' => $validEnd ?? Carbon::today()->endOfMonth()->toDateString(),
            ];
        }

        return [
            'preset' => 'bulan_ini',
            'start_date' => Carbon::today()->startOfMonth()->toDateString(),
            'end_date' => Carbon::today()->endOfMonth()->toDateString(),
        ];
    }

    /**
     * Agregasi data okupansi reservasi disetujui (approved) per fasilitas.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function getOccupancyData(?string $startDate, ?string $endDate): Collection
    {
        $facilities = Facility::query()
            ->with([
                'reservations' => function ($query) use ($startDate, $endDate) {
                    $query->where('status', 'approved');

                    if ($startDate !== null) {
                        $query->whereDate('tanggal', '>=', $startDate);
                    }

                    if ($endDate !== null) {
                        $query->whereDate('tanggal', '<=', $endDate);
                    }

                    $query->with('user:id,name');
                },
            ])
            ->orderBy('nama')
            ->get();

        return $facilities->map(function (Facility $facility) {
            $reservations = $facility->reservations;
            $totalReservasi = $reservations->count();

            $totalMenit = 0;
            foreach ($reservations as $reservation) {
                $start = Carbon::parse($reservation->start_time);
                $end = Carbon::parse($reservation->end_time);
                $diff = $start->diffInMinutes($end, false);
                if ($diff > 0) {
                    $totalMenit += (int) $diff;
                }
            }

            $totalJam = round($totalMenit / 60, 1);

            $userCounts = $reservations
                ->groupBy('user_id')
                ->map(function ($group) {
                    $firstRes = $group->first();

                    return [
                        'count' => $group->count(),
                        'name' => $firstRes->user?->name ?? 'Pengguna #'.$firstRes->user_id,
                    ];
                })
                ->sortByDesc('count');

            $pemesanTerbanyak = $userCounts->isNotEmpty() ? $userCounts->first()['name'] : '-';

            return [
                'facility' => $facility,
                'facility_id' => $facility->id,
                'facility_nama' => $facility->nama,
                'facility_tipe' => $facility->tipe,
                'facility_lokasi' => $facility->lokasi,
                'facility_kapasitas' => $facility->kapasitas,
                'kapasitas' => $facility->kapasitas,
                'total_reservasi' => $totalReservasi,
                'total_menit' => $totalMenit,
                'total_jam' => $totalJam,
                'pemesan_terbanyak' => $pemesanTerbanyak,
            ];
        });
    }

    /**
     * Ringkasan metrik okupansi fasilitas.
     *
     * @param  Collection<int, array<string, mixed>>  $occupancyData
     * @return array<string, mixed>
     */
    public function getOccupancySummary(Collection $occupancyData): array
    {
        $totalMenit = (int) $occupancyData->sum('total_menit');
        $totalJam = round($totalMenit / 60, 1);
        $totalReservasi = (int) $occupancyData->sum('total_reservasi');

        $topFacility = $occupancyData
            ->where('total_reservasi', '>', 0)
            ->sortByDesc('total_reservasi')
            ->first();

        $topFacilityName = $topFacility ? $topFacility['facility_nama'] : '-';

        return [
            'total_jam' => $totalJam,
            'total_hours' => $totalJam,
            'total_reservasi' => $totalReservasi,
            'total_reservations' => $totalReservasi,
            'fasilitas_paling_sering' => $topFacilityName,
            'fasilitas_terfavorit' => $topFacilityName,
            'most_used_facility' => $topFacilityName,
        ];
    }

    /**
     * Agregasi laporan kendala dan frekuensi kerusakan per fasilitas.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function getDamageData(?string $startDate, ?string $endDate): Collection
    {
        $facilities = Facility::query()
            ->with([
                'reports' => function ($query) use ($startDate, $endDate) {
                    if ($startDate !== null) {
                        $query->whereDate('created_at', '>=', $startDate);
                    }

                    if ($endDate !== null) {
                        $query->whereDate('created_at', '<=', $endDate);
                    }
                },
            ])
            ->orderBy('nama')
            ->get();

        return $facilities->map(function (Facility $facility) {
            $reports = $facility->reports;
            $totalLaporan = $reports->count();

            $kerusakan = $reports->where('kategori', 'kerusakan')->count();
            $kebersihan = $reports->where('kategori', 'kebersihan')->count();
            $lainnya = $reports->where('kategori', 'lainnya')->count();

            $selesai = $reports->where('status', 'selesai')->count();
            $belumSelesai = $reports->whereIn('status', ['baru', 'diproses'])->count();
            $ditolak = $reports->where('status', 'ditolak')->count();

            $tingkatPenyelesaian = $totalLaporan > 0 ? round(($selesai / $totalLaporan) * 100, 1) : 0.0;

            return [
                'facility' => $facility,
                'facility_id' => $facility->id,
                'facility_nama' => $facility->nama,
                'facility_tipe' => $facility->tipe,
                'facility_lokasi' => $facility->lokasi,
                'total_laporan' => $totalLaporan,
                'total_insiden' => $totalLaporan,
                'kerusakan' => $kerusakan,
                'kebersihan' => $kebersihan,
                'lainnya' => $lainnya,
                'kategori_kerusakan' => $kerusakan,
                'kategori_kebersihan' => $kebersihan,
                'kategori_lainnya' => $lainnya,
                'kategori' => [
                    'kerusakan' => $kerusakan,
                    'kebersihan' => $kebersihan,
                    'lainnya' => $lainnya,
                ],
                'selesai' => $selesai,
                'status_selesai' => $selesai,
                'belum_selesai' => $belumSelesai,
                'status_belum_selesai' => $belumSelesai,
                'ditolak' => $ditolak,
                'tingkat_penyelesaian' => $tingkatPenyelesaian,
                'persentase_selesai' => $tingkatPenyelesaian,
            ];
        });
    }

    /**
     * Agregasi insiden kerusakan berdasarkan lokasi gedung.
     *
     * @param  Collection<int, array<string, mixed>>  $damageData
     * @return Collection<int, array<string, mixed>>
     */
    public function getLocationDamageData(Collection $damageData): Collection
    {
        return $damageData
            ->groupBy('facility_lokasi')
            ->map(function (Collection $items, string $lokasi) {
                $totalLaporan = (int) $items->sum('total_laporan');
                $selesai = (int) $items->sum('selesai');
                $belumSelesai = (int) $items->sum('belum_selesai');
                $kerusakan = (int) $items->sum('kerusakan');
                $kebersihan = (int) $items->sum('kebersihan');
                $lainnya = (int) $items->sum('lainnya');
                $ditolak = (int) $items->sum('ditolak');
                $tingkatPenyelesaian = $totalLaporan > 0 ? round(($selesai / $totalLaporan) * 100, 1) : 0.0;

                return [
                    'lokasi' => $lokasi,
                    'total_laporan' => $totalLaporan,
                    'total_insiden' => $totalLaporan,
                    'selesai' => $selesai,
                    'status_selesai' => $selesai,
                    'belum_selesai' => $belumSelesai,
                    'status_belum_selesai' => $belumSelesai,
                    'kerusakan' => $kerusakan,
                    'kebersihan' => $kebersihan,
                    'lainnya' => $lainnya,
                    'ditolak' => $ditolak,
                    'tingkat_penyelesaian' => $tingkatPenyelesaian,
                    'persentase_selesai' => $tingkatPenyelesaian,
                    'fasilitas_count' => $items->count(),
                ];
            })
            ->sortByDesc('total_laporan')
            ->values();
    }

    /**
     * Ringkasan kartu metrik kerusakan dan resolusi fasilitas.
     *
     * @param  Collection<int, array<string, mixed>>  $damageData
     * @param  Collection<int, array<string, mixed>>  $locationDamageData
     * @return array<string, mixed>
     */
    public function getDamageSummary(Collection $damageData, Collection $locationDamageData): array
    {
        $totalInsiden = (int) $damageData->sum('total_laporan');
        $totalSelesai = (int) $damageData->sum('selesai');
        $totalBelumSelesai = (int) $damageData->sum('belum_selesai');
        $persentaseResolusi = $totalInsiden > 0 ? round(($totalSelesai / $totalInsiden) * 100, 1) : 0.0;

        $topLocation = $locationDamageData->first(fn ($item) => $item['total_laporan'] > 0);
        $lokasiPalingRawan = $topLocation ? $topLocation['lokasi'] : '-';

        return [
            'total_insiden' => $totalInsiden,
            'total_laporan' => $totalInsiden,
            'total_reports' => $totalInsiden,
            'total_selesai' => $totalSelesai,
            'resolved_reports' => $totalSelesai,
            'total_belum_selesai' => $totalBelumSelesai,
            'persentase_resolusi' => $persentaseResolusi,
            'resolution_rate' => $persentaseResolusi,
            'tingkat_penyelesaian' => $persentaseResolusi,
            'persentase_selesai' => $persentaseResolusi,
            'lokasi_paling_rawan' => $lokasiPalingRawan,
            'lokasi_tersering' => $lokasiPalingRawan,
            'gedung_paling_rawan' => $lokasiPalingRawan,
            'most_damaged_location' => $lokasiPalingRawan,
        ];
    }

    /**
     * Ekspor data rekapitulasi ke format CSV (dengan UTF-8 BOM).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $filterParams = $this->resolveDateFilter($request);

        $occupancyData = $this->getOccupancyData($filterParams['start_date'], $filterParams['end_date']);
        $occupancySummary = $this->getOccupancySummary($occupancyData);

        $damageData = $this->getDamageData($filterParams['start_date'], $filterParams['end_date']);
        $locationDamageData = $this->getLocationDamageData($damageData);
        $damageSummary = $this->getDamageSummary($damageData, $locationDamageData);

        $filename = sprintf('rekap-fasilitas-%s.csv', now()->format('Ymd-His'));

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $periodeLabel = $filterParams['start_date']
            ? Carbon::parse($filterParams['start_date'])->format('d/m/Y').' s/d '.Carbon::parse($filterParams['end_date'])->format('d/m/Y')
            : 'Semua Periode';

        return response()->stream(function () use ($periodeLabel, $occupancyData, $occupancySummary, $damageData, $locationDamageData, $damageSummary, $request) {
            $handle = fopen('php://output', 'w');

            // Output UTF-8 BOM untuk kompatibilitas Microsoft Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // 1. Header info periode & waktu ekspor
            fputcsv($handle, ['LAPORAN REKAPITULASI OKUPANSI FASILITAS & FREKUENSI KERUSAKAN']);
            fputcsv($handle, ['Biro Sarana dan Prasarana Kampus - FacilityHub']);
            fputcsv($handle, ['Periode Filter', $periodeLabel]);
            fputcsv($handle, ['Waktu Ekspor', now()->format('Y-m-d H:i:s')]);
            fputcsv($handle, ['Administrator', $request->user()?->name ?? 'Administrator']);
            fputcsv($handle, []);

            // Ringkasan KPI Eksekutif
            fputcsv($handle, ['RINGKASAN METRIK UTAMA (KPI)']);
            fputcsv($handle, ['Total Jam Pakai', $occupancySummary['total_jam'].' Jam']);
            fputcsv($handle, ['Total Reservasi Disetujui', $occupancySummary['total_reservasi']]);
            fputcsv($handle, ['Fasilitas Teraktif', $occupancySummary['fasilitas_paling_sering']]);
            fputcsv($handle, ['Total Insiden Kerusakan', $damageSummary['total_insiden']]);
            fputcsv($handle, ['Laporan Selesai Ditangani', $damageSummary['total_selesai']]);
            fputcsv($handle, ['Tingkat Resolusi Kerusakan', $damageSummary['persentase_resolusi'].'%']);
            fputcsv($handle, []);

            // 2. Bagian A: Tabel Rekapitulasi Okupansi Fasilitas
            fputcsv($handle, ['BAGIAN A: TABEL REKAPITULASI OKUPANSI FASILITAS']);
            fputcsv($handle, ['No', 'Nama Fasilitas', 'Tipe', 'Lokasi', 'Kapasitas', 'Total Booking', 'Total Jam Pakai', 'Pemesan Teraktif']);
            $noA = 1;
            foreach ($occupancyData as $item) {
                fputcsv($handle, [
                    $noA++,
                    $item['facility_nama'],
                    ucfirst($item['facility_tipe']),
                    $item['facility_lokasi'],
                    $item['facility_kapasitas'],
                    $item['total_reservasi'],
                    $item['total_jam'],
                    $item['pemesan_terbanyak'],
                ]);
            }
            fputcsv($handle, []);

            // 3. Bagian B: Tabel Frekuensi Kerusakan per Fasilitas
            fputcsv($handle, ['BAGIAN B: TABEL FREKUENSI KERUSAKAN PER FASILITAS']);
            fputcsv($handle, ['No', 'Nama Fasilitas', 'Lokasi', 'Total Laporan', 'Fisik', 'Kebersihan', 'Lainnya', 'Selesai', 'Belum Selesai', '% Resolusi']);
            $noB = 1;
            foreach ($damageData as $item) {
                fputcsv($handle, [
                    $noB++,
                    $item['facility_nama'],
                    $item['facility_lokasi'],
                    $item['total_laporan'],
                    $item['kerusakan'],
                    $item['kebersihan'],
                    $item['lainnya'],
                    $item['selesai'],
                    $item['belum_selesai'],
                    $item['tingkat_penyelesaian'].'%',
                ]);
            }
            fputcsv($handle, []);

            // 4. Bagian C: Tabel Kerusakan per Lokasi/Gedung
            fputcsv($handle, ['BAGIAN C: TABEL KERUSAKAN PER LOKASI / GEDUNG']);
            fputcsv($handle, ['No', 'Lokasi Gedung', 'Total Insiden', 'Selesai', '% Resolusi']);
            $noC = 1;
            foreach ($locationDamageData as $item) {
                fputcsv($handle, [
                    $noC++,
                    $item['lokasi'],
                    $item['total_insiden'],
                    $item['selesai'],
                    $item['tingkat_penyelesaian'].'%',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Ekspor data rekapitulasi ke format Excel (Native HTML/XML Table).
     */
    public function exportExcel(Request $request): Response
    {
        $filterParams = $this->resolveDateFilter($request);

        $occupancyData = $this->getOccupancyData($filterParams['start_date'], $filterParams['end_date']);
        $occupancySummary = $this->getOccupancySummary($occupancyData);

        $damageData = $this->getDamageData($filterParams['start_date'], $filterParams['end_date']);
        $locationDamageData = $this->getLocationDamageData($damageData);
        $damageSummary = $this->getDamageSummary($damageData, $locationDamageData);

        $filename = sprintf('rekap-fasilitas-%s.xls', now()->format('Ymd-His'));

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            'Pragma' => 'no-cache',
            'Cache-Control' => 'max-age=0',
            'Expires' => '0',
        ];

        $periodeLabel = $filterParams['start_date']
            ? Carbon::parse($filterParams['start_date'])->format('d/m/Y').' s/d '.Carbon::parse($filterParams['end_date'])->format('d/m/Y')
            : 'Semua Periode';

        return response()->view('admin.rekap.excel', compact(
            'filterParams',
            'periodeLabel',
            'occupancyData',
            'occupancySummary',
            'damageData',
            'locationDamageData',
            'damageSummary'
        ), 200, $headers);
    }

    /**
     * Pratinjau cetak dokumen rekapitulasi (PDF Print Friendly).
     */
    public function print(Request $request): View
    {
        $filterParams = $this->resolveDateFilter($request);

        $occupancyData = $this->getOccupancyData($filterParams['start_date'], $filterParams['end_date']);
        $occupancySummary = $this->getOccupancySummary($occupancyData);

        $damageData = $this->getDamageData($filterParams['start_date'], $filterParams['end_date']);
        $locationDamageData = $this->getLocationDamageData($damageData);
        $damageSummary = $this->getDamageSummary($damageData, $locationDamageData);

        $periodeLabel = $filterParams['start_date']
            ? Carbon::parse($filterParams['start_date'])->format('d/m/Y').' — '.Carbon::parse($filterParams['end_date'])->format('d/m/Y')
            : 'Semua Periode';

        return view('admin.rekap.print', compact(
            'filterParams',
            'periodeLabel',
            'occupancyData',
            'occupancySummary',
            'damageData',
            'locationDamageData',
            'damageSummary'
        ));
    }
}
