<?php

declare(strict_types=1);

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update Branch validation + authorisation (Phases 08-12).
 */
class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        $branch = $this->route('branch');

        return $branch !== null && $this->user()->can('update', $branch);
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('branches', 'code')->ignore($this->route('branch'))],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'status' => ['sometimes', 'required', 'in:active,inactive'],
        ];
    }
}
