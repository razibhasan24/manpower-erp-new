<?php

namespace App\Http\Requests\PassportReturn;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePassportReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('passport-return.edit');
    }

    public function rules(): array
    {
        return [
            'recruitment_id' => ['sometimes', 'required', 'exists:recruitments,id'],
            'return_date' => ['nullable', 'date'],
            'return_reason' => ['nullable', 'string'],
            'return_type' => ['nullable', 'in:handover,courier,agent_collection,other'],
            'responsible_person' => ['required', 'string', 'max:255'],
            'received_by' => ['required', 'string', 'max:255'],
            'delivered_to' => ['nullable', 'string', 'max:255'],
            'identity_verified_by' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'acknowledgement_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
