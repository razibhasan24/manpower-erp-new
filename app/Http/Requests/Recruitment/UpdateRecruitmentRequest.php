<?php

declare(strict_types=1);

namespace App\Http\Requests\Recruitment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update Recruitment validation + authorisation (Phases 08-12).
 */
class UpdateRecruitmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $recruitment = $this->route('recruitment');

        return $recruitment !== null && $this->user()->can('update', $recruitment);
    }

    public function rules(): array
    {
        return [
            'passport_holder_id' => ['sometimes', 'required', 'integer', 'exists:passport_holders,id'],
            'agent_id' => ['nullable', 'integer', 'exists:agents,id'],
            'branch_id' => ['sometimes', 'required', 'integer', 'exists:branches,id'],
            'country_id' => ['sometimes', 'required', 'integer', 'exists:countries,id'],
            'company_id' => ['sometimes', 'required', 'integer', 'exists:companies,id'],
            'position_id' => ['sometimes', 'required', 'integer', 'exists:positions,id'],
            'expected_salary' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
            'documents' => ['nullable', 'array'],
            'documents.passport_scan' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'documents.nid_scan' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'documents.photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'documents.other' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
