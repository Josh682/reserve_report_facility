<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Tampilkan halaman reservasi pengguna beserta riwayat peminjamannya.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $reservations = Reservation::with(['facility'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $facilities = Facility::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $selectedFacilityId = $request->query('facility_id');
        $openForm = $request->boolean('open_form') || $request->filled('facility_id');

        return view('reservation', compact('reservations', 'facilities', 'selectedFacilityId', 'openForm'));
    }

    /**
     * Simpan pengajuan reservasi baru dari pengguna (US 3 & US 4).
     */
    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $facility = Facility::findOrFail($validated['facility_id']);

        // Verifikasi fasilitas aktif
        if ($facility->status !== 'aktif') {
            return back()
                ->withInput()
                ->withErrors(['facility_id' => 'Fasilitas ini sedang dalam pemeliharaan atau nonaktif dan tidak dapat dipinjam.']);
        }

        // Cek konflik bentrok jadwal dengan reservasi lain yang sudah disetujui (US 4)
        $hasConflict = Reservation::where('facility_id', $facility->id)
            ->where('status', 'approved')
            ->whereDate('tanggal', $validated['tanggal'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($hasConflict) {
            return back()
                ->withInput()
                ->withErrors(['conflict' => 'Jadwal peminjaman bentrok dengan jadwal lain yang sudah disetujui pada fasilitas ini. Silakan periksa jam ketersediaan di katalog.']);
        }

        Reservation::create([
            'user_id' => $request->user()->id,
            'facility_id' => $facility->id,
            'tanggal' => $validated['tanggal'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'tujuan_penggunaan' => $validated['tujuan_penggunaan'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('reservation')
            ->with('status', 'Pengajuan peminjaman fasilitas berhasil dikirim! Silakan menunggu verifikasi dari petugas.');
    }

    /**
     * Batalkan reservasi mandiri oleh pemohon (maksimal H-1, US 4 & ASSUMPTION.md 1.2).
     */
    public function cancel(Request $request, Reservation $reservation): RedirectResponse
    {
        if ($reservation->user_id !== $request->user()->id) {
            abort(403, 'Anda tidak berhak membatalkan reservasi ini.');
        }

        if (! in_array($reservation->status, ['pending', 'approved'], true)) {
            return back()->with('status_error', 'Hanya pengajuan peminjaman berstatus menunggu atau disetujui yang dapat dibatalkan.');
        }

        // Pengecekan batas H-1: tanggal reservasi harus lebih besar dari hari ini
        if ($reservation->tanggal->toDateString() <= now()->toDateString()) {
            return back()->with('status_error', 'Pembatalan mandiri hanya diizinkan maksimal H-1 sebelum tanggal penggunaan fasilitas. Silakan hubungi petugas untuk kendala mendadak.');
        }

        $reservation->update([
            'status' => 'cancelled',
            'cancelled_reason' => 'Dibatalkan langsung oleh pemohon (H-1).',
        ]);

        return redirect()
            ->route('reservation')
            ->with('status', 'Pengajuan peminjaman berhasil dibatalkan.');
    }
}
