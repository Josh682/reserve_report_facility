<?php

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

test('login validation passes with valid email and password', function () {
    $request = new LoginRequest;
    $rules = $request->rules();

    $validator = Validator::make([
        'email' => 'mahasiswa@kampus.test',
        'password' => 'password123',
    ], $rules);

    expect($validator->passes())->toBeTrue();
});

test('login validation fails when email or password is empty or invalid format', function () {
    $request = new LoginRequest;
    $rules = $request->rules();

    $validator = Validator::make([
        'email' => 'not-an-email',
        'password' => '',
    ], $rules);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue()
        ->and($validator->errors()->has('password'))->toBeTrue();
});

test('guest can view login page', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Masuk ke Sistem');
});

test('verified user can login with valid credentials and redirects based on role', function (string $role, string $expectedRoute) {
    $user = User::factory()->create([
        'email' => "{$role}@kampus.test",
        'password' => bcrypt('password123'),
        'role' => $role,
        'status_akun' => 'verified',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect($expectedRoute);
})->with([
    ['admin', '/admin/dashboard'],
    ['petugas', '/petugas/dashboard'],
    ['pengguna', '/'],
]);

test('pending user cannot login and sees pending status warning', function () {
    $user = User::factory()->create([
        'email' => 'pending@kampus.test',
        'password' => bcrypt('password123'),
        'role' => 'pengguna',
        'status_akun' => 'pending',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('status_warning', 'Akun Anda sedang menunggu verifikasi oleh Admin sebelum dapat digunakan.');
});

test('rejected user cannot login and sees rejection error', function () {
    $user = User::factory()->create([
        'email' => 'rejected@kampus.test',
        'password' => bcrypt('password123'),
        'role' => 'pengguna',
        'status_akun' => 'rejected',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('status_error', 'Akun Anda telah ditolak oleh Admin. Silakan hubungi bagian administrasi.');
});

test('login fails with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'user@kampus.test',
        'password' => bcrypt('password123'),
        'status_akun' => 'verified',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('authenticated user can logout and session is cleared', function () {
    $user = User::factory()->create([
        'status_akun' => 'verified',
    ]);

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('status', 'Anda telah berhasil keluar dari sistem.');
});
