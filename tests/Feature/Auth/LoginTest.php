<?php

use App\Http\Requests\Auth\LoginRequest;
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
