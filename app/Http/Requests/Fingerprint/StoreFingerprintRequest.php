<?php

namespace App\Http\Requests\Fingerprint;

use Illuminate\Foundation\Http\FormRequest;

class StoreFingerprintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('fingerprint.create');
    }

    public function rules(): array
    {
        return [
            'recruitment_id' => ['required', 'exists:recruitments,id'],
            'appointment_date' => ['nullable', 'date'],
            'submission_date' => ['nullable', 'date'],
            'status' => ['required', 'in:pending,submitted,completed,rejected,cancelled'],
            'reference_number' => ['required', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'document_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
