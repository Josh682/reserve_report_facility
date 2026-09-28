<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

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
     * Ekspor data rekapitulasi ke format CSV.
     */
    public function exportCsv(Request $request): Response
    {
        return response('Export CSV');
    }

    /**
     * Ekspor data rekapitulasi ke format Excel.
     */
    public function exportExcel(Request $request): Response
    {
        return response('Export Excel');
    }

    /**
     * Pratinjau cetak dokumen rekapitulasi.
     */
    public function print(Request $request): Response
    {
        return response('Print Preview');
    }
}
