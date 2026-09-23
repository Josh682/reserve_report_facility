<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the application login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        // Cek status akun sesuai aturan ASSUMPTION.md
        if ($user->status_akun === 'pending') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with(
                'status_warning',
                'Akun Anda sedang menunggu verifikasi oleh Admin sebelum dapat digunakan.'
            );
        }

        if ($user->status_akun === 'rejected') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with(
                'status_error',
                'Akun Anda telah ditolak oleh Admin. Silakan hubungi bagian administrasi.'
            );
        }

        $request->session()->regenerate();

        // Redirect berbasis role
        return match ($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'petugas' => redirect()->intended(route('petugas.dashboard')),
            'pengguna' => redirect()->intended(route('pengguna.dashboard')),
            default => redirect()->intended(route('login')),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with(
            'status',
            'Anda telah berhasil keluar dari sistem.'
        );
    }
}
