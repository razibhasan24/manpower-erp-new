<?php

namespace App\Http\Requests\PoliceClearance;

use Illuminate\Foundation\Http\FormRequest;

class StorePoliceClearanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('police-clearance.create');
    }

    public function rules(): array
    {
        return [
            'recruitment_id' => ['required', 'exists:recruitments,id'],
            'status' => ['required', 'in:pending,cleared,uncleared,returned,cancelled'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
            'certificate_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
