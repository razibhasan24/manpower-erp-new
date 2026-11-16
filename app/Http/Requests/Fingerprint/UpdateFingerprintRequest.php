<?php

namespace App\Http\Requests\Fingerprint;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFingerprintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('fingerprint.edit');
    }

    public function rules(): array
    {
        return [
            'recruitment_id' => ['sometimes', 'required', 'exists:recruitments,id'],
            'appointment_date' => ['nullable', 'date'],
            'submission_date' => ['nullable', 'date'],
            'status' => ['required', 'in:pending,submitted,completed,rejected,cancelled'],
            'reference_number' => ['required', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'document_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
