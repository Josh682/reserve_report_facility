<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Tampilkan antrean permohonan reservasi fasilitas untuk petugas (US 9 & US 10).
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'pending');
        $search = $request->query('search');
        $facilityId = $request->query('facility_id');
        $dateFilter = $request->query('tanggal');

        // Statistik antrean untuk counter badge
        $counts = [
            'pending' => Reservation::where('status', 'pending')->count(),
            'approved' => Reservation::where('status', 'approved')->count(),
            'rejected' => Reservation::where('status', 'rejected')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
            'total' => Reservation::count(),
        ];

        $query = Reservation::with(['facility', 'user', 'processor']);

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('facility', function ($fq) use ($search) {
                    $fq->where('nama', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%");
                })->orWhere('tujuan_penggunaan', 'like', "%{$search}%");
            });
        }

        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }

        if ($dateFilter) {
            $query->whereDate('tanggal', $dateFilter);
        }

        // Urutan: jika filter pending, dahulukan akun Dosen (Prioritas Kampus US 27), lalu tanggal terdekat
        $reservations = $query
            ->join('users', 'reservations.user_id', '=', 'users.id')
            ->select('reservations.*')
            ->orderByRaw("CASE WHEN users.tipe_pengguna = 'dosen' THEN 0 ELSE 1 END")
            ->orderBy('reservations.tanggal', 'asc')
            ->orderBy('reservations.start_time', 'asc')
            ->paginate(12)
            ->withQueryString();

        $facilities = Facility::where('status', 'aktif')->orderBy('nama')->get();

        return view('petugas.reservasi.index', compact(
            'reservations',
            'counts',
            'statusFilter',
            'search',
            'facilityId',
            'dateFilter',
            'facilities'
        ));
    }

    /**
     * Persetujuan manual permohonan reservasi dengan proteksi bentrok jadwal (US 9).
     */
    public function approve(Request $request, Reservation $reservation): RedirectResponse
    {
        if ($reservation->status !== 'pending') {
            return back()->with('status_error', 'Hanya permohonan reservasi berstatus menunggu (pending) yang dapat disetujui.');
        }

        $facility = $reservation->facility;
        if (! $facility || $facility->status !== 'aktif') {
            return back()->with('status_error', 'Fasilitas saat ini tidak aktif atau sedang dalam perbaikan, tidak dapat menyetujui jadwal.');
        }

        try {
            DB::transaction(function () use ($reservation) {
                // Cek apakah ada reservasi approved lain yang bentrok
                $conflict = Reservation::where('facility_id', $reservation->facility_id)
                    ->where('status', 'approved')
                    ->whereDate('tanggal', $reservation->tanggal)
                    ->where('id', '!=', $reservation->id)
                    ->where(function ($query) use ($reservation) {
                        $query->where('start_time', '<', $reservation->end_time)
                            ->where('end_time', '>', $reservation->start_time);
                    })
                    ->lockForUpdate()
                    ->first();

                if ($conflict) {
                    throw new \RuntimeException("Jadwal bentrok dengan reservasi yang telah disetujui sebelumnya (#{$conflict->id} pukul ".substr($conflict->start_time, 0, 5).' - '.substr($conflict->end_time, 0, 5).').');
                }

                // Setujui reservasi saat ini
                $reservation->update([
                    'status' => 'approved',
                    'processed_by' => auth()->id(),
                ]);

                // Auto-reject permohonan pending lain yang tumpang tindih waktu (US 9)
                Reservation::where('facility_id', $reservation->facility_id)
                    ->where('status', 'pending')
                    ->whereDate('tanggal', $reservation->tanggal)
                    ->where('id', '!=', $reservation->id)
                    ->where(function ($query) use ($reservation) {
                        $query->where('start_time', '<', $reservation->end_time)
                            ->where('end_time', '>', $reservation->start_time);
                    })
                    ->update([
                        'status' => 'rejected',
                        'processed_by' => auth()->id(),
                        'cancelled_reason' => 'Ditolak otomatis oleh sistem karena jadwal bentrok dengan permohonan reservasi lain yang disetujui petugas.',
                    ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('status_error', $e->getMessage());
        }

        return back()->with('status', "Permohonan reservasi #{$reservation->id} oleh {$reservation->user->name} berhasil disetujui.");
    }

    /**
     * Penolakan permohonan reservasi oleh petugas (US 9).
     */
    public function reject(Request $request, Reservation $reservation): RedirectResponse
    {
        if ($reservation->status !== 'pending') {
            return back()->with('status_error', 'Hanya permohonan reservasi berstatus menunggu (pending) yang dapat ditolak.');
        }

        $validated = $request->validate([
            'alasan' => ['nullable', 'string', 'max:500'],
        ]);

        $reason = ! empty($validated['alasan'])
            ? $validated['alasan']
            : 'Ditolak oleh petugas fasilitas.';

        $reservation->update([
            'status' => 'rejected',
            'processed_by' => auth()->id(),
            'cancelled_reason' => $reason,
        ]);

        return back()->with('status', "Permohonan reservasi #{$reservation->id} berhasil ditolak.");
    }

    /**
     * Pembatalan darurat untuk reservasi yang sudah approved (US 10).
     */
    public function emergencyCancel(Request $request, Reservation $reservation): RedirectResponse
    {
        if ($reservation->status !== 'approved') {
            return back()->with('status_error', 'Hanya reservasi yang telah disetujui (approved) yang dapat dibatalkan secara darurat.');
        }

        $validated = $request->validate([
            'cancelled_reason' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'cancelled_reason.required' => 'Alasan pembatalan darurat wajib diisi.',
            'cancelled_reason.min' => 'Alasan pembatalan darurat wajib diisi minimal 10 karakter untuk keperluan audit dan transparansi.',
        ]);

        $reservation->update([
            'status' => 'cancelled',
            'processed_by' => auth()->id(),
            'cancelled_reason' => '[Pembatalan Darurat Petugas] '.$validated['cancelled_reason'],
        ]);

        return back()->with('status', "Reservasi disetujui #{$reservation->id} telah dibatalkan secara darurat.");
    }
}
