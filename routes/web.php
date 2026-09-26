<?php

use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PublicFacilityController;
use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        /** @var User $user */
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            'pengguna' => redirect()->route('pengguna.dashboard'),
            default => redirect()->route('login'),
        };
    }

    return redirect()->route('login');
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::get('/facilities', [PublicFacilityController::class, 'index'])->name('facilities');
Route::get('/facilities/{facility}/schedule', [PublicFacilityController::class, 'schedule'])->name('facilities.schedule');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        /** @var User $user */
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            'pengguna' => redirect()->route('pengguna.dashboard'),
            default => redirect()->route('login'),
        };
    })->name('dashboard');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::view('/reservation', 'reservation')->name('reservation');
    Route::view('/report', 'report')->name('report');

    Route::post('/reservations', function () {
        return redirect()->route('reservation')->with('status', 'Pengajuan reservasi berhasil dikirim dan menunggu persetujuan petugas.');
    });

    Route::post('/reports', function () {
        return redirect()->route('report')->with('status', 'Laporan kerusakan fasilitas berhasil dikirim dan menunggu tindak lanjut teknisi.');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $facilityCount = Facility::count();
        $aktifFacilityCount = Facility::where('status', 'aktif')->count();
        $repairFacilityCount = Facility::where('status', 'dalam_perbaikan')->count();
        $inactiveFacilityCount = Facility::where('status', 'nonaktif')->count();
        $pendingUsersCount = User::where('status_akun', 'pending')->count();
        $totalUsersCount = User::count();

        // Reservasi statistik aktual
        $totalReservations = Reservation::count();
        $approvedReservations = Reservation::where('status', 'approved')->count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $rejectedReservations = Reservation::where('status', 'rejected')->count();

        // Laporan kerusakan aktual
        $newReportsCount = DB::table('reports')->where('status', 'baru')->count();
        $inProgressReportsCount = DB::table('reports')->where('status', 'diproses')->count();
        $resolvedReportsCount = DB::table('reports')->where('status', 'selesai')->count();

        // Okupansi / Rasio Kesiapan Fasilitas Operasional
        $occupancyRate = $facilityCount > 0 ? round(($aktifFacilityCount / $facilityCount) * 100) : 0;

        $stats = [
            'total' => $facilityCount,
            'aktif' => $aktifFacilityCount,
            'dalam_perbaikan' => $repairFacilityCount,
            'nonaktif' => $inactiveFacilityCount,
            'pending_users' => $pendingUsersCount,
            'total_users' => $totalUsersCount,
            'total_reservations' => $totalReservations,
            'approved_reservations' => $approvedReservations,
            'pending_reservations' => $pendingReservations,
            'rejected_reservations' => $rejectedReservations,
            'new_reports' => $newReportsCount,
            'in_progress_reports' => $inProgressReportsCount,
            'resolved_reports' => $resolvedReportsCount,
            'occupancy_rate' => $occupancyRate,
        ];

        // Upcoming major event / reservation aktual
        $upcomingReservation = Reservation::with(['facility', 'user'])
            ->where('tanggal', '>=', now()->toDateString())
            ->where('status', 'approved')
            ->orderBy('tanggal')
            ->orderBy('start_time')
            ->first();

        if (! $upcomingReservation) {
            $upcomingReservation = Reservation::with(['facility', 'user'])
                ->orderBy('tanggal', 'desc')
                ->first();
        }

        // Fasilitas utama aktual dari database (maksimal 5 item teratas)
        $mainFacilities = Facility::orderByRaw("CASE WHEN status = 'aktif' THEN 1 WHEN status = 'dalam_perbaikan' THEN 2 ELSE 3 END")
            ->orderBy('kapasitas', 'desc')
            ->take(5)
            ->get();

        // Notifikasi aktual
        $recentPendingUsers = User::where('status_akun', 'pending')->latest()->take(3)->get();
        $recentPendingReservations = Reservation::with(['facility', 'user'])->where('status', 'pending')->latest()->take(3)->get();
        $recentReports = DB::table('reports')
            ->join('facilities', 'reports.facility_id', '=', 'facilities.id')
            ->select('reports.*', 'facilities.nama as facility_nama')
            ->where('reports.status', 'baru')
            ->latest('reports.created_at')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'upcomingReservation',
            'mainFacilities',
            'recentPendingUsers',
            'recentPendingReservations',
            'recentReports'
        ));
    })->name('dashboard');

    Route::patch('/facilities/{facility}/status', [FacilityController::class, 'updateStatus'])
        ->name('facilities.status');
    Route::resource('facilities', FacilityController::class);

    Route::patch('/users/{user}/approve', [UserController::class, 'approve'])
        ->name('users.approve');
    Route::patch('/users/{user}/reject', [UserController::class, 'reject'])
        ->name('users.reject');
    Route::resource('users', UserController::class)
        ->only(['index', 'create', 'store']);
});

Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'total_facilities' => Facility::count(),
            'aktif' => Facility::where('status', 'aktif')->count(),
            'dalam_perbaikan' => Facility::where('status', 'dalam_perbaikan')->count(),
        ];

        return view('petugas.dashboard', compact('stats'));
    })->name('dashboard');
});

Route::middleware(['auth', 'role:pengguna'])->prefix('pengguna')->name('pengguna.')->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'total_facilities' => Facility::count(),
            'aktif' => Facility::where('status', 'aktif')->count(),
            'dalam_perbaikan' => Facility::where('status', 'dalam_perbaikan')->count(),
        ];

        return view('pengguna.dashboard', compact('stats'));
    })->name('dashboard');
});
