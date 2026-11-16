<?php

declare(strict_types=1);

namespace App\Http\Requests\Position;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Position validation + authorisation (Phases 08-12).
 */
class StorePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Position::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'currency' => ['nullable', 'string', 'size:3'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
