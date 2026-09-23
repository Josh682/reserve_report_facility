<?php

use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
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
        return 'Petugas Dashboard Placeholder';
    })->name('dashboard');
});
