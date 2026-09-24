<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'facility_id' => Facility::factory(),
            'tanggal' => now()->toDateString(),
            'start_time' => '08:00',
            'end_time' => '09:00',
            'tujuan_penggunaan' => fake()->sentence(),
            'status' => 'pending',
            'processed_by' => null,
            'cancelled_reason' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'processed_by' => User::factory()->petugas(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'processed_by' => User::factory()->petugas(),
        ]);
    }

    public function cancelled(?string $reason = 'Dibatalkan'): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancelled_reason' => $reason,
        ]);
    }
}
