<?php

namespace App\Http\Requests\Visa;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('visa.create');
    }

    public function rules(): array
    {
        return [
            'recruitment_id' => ['required', 'exists:recruitments,id'],
            'visa_lot_id' => ['required', 'exists:visa_lots,id'],
            'submission_date' => ['nullable', 'date'],
            'status' => ['required', 'in:pending,submitted,approved,rejected,returned,cancelled'],
            'mofa_status' => ['nullable', 'in:pending,submitted,approved,rejected,returned'],
            'remarks' => ['nullable', 'string'],
            'document_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
