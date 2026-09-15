<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Admin utama
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@kampus.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'tipe_pengguna' => null, 
            'status_akun' => 'verified', 
            'email_verified_at' => now(),
        ]);
 
        // Petugas
        User::create([
            'name' => 'Petugas Fasilitas',
            'email' => 'petugas@kampus.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'tipe_pengguna' => null,
            'status_akun' => 'verified',
            'email_verified_at' => now(),
        ]);
        // Pengguna (Mahasiswa)
        User::create([
            'name' => 'Mahasiswa Contoh',
            'email' => 'mahasiswa@kampus.test',
            'password' => Hash::make('password'),
            'role' => 'pengguna',
            'tipe_pengguna' => 'mahasiswa',
            'status_akun' => 'verified', 
            'email_verified_at' => now(),
        ]);
 
        // Calon pengguna (status_akun = pending)
        User::create([
            'name' => 'Calon Pengguna (Belum Diverifikasi)',
            'email' => 'pending@kampus.test',
            'password' => Hash::make('password'),
            'role' => 'pengguna',
            'tipe_pengguna' => 'mahasiswa',
            'status_akun' => 'pending',
            'email_verified_at' => null,
        ]);

    }
}
