<?php

declare(strict_types=1);

namespace App\Http\Requests\Recruitment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Recruitment validation + authorisation (Phases 08-12).
 */
class StoreRecruitmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Recruitment::class);
    }

    public function rules(): array
    {
        return [
            'passport_holder_id' => ['sometimes', 'integer', 'exists:passport_holders,id'],
            'passport_number' => ['required', 'string', 'max:50', 'unique:passport_holders,passport_number'],
            'full_name' => ['required', 'string', 'max:255'], 'nid' => ['nullable', 'string', 'max:50'],
            'father_name' => ['nullable', 'string', 'max:255'], 'mother_name' => ['nullable', 'string', 'max:255'],
            'dob' => ['required', 'date', 'before:today'], 'gender' => ['required', 'in:male,female,other'],
            'phone' => ['nullable', 'string', 'max:30'], 'email' => ['nullable', 'email', 'max:255'],
            'passport_issue_date' => ['required', 'date'], 'passport_expiry_date' => ['required', 'date', 'after:passport_issue_date'],
            'religion' => ['required', 'string', 'max:50'], 'marital_status' => ['nullable', 'string', 'max:30'],
            'referer' => ['nullable', 'string', 'max:255'], 'height' => ['nullable', 'string', 'max:30'], 'weight' => ['nullable', 'string', 'max:30'],
            'village_road' => ['nullable', 'string', 'max:255'], 'post_office' => ['nullable', 'string', 'max:255'], 'police_station' => ['nullable', 'string', 'max:255'], 'district' => ['nullable', 'string', 'max:255'], 'present_address' => ['nullable', 'string'],
            'agent_id' => ['required', 'integer', 'exists:agents,id'],
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'position_id' => ['required', 'integer', 'exists:positions,id'],
            'expected_salary' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
            'local_experience_years' => ['nullable', 'integer', 'min:0', 'max:80'], 'education_type' => ['nullable', 'string', 'max:100'], 'education_degree' => ['nullable', 'string', 'max:255'], 'education_school' => ['nullable', 'string', 'max:255'], 'education_grade' => ['nullable', 'string', 'max:50'], 'education_year' => ['nullable', 'string', 'max:10'], 'language_proficiency' => ['nullable', 'array'], 'language_proficiency.*' => ['in:No,Low,Medium,High'],
            'documents' => ['nullable', 'array'],
            'documents.passport_scan' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'documents.nid_scan' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'documents.photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'documents.other' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
