<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Petugas\UpdateReportStatusRequest;
use App\Models\Facility;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Tampilkan antrean dan riwayat laporan kerusakan fasilitas untuk petugas (US 8).
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'baru');
        $search = $request->query('search');
        $categoryFilter = $request->query('category');
        $facilityId = $request->query('facility_id');

        // Statistik antrean untuk counter badge status
        $counts = [
            'baru' => Report::where('status', 'baru')->count(),
            'diproses' => Report::where('status', 'diproses')->count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'ditolak' => Report::where('status', 'ditolak')->count(),
            'total' => Report::count(),
        ];

        $query = Report::with(['facility', 'user', 'resolver']);

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($categoryFilter && $categoryFilter !== 'all') {
            $query->where('kategori', $categoryFilter);
        }

        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('facility', function ($fq) use ($search) {
                    $fq->where('nama', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%");
                })->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('catatan_resolusi', 'like', "%{$search}%");
            });
        }

        $reports = $query->latest()
            ->paginate(12)
            ->withQueryString();

        $facilities = Facility::orderBy('nama')->get();

        return view('petugas.laporan.index', compact(
            'reports',
            'counts',
            'statusFilter',
            'categoryFilter',
            'facilityId',
            'search',
            'facilities'
        ));
    }

    /**
     * Perbarui status laporan fasilitas dan resolusi perbaikan (US 11 & US 12).
     */
    public function update(UpdateReportStatusRequest $request, Report $report): RedirectResponse
    {
        $report->update([
            'status' => $request->status,
            'catatan_resolusi' => $request->catatan_resolusi,
            'resolved_by' => auth()->id(),
        ]);

        // US 12: Opsional sinkronisasi status operasional fasilitas (misal: dalam_perbaikan saat diproses atau aktif kembali)
        if ($request->filled('mark_facility_status')) {
            $report->facility->update([
                'status' => $request->mark_facility_status,
            ]);
        }

        $facilityName = $report->facility ? $report->facility->nama : 'Fasilitas';

        return back()->with('status', "Laporan kendala #{$report->id} pada {$facilityName} berhasil diperbarui menjadi status '{$report->status}'.");
    }
}
