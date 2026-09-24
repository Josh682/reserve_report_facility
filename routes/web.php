<?php

use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
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

Route::view('/facilities', 'facilities')->name('facilities');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
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
        $stats = [
            'total' => Facility::count(),
            'aktif' => Facility::where('status', 'aktif')->count(),
            'dalam_perbaikan' => Facility::where('status', 'dalam_perbaikan')->count(),
            'nonaktif' => Facility::where('status', 'nonaktif')->count(),
            'pending_users' => User::where('status_akun', 'pending')->count(),
            'total_users' => User::count(),
        ];

        return view('admin.dashboard', compact('stats'));
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
