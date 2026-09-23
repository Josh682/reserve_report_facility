<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'tipe_pengguna' => null,
            'status_akun' => 'verified',
        ]);
    }

    public function petugas(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'petugas',
            'tipe_pengguna' => null,
            'status_akun' => 'verified',
        ]);
    }

    public function pengguna(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'pengguna',
            'tipe_pengguna' => 'mahasiswa',
            'status_akun' => 'pending',
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_akun' => 'pending',
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_akun' => 'verified',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_akun' => 'rejected',
        ]);
    }

    public function mahasiswa(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe_pengguna' => 'mahasiswa',
        ]);
    }

    public function dosen(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe_pengguna' => 'dosen',
        ]);
    }

    public function staf(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe_pengguna' => 'staf',
        ]);
    }
}
