<?php

namespace App\Http\Requests\Delegation;

use Illuminate\Foundation\Http\FormRequest;

class StoreDelegationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('delegation.create');
    }

    public function rules(): array
    {
        return [
            'recruitment_id' => ['required', 'exists:recruitments,id'],
            'status' => ['required', 'in:pending,approved,rejected,returned,cancelled'],
            'remarks' => ['nullable', 'string'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'e_duty_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
