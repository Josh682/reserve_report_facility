<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::view('/', 'login');

Route::view('/login', 'login')->name('login');

Route::post('/login', function (Request $request) {
    return redirect('/facilities');
});

Route::view('/facilities', 'facilities')->name('facilities');

Route::view('/reservation', 'reservation')->name('reservation');

Route::view('/report', 'report')->name('report');

Route::view('/admin', 'admin')->name('admin');

Route::view('/petugas', 'petugas')->name('petugas');