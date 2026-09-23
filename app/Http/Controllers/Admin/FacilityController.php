<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FacilityRequest;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Facility::query();

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->query('tipe'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $facilities = $query->latest()->paginate(10)->withQueryString();

        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.facilities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FacilityRequest $request): RedirectResponse
    {
        Facility::create($request->validated());

        return redirect()->route('admin.facilities.index')->with(
            'status',
            'Fasilitas baru berhasil ditambahkan ke dalam sistem.'
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility): View
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FacilityRequest $request, Facility $facility): RedirectResponse
    {
        $facility->update($request->validated());

        return redirect()->route('admin.facilities.index')->with(
            'status',
            'Data fasilitas berhasil diperbarui.'
        );
    }

    /**
     * Quick status toggle.
     */
    public function updateStatus(Request $request, Facility $facility): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:aktif,dalam_perbaikan,nonaktif'],
        ]);

        $facility->update(['status' => $validated['status']]);

        return back()->with('status', "Status fasilitas '{$facility->nama}' berhasil diubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility): RedirectResponse
    {
        $hasReservations = DB::table('reservations')->where('facility_id', $facility->id)->exists();

        if ($hasReservations) {
            return back()->with(
                'status_error',
                'Fasilitas ini sudah memiliki riwayat reservasi dan tidak boleh dihapus secara permanen. Anda dapat mengubah statusnya menjadi "Nonaktif".'
            );
        }

        $facility->delete();

        return redirect()->route('admin.facilities.index')->with(
            'status',
            'Fasilitas berhasil dihapus dari sistem.'
        );
    }
}
