<?php

namespace App\Http\Requests;

use App\Services\SawService;
use Illuminate\Foundation\Http\FormRequest;

class ProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'minat' => ['required', 'string', 'in:' . implode(',', SawService::MINAT)],
            'anggaran' => ['required', 'string', 'in:' . implode(',', SawService::ANGGARAN)],
            'jarak' => ['required', 'string', 'in:' . implode(',', SawService::JARAK)],
        ];
    }

    public function messages(): array
    {
        return [
            'minat.required' => 'Minat aktivitas wajib dipilih.',
            'minat.in' => 'Minat aktivitas tidak dikenali.',
            'anggaran.required' => 'Anggaran perjalanan wajib dipilih.',
            'anggaran.in' => 'Anggaran perjalanan tidak dikenali.',
            'jarak.required' => 'Preferensi jarak wajib dipilih.',
            'jarak.in' => 'Preferensi jarak tidak dikenali.',
        ];
    }

    public function attributes(): array
    {
        return [
            'minat' => 'minat aktivitas',
            'anggaran' => 'anggaran',
            'jarak' => 'jarak',
        ];
    }
}
