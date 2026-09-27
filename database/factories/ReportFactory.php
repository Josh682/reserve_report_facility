<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->pengguna()->verified(),
            'facility_id' => Facility::factory(),
            'kategori' => fake()->randomElement(['kerusakan', 'kebersihan', 'lainnya']),
            'deskripsi' => fake()->sentence(8),
            'foto_path' => null,
            'status' => 'baru',
            'catatan_resolusi' => null,
            'resolved_by' => null,
        ];
    }

    /**
     * Indicate that the report is in progress (diproses).
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'diproses',
            'resolved_by' => User::factory()->petugas()->verified(),
        ]);
    }

    /**
     * Indicate that the report is resolved (selesai).
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
            'catatan_resolusi' => 'Kerusakan telah diperbaiki oleh tim teknisi.',
            'resolved_by' => User::factory()->petugas()->verified(),
        ]);
    }

    /**
     * Indicate that the report is rejected (ditolak).
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ditolak',
            'catatan_resolusi' => 'Laporan tidak dapat diverifikasi di lapangan.',
            'resolved_by' => User::factory()->petugas()->verified(),
        ]);
    }
}
