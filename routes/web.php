<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
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

    // Placeholder sementara untuk tujuan redirect role di Tahap 1
    Route::get('/admin/dashboard', function () {
        return 'Admin Dashboard Placeholder';
    })->name('admin.dashboard');

    Route::get('/petugas/dashboard', function () {
        return 'Petugas Dashboard Placeholder';
    })->name('petugas.dashboard');
});
