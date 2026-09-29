<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Facility;
use App\Models\Report;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of user reports and the submission form.
     */
    public function index(Request $request): View
    {
        $facilities = Facility::where('status', '!=', 'nonaktif')
            ->orderBy('nama')
            ->get();

        $statusCounts = Report::where('user_id', auth()->id())
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $counts = [
            'all' => (int) $statusCounts->sum(),
            'baru' => (int) ($statusCounts['baru'] ?? 0),
            'diproses' => (int) ($statusCounts['diproses'] ?? 0),
            'selesai' => (int) ($statusCounts['selesai'] ?? 0),
            'ditolak' => (int) ($statusCounts['ditolak'] ?? 0),
        ];

        $reportsQuery = Report::where('user_id', auth()->id())
            ->with(['facility', 'resolver']);

        if (in_array($request->query('status'), ['baru', 'diproses', 'selesai', 'ditolak'], true)) {
            $reportsQuery->where('status', $request->query('status'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $reportsQuery->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('facility', function ($fq) use ($search) {
                        $fq->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $myReports = $reportsQuery->latest()
            ->paginate(10)
            ->withQueryString();

        return view('report', compact('facilities', 'myReports', 'counts'));
    }

    /**
     * Store a newly created report in storage.
     */
    public function store(StoreReportRequest $request): RedirectResponse
    {
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reports', 'public');
        }

        Report::create([
            'user_id' => (int) auth()->id(),
            'facility_id' => (int) $request->facility_id,
            'kategori' => $request->category,
            'deskripsi' => $request->description,
            'foto_path' => $photoPath,
            'status' => 'baru',
        ]);

        return redirect()->route('report')->with('status', 'Laporan kerusakan fasilitas berhasil dikirim dan menunggu tindak lanjut teknisi.');
    }
}
