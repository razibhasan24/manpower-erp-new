<?php

declare(strict_types=1);

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Branch validation + authorisation (Phases 08-12).
 */
class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Branch::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:branches,code'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
