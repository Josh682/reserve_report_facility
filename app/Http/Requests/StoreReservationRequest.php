<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class StoreReservationRequest extends FormRequest
{
    public const TIMEZONE = 'Asia/Jakarta';

    public const MAX_ADVANCE_DAYS = 60;

    public const MAX_DURATION_HOURS = 6;

    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $today = now(self::TIMEZONE)->startOfDay();

        return [
            'facility_id' => ['required', 'integer', 'exists:facilities,id'],
            'tanggal' => [
                'bail',
                'required',
                'date_format:Y-m-d',
                'after_or_equal:'.$today->toDateString(),
                'before_or_equal:'.$today->copy()->addDays(self::MAX_ADVANCE_DAYS)->toDateString(),
            ],
            'start_time' => [
                'bail',
                'required',
                'date_format:H:i',
                'after_or_equal:07:00',
                'before:20:00',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $parts = explode(':', (string) $value);
                    if (count($parts) !== 2 || ! in_array($parts[1], ['00', '30'], true)) {
                        $fail('Jam mulai peminjaman harus berupa slot kelipatan 30 menit (misal :00 atau :30).');
                    }
                },
            ],
            'end_time' => [
                'bail',
                'required',
                'date_format:H:i',
                'before_or_equal:20:00',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $parts = explode(':', (string) $value);
                    if (count($parts) !== 2 || ! in_array($parts[1], ['00', '30'], true)) {
                        $fail('Jam selesai peminjaman harus berupa slot kelipatan 30 menit (misal :00 atau :30).');
                    }
                },
            ],
            'tujuan_penggunaan' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    /**
     * @return array<Closure>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->hasAny(['tanggal', 'start_time', 'end_time'])) {
                    return;
                }

                $start = Carbon::createFromFormat('!Y-m-d H:i', $this->input('tanggal').' '.$this->input('start_time'), self::TIMEZONE);
                $end = Carbon::createFromFormat('!Y-m-d H:i', $this->input('tanggal').' '.$this->input('end_time'), self::TIMEZONE);

                if ($start->lt(now(self::TIMEZONE))) {
                    $validator->errors()->add('start_time', 'Jam mulai peminjaman untuk hari ini tidak boleh sudah terlewat (WIB).');
                }

                if ($end->lte($start)) {
                    $validator->errors()->add('end_time', 'Jam selesai harus lebih akhir dari jam mulai.');

                    return;
                }

                if ($start->diffInMinutes($end) > self::MAX_DURATION_HOURS * 60) {
                    $validator->errors()->add('end_time', 'Durasi peminjaman maksimal '.self::MAX_DURATION_HOURS.' jam per reservasi.');
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'facility_id.required' => 'Fasilitas / ruangan wajib dipilih.',
            'facility_id.exists' => 'Fasilitas yang dipilih tidak terdaftar di sistem.',
            'tanggal.required' => 'Tanggal peminjaman wajib diisi.',
            'tanggal.date_format' => 'Format tanggal peminjaman tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal peminjaman tidak boleh tanggal yang sudah lewat.',
            'tanggal.before_or_equal' => 'Tanggal peminjaman maksimal '.self::MAX_ADVANCE_DAYS.' hari dari hari ini.',
            'start_time.required' => 'Jam mulai peminjaman wajib dipilih.',
            'start_time.after_or_equal' => 'Jam operasional peminjaman dimulai pukul 07.00 WIB.',
            'end_time.required' => 'Jam selesai peminjaman wajib dipilih.',
            'end_time.before_or_equal' => 'Jam operasional peminjaman berakhir maksimal pukul 20.00 WIB.',
            'tujuan_penggunaan.required' => 'Tujuan penggunaan fasilitas wajib diisi.',
            'tujuan_penggunaan.min' => 'Tujuan penggunaan wajib diisi minimal 5 karakter.',
        ];
    }
}
