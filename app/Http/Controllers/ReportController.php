<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Facility;
use App\Models\Report;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    /**
     * Display a listing of user reports and the submission form.
     */
    public function index(): View
    {
        $facilities = Facility::where('status', '!=', 'nonaktif')
            ->orderBy('nama')
            ->get();

        $myReports = Report::where('user_id', auth()->id())
            ->with(['facility', 'resolver'])
            ->latest()
            ->paginate(10);

        return view('report', compact('facilities', 'myReports'));
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
