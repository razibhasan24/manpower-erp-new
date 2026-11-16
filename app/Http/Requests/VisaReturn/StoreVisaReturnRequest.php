<?php

namespace App\Http\Requests\VisaReturn;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisaReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('visa-return.create');
    }

    public function rules(): array
    {
        return [
            'visa_id' => ['required', 'exists:visas,id'],
            'return_date' => ['nullable', 'date'],
            'return_reason' => ['nullable', 'string'],
            'return_type' => ['nullable', 'in:handover,courier,agent_collection,other'],
            'responsible_person' => ['required', 'string', 'max:255'],
            'received_by' => ['required', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'document_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
