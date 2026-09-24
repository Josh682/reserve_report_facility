<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PublicFacilityController extends Controller
{
    /**
     * Menampilkan katalog fasilitas dan status ketersediaannya untuk publik/pengunjung (US 1 & US 2).
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $tipe = $request->query('tipe');
        $lokasi = $request->query('lokasi');
        $kapasitas = $request->query('kapasitas');
        $tanggal = $request->query('tanggal');

        try {
            $selectedDate = $tanggal ? Carbon::parse($tanggal)->toDateString() : now()->toDateString();
        } catch (\Throwable) {
            $selectedDate = now()->toDateString();
        }

        // Summary statistics untuk widget atas
        $stats = [
            'total' => Facility::count(),
            'aktif' => Facility::where('status', 'aktif')->count(),
            'dalam_perbaikan' => Facility::where('status', 'dalam_perbaikan')->count(),
            'nonaktif' => Facility::where('status', 'nonaktif')->count(),
        ];

        // Query fasilitas dengan filter pencarian (US 2)
        $facilitiesQuery = Facility::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->when($tipe && $tipe !== 'Semua Tipe', function ($query) use ($tipe) {
                $query->where('tipe', $tipe);
            })
            ->when($lokasi && $lokasi !== 'Semua Lokasi', function ($query) use ($lokasi) {
                $query->where('lokasi', 'like', "%{$lokasi}%");
            })
            ->when($kapasitas && $kapasitas !== 'Semua Kapasitas', function ($query) use ($kapasitas) {
                if ($kapasitas === '< 30 orang' || $kapasitas === 'small') {
                    $query->where('kapasitas', '<', 30);
                } elseif ($kapasitas === '30 - 50 orang' || $kapasitas === 'medium') {
                    $query->whereBetween('kapasitas', [30, 50]);
                } elseif ($kapasitas === '> 50 orang' || $kapasitas === 'large') {
                    $query->where('kapasitas', '>', 50);
                } elseif (is_numeric($kapasitas)) {
                    $query->where('kapasitas', '>=', (int) $kapasitas);
                }
            })
            ->with(['reservations' => function ($q) use ($selectedDate) {
                $q->where('status', 'approved')->whereDate('tanggal', $selectedDate);
            }])
            ->orderByRaw("CASE status WHEN 'aktif' THEN 1 WHEN 'dalam_perbaikan' THEN 2 WHEN 'nonaktif' THEN 3 ELSE 4 END")
            ->orderBy('nama');

        $facilities = $facilitiesQuery->paginate(9)->withQueryString();

        // Hitung status ketersediaan slot untuk tiap fasilitas (US 1)
        $facilities->getCollection()->transform(function (Facility $facility) use ($selectedDate) {
            $facility->schedule_slots = $facility->getScheduleForDate($selectedDate);

            // Hitung ketersediaan slot hari ini
            $availableSlots = array_filter($facility->schedule_slots, fn ($s) => $s['is_available']);
            $facility->total_slots = count($facility->schedule_slots);
            $facility->available_slots_count = count($availableSlots);
            $facility->is_fully_booked = ($facility->status === 'aktif' && empty($availableSlots));

            // Slot pertama yang masih tersedia
            $firstAvailable = reset($availableSlots);
            $facility->next_available_slot = $firstAvailable ? $firstAvailable['label'] : null;

            return $facility;
        });

        // Daftar lokasi unik untuk opsi dropdown filter
        $availableLocations = Facility::select('lokasi')->distinct()->pluck('lokasi');

        return view('facilities', compact(
            'facilities',
            'stats',
            'selectedDate',
            'search',
            'tipe',
            'lokasi',
            'kapasitas',
            'availableLocations'
        ));
    }

    /**
     * Endpoint API JSON untuk melihat ketersediaan 26 slot waktu fasilitas tertentu (US 1).
     * Tanpa memuat informasi pemohon atau tujuan reservasi (privasi).
     */
    public function schedule(Facility $facility, Request $request): JsonResponse
    {
        $tanggal = $request->query('date', now()->toDateString());

        try {
            $selectedDate = Carbon::parse($tanggal)->toDateString();
        } catch (\Throwable) {
            $selectedDate = now()->toDateString();
        }

        $slots = $facility->getScheduleForDate($selectedDate);

        return response()->json([
            'facility' => [
                'id' => $facility->id,
                'nama' => $facility->nama,
                'tipe' => $facility->tipe,
                'lokasi' => $facility->lokasi,
                'kapasitas' => $facility->kapasitas,
                'status' => $facility->status,
            ],
            'date' => $selectedDate,
            'total_slots' => count($slots),
            'available_slots' => count(array_filter($slots, fn ($s) => $s['is_available'])),
            'slots' => $slots,
        ]);
    }
}
