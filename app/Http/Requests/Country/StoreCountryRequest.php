<?php

declare(strict_types=1);

namespace App\Http\Requests\Country;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Country validation + authorisation (Phases 08-12).
 */
class StoreCountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Country::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'size:2', 'unique:countries,code'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
