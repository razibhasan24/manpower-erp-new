<?php

declare(strict_types=1);

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Company validation + authorisation (Phases 08-12).
 */
class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Company::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
