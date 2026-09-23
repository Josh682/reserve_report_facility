<?php

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

test('user model can mass assign role, tipe_pengguna, and status_akun', function () {
    $user = new User([
        'name' => 'Test User',
        'email' => 'test@kampus.test',
        'password' => 'secret123',
        'role' => 'pengguna',
        'tipe_pengguna' => 'mahasiswa',
        'status_akun' => 'pending',
    ]);

    expect($user->role)->toBe('pengguna')
        ->and($user->tipe_pengguna)->toBe('mahasiswa')
        ->and($user->status_akun)->toBe('pending');
});

test('registration validation passes with valid data', function () {
    $request = new RegisterRequest;
    $rules = $request->rules();

    $validator = Validator::make([
        'name' => 'Menza Isaiah',
        'email' => 'menza@mahasiswa.test',
        'tipe_pengguna' => 'mahasiswa',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ], $rules);

    expect($validator->passes())->toBeTrue();
});

test('registration validation fails if required fields are missing', function () {
    $request = new RegisterRequest;
    $rules = $request->rules();

    $validator = Validator::make([], $rules);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue()
        ->and($validator->errors()->has('tipe_pengguna'))->toBeTrue()
        ->and($validator->errors()->has('password'))->toBeTrue();
});

test('registration validation fails if tipe_pengguna is invalid', function () {
    $request = new RegisterRequest;
    $rules = $request->rules();

    $validator = Validator::make([
        'name' => 'Menza Isaiah',
        'email' => 'menza@mahasiswa.test',
        'tipe_pengguna' => 'rektor',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ], $rules);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('tipe_pengguna'))->toBeTrue();
});

test('registration validation fails if password is less than 8 chars or confirmation mismatch', function () {
    $request = new RegisterRequest;
    $rules = $request->rules();

    $validator = Validator::make([
        'name' => 'Menza Isaiah',
        'email' => 'menza@mahasiswa.test',
        'tipe_pengguna' => 'mahasiswa',
        'password' => 'short',
        'password_confirmation' => 'mismatch',
    ], $rules);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('password'))->toBeTrue();
});

test('guest can view registration page', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
    $response->assertSee('Daftar Akun');
});

test('guest can register with valid data and receives pending status', function () {
    $userData = [
        'name' => 'Menza Isaiah',
        'email' => 'menza@mahasiswa.test',
        'tipe_pengguna' => 'mahasiswa',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->post('/register', $userData);

    $this->assertDatabaseHas('users', [
        'name' => 'Menza Isaiah',
        'email' => 'menza@mahasiswa.test',
        'role' => 'pengguna',
        'tipe_pengguna' => 'mahasiswa',
        'status_akun' => 'pending',
    ]);

    $this->assertGuest();
    $response->assertRedirect('/login');
    $response->assertSessionHas('status');
});

test('registration fails and redirects back with session errors on invalid input', function () {
    $response = $this->post('/register', [
        'name' => '',
        'email' => 'not-an-email',
        'tipe_pengguna' => 'invalid-role',
        'password' => '123',
        'password_confirmation' => '456',
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'tipe_pengguna', 'password']);
    $this->assertDatabaseMissing('users', [
        'email' => 'not-an-email',
    ]);
});
