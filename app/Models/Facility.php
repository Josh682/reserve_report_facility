<?php

namespace App\Models;

use Database\Factories\FacilityFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

#[Fillable(['nama', 'tipe', 'lokasi', 'kapasitas', 'deskripsi', 'status'])]
class Facility extends Model
{
    /** @use HasFactory<FacilityFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
        ];
    }

    /**
     * Scope query untuk fasilitas yang berstatus aktif.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Relasi ke reservasi fasilitas.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Menghasilkan 26 slot waktu operasional tetap (07.00 - 20.00, interval 30 menit).
     *
     * @return array<int, array{start: string, end: string, label: string}>
     */
    public static function generateDailySlots(): array
    {
        $slots = [];
        $start = Carbon::createFromTime(7, 0);
        $end = Carbon::createFromTime(20, 0);

        while ($start < $end) {
            $slotStart = $start->format('H:i');
            $slotEnd = $start->copy()->addMinutes(30)->format('H:i');
            $slots[] = [
                'start' => $slotStart,
                'end' => $slotEnd,
                'label' => "{$slotStart} - {$slotEnd}",
            ];
            $start->addMinutes(30);
        }

        return $slots;
    }

    /**
     * Dapatkan matriks jadwal ketersediaan 26 slot waktu untuk tanggal tertentu.
     * Sesuai US 1: Menampilkan ketersediaan tanpa data pemohon atau tujuan penggunaan.
     *
     * @return array<int, array{start: string, end: string, label: string, is_available: bool, status_label: string}>
     */
    public function getScheduleForDate(string $date): array
    {
        $slots = static::generateDailySlots();

        if ($this->status === 'dalam_perbaikan') {
            return array_map(function ($slot) {
                return [
                    ...$slot,
                    'is_available' => false,
                    'status_label' => 'Dalam Perbaikan',
                ];
            }, $slots);
        }

        if ($this->status === 'nonaktif') {
            return array_map(function ($slot) {
                return [
                    ...$slot,
                    'is_available' => false,
                    'status_label' => 'Nonaktif',
                ];
            }, $slots);
        }

        $approvedReservations = $this->reservations()
            ->where('status', 'approved')
            ->whereDate('tanggal', $date)
            ->get(['start_time', 'end_time']);

        return array_map(function ($slot) use ($approvedReservations) {
            $slotStart = $slot['start'];
            $slotEnd = $slot['end'];

            $isBooked = $approvedReservations->contains(function ($res) use ($slotStart, $slotEnd) {
                $resStart = substr((string) $res->start_time, 0, 5);
                $resEnd = substr((string) $res->end_time, 0, 5);

                return $resStart < $slotEnd && $resEnd > $slotStart;
            });

            return [
                ...$slot,
                'is_available' => ! $isBooked,
                'status_label' => $isBooked ? 'Tidak Tersedia' : 'Tersedia',
            ];
        }, $slots);
    }
}
