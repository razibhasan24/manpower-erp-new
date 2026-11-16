<?php

declare(strict_types=1);

namespace App\Http\Requests\Position;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update Position validation + authorisation (Phases 08-12).
 */
class UpdatePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $position = $this->route('position');

        return $position !== null && $this->user()->can('update', $position);
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'company_id' => ['sometimes', 'required', 'integer', 'exists:companies,id'],
            'country_id' => ['sometimes', 'required', 'integer', 'exists:countries,id'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'currency' => ['nullable', 'string', 'size:3'],
            'status' => ['sometimes', 'required', 'in:active,inactive'],
        ];
    }
}
