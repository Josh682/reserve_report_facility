<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FacilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'tipe' => ['required', 'string', Rule::in(['ruang_kelas', 'aula', 'laboratorium', 'alat', 'lapangan'])],
            'lokasi' => ['required', 'string', 'max:255'],
            'kapasitas' => ['nullable', 'integer', 'min:1'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['aktif', 'dalam_perbaikan', 'nonaktif'])],
        ];
    }

    /**
     * Custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama fasilitas wajib diisi.',
            'nama.max' => 'Nama fasilitas maksimal 150 karakter.',
            'tipe.required' => 'Pilih jenis/tipe fasilitas.',
            'tipe.in' => 'Jenis/tipe fasilitas tidak valid.',
            'lokasi.required' => 'Lokasi fasilitas wajib diisi.',
            'lokasi.max' => 'Lokasi fasilitas maksimal 255 karakter.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'kapasitas.min' => 'Kapasitas minimal 1 orang/unit.',
            'status.required' => 'Status operasional fasilitas wajib dipilih.',
            'status.in' => 'Status fasilitas tidak valid.',
        ];
    }
}
