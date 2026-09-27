<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->status_akun === 'verified');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'facility_id' => ['required', 'exists:facilities,id'],
            'category' => ['required', 'string', 'in:kerusakan,kebersihan,lainnya'],
            'description' => ['required', 'string', 'min:5', 'max:2000'],
            'photo' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'facility_id' => 'fasilitas',
            'category' => 'jenis masalah / kendala',
            'description' => 'deskripsi kendala',
            'photo' => 'foto bukti',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'facility_id.required' => 'Fasilitas atau ruangan wajib dipilih.',
            'facility_id.exists' => 'Fasilitas yang dipilih tidak terdaftar di sistem.',
            'category.required' => 'Jenis kendala wajib dipilih.',
            'category.in' => 'Jenis kendala harus salah satu dari kerusakan, kebersihan, atau lainnya.',
            'description.required' => 'Deskripsi kendala wajib diisi.',
            'description.min' => 'Deskripsi kendala minimal berisi :min karakter.',
            'photo.image' => 'Berkas bukti harus berupa gambar.',
            'photo.mimes' => 'Format foto bukti harus berupa JPEG atau PNG.',
            'photo.max' => 'Ukuran foto bukti tidak boleh melebihi 2MB.',
        ];
    }
}
