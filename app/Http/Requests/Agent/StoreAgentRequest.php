<?php

declare(strict_types=1);

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Agent validation + authorisation (Phases 08-12).
 */
class StoreAgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Agent::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'address' => ['nullable', 'string'],
            'nid' => ['nullable', 'string', 'max:50'],
            'trade_license' => ['nullable', 'string', 'max:100'],
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'country_ids' => ['nullable', 'array'],
            'country_ids.*' => ['integer', 'exists:countries,id'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
