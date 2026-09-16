<?php

use App\Models\User;

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
