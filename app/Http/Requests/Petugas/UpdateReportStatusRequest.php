<?php

namespace App\Http\Requests\Petugas;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReportStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'petugas';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                'in:baru,diproses,selesai,ditolak',
                function ($attribute, $value, $fail) {
                    $report = $this->route('report');
                    if (! $report) {
                        return;
                    }

                    $currentStatus = $report->status;

                    if (in_array($currentStatus, ['selesai', 'ditolak'], true)) {
                        $fail("Laporan yang sudah berstatus '{$currentStatus}' tidak dapat diubah kembali.");

                        return;
                    }

                    $allowedTransitions = [
                        'baru' => ['baru', 'diproses', 'ditolak'],
                        'diproses' => ['diproses', 'selesai', 'ditolak'],
                    ];

                    if (! in_array($value, $allowedTransitions[$currentStatus] ?? [], true)) {
                        $fail("Transisi status dari '{$currentStatus}' ke '{$value}' tidak diizinkan.");
                    }
                },
            ],
            'catatan_resolusi' => [
                'nullable',
                'string',
                'max:2000',
                'required_if:status,selesai',
                'required_if:status,ditolak',
            ],
            'mark_facility_status' => [
                'nullable',
                'string',
                'in:aktif,dalam_perbaikan',
                function ($attribute, $value, $fail) {
                    $report = $this->route('report');
                    if ($report && $report->facility && $report->facility->status === 'nonaktif') {
                        $fail('Fasilitas berstatus nonaktif hanya dapat dikelola oleh Administrator.');
                    }
                },
            ],
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
            'status.required' => 'Status laporan wajib dipilih.',
            'status.in' => 'Status laporan tidak valid.',
            'catatan_resolusi.required_if' => 'Catatan tindak lanjut / resolusi wajib diisi saat status diselesaikan atau ditolak.',
            'catatan_resolusi.max' => 'Catatan resolusi maksimal 2000 karakter.',
            'mark_facility_status.in' => 'Status fasilitas operasional tidak valid.',
        ];
    }
}
