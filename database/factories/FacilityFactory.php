<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Facility>
 */
class FacilityFactory extends Factory
{
    protected $model = Facility::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->words(3, true),
            'tipe' => fake()->randomElement(['ruang_kelas', 'aula', 'laboratorium', 'alat', 'lapangan']),
            'lokasi' => 'Gedung '.fake()->randomElement(['A', 'B', 'C', 'D', 'E']).' Lantai '.fake()->numberBetween(1, 4),
            'kapasitas' => fake()->numberBetween(10, 100),
            'deskripsi' => fake()->paragraph(),
            'status' => 'aktif',
        ];
    }
}
