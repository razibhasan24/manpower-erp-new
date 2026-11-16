<?php

declare(strict_types=1);

namespace App\Http\Requests\Country;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update Country validation + authorisation (Phases 08-12).
 */
class UpdateCountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $country = $this->route('country');

        return $country !== null && $this->user()->can('update', $country);
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'size:2', Rule::unique('countries', 'code')->ignore($this->route('country'))],
            'status' => ['sometimes', 'required', 'in:active,inactive'],
        ];
    }
}
