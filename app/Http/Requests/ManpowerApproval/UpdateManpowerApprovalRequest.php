<?php

namespace App\Http\Requests\ManpowerApproval;

use Illuminate\Foundation\Http\FormRequest;

class UpdateManpowerApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('manpower-approval.edit');
    }

    public function rules(): array
    {
        return [
            'recruitment_id' => ['sometimes', 'required', 'exists:recruitments,id'],
            'approval_reference' => ['nullable', 'string', 'max:255'],
            'approval_date' => ['nullable', 'date'],
            'status' => ['required', 'in:pending,approved,rejected,returned,cancelled'],
            'remarks' => ['nullable', 'string'],
            'documents_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
