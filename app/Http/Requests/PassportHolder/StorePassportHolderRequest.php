<?php

declare(strict_types=1);

namespace App\Http\Requests\PassportHolder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Passport Holder validation + authorisation (Phases 08-12).
 */
class StorePassportHolderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', PassportHolder::class);
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'present_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'nid' => ['nullable', 'string', 'max:50'],
            'passport_number' => ['required', 'string', 'max:50'],
            'passport_issue_date' => ['nullable', 'date'],
            'passport_expiry_date' => ['nullable', 'date', 'after:passport_issue_date'],
            'agent_id' => ['nullable', 'integer', 'exists:agents,id'],
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'position_id' => ['nullable', 'integer', 'exists:positions,id'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
