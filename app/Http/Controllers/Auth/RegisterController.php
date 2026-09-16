<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the application registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'tipe_pengguna' => $request->validated('tipe_pengguna'),
            'password' => Hash::make($request->validated('password')),
            'role' => 'pengguna',
            'status_akun' => 'pending',
        ]);

        return redirect()->route('login')->with(
            'status',
            'Pendaftaran akun berhasil! Akun Anda sedang menunggu verifikasi oleh Admin sebelum dapat digunakan untuk login.'
        );
    }
}
