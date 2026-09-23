<?php

use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

test('store user request passes with valid petugas data', function () {
    $data = [
        'name' => 'Petugas Fasilitas Baru',
        'email' => 'petugas@kampus.ac.id',
        'role' => 'petugas',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $request = new StoreUserRequest;
    $validator = Validator::make($data, $request->rules(), $request->messages());

    expect($validator->passes())->toBeTrue();
});

test('store user request passes with valid pengguna mahasiswa data', function () {
    $data = [
        'name' => 'Mahasiswa Baru',
        'email' => 'mhs@kampus.ac.id',
        'role' => 'pengguna',
        'tipe_pengguna' => 'mahasiswa',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $request = new StoreUserRequest;
    $validator = Validator::make($data, $request->rules(), $request->messages());

    expect($validator->passes())->toBeTrue();
});

test('store user request fails when pengguna does not provide tipe_pengguna', function () {
    $data = [
        'name' => 'Mahasiswa Tanpa Tipe',
        'email' => 'mhs@kampus.ac.id',
        'role' => 'pengguna',
        'tipe_pengguna' => null,
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $request = new StoreUserRequest;
    $validator = Validator::make($data, $request->rules(), $request->messages());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('tipe_pengguna'))->toBeTrue()
        ->and($validator->errors()->first('tipe_pengguna'))->toBe('Tipe pengguna wajib dipilih jika role adalah pengguna.');
});

test('store user request fails when email is already registered', function () {
    User::factory()->verified()->create(['email' => 'existing@kampus.ac.id']);

    $data = [
        'name' => 'User Duplikat',
        'email' => 'existing@kampus.ac.id',
        'role' => 'petugas',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $request = new StoreUserRequest;
    $validator = Validator::make($data, $request->rules(), $request->messages());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue()
        ->and($validator->errors()->first('email'))->toBe('Alamat email sudah terdaftar dalam sistem.');
});
