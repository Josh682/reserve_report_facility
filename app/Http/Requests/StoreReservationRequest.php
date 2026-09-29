<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'facility_id' => ['required', 'integer', 'exists:facilities,id'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => [
                'required',
                'date_format:H:i',
                'after_or_equal:07:00',
                'before:20:00',
                function ($attribute, $value, $fail) {
                    $parts = explode(':', (string) $value);
                    if (count($parts) !== 2 || ! in_array($parts[1], ['00', '30'], true)) {
                        $fail('Jam mulai peminjaman harus berupa slot kelipatan 30 menit (misal :00 atau :30).');
                    }
                },
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
                'before_or_equal:20:00',
                function ($attribute, $value, $fail) {
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
     * Get custom error messages for validator.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'facility_id.required' => 'Fasilitas / ruangan wajib dipilih.',
            'facility_id.exists' => 'Fasilitas yang dipilih tidak terdaftar di sistem.',
            'tanggal.required' => 'Tanggal peminjaman wajib diisi.',
            'tanggal.date' => 'Format tanggal peminjaman tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal peminjaman tidak boleh tanggal yang sudah lewat.',
            'start_time.required' => 'Jam mulai peminjaman wajib dipilih.',
            'start_time.after_or_equal' => 'Jam operasional peminjaman dimulai pukul 07.00 WIB.',
            'end_time.required' => 'Jam selesai peminjaman wajib dipilih.',
            'end_time.after' => 'Jam selesai harus lebih akhir dari jam mulai.',
            'end_time.before_or_equal' => 'Jam operasional peminjaman berakhir maksimal pukul 20.00 WIB.',
            'tujuan_penggunaan.required' => 'Tujuan penggunaan fasilitas wajib diisi.',
            'tujuan_penggunaan.min' => 'Tujuan penggunaan wajib diisi minimal 5 karakter.',
        ];
    }
}
