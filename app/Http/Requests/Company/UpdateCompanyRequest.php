<?php

declare(strict_types=1);

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update Company validation + authorisation (Phases 08-12).
 */
class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $company = $this->route('company');

        return $company !== null && $this->user()->can('update', $company);
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'country_id' => ['sometimes', 'required', 'integer', 'exists:countries,id'],
            'status' => ['sometimes', 'required', 'in:active,inactive'],
        ];
    }
}
