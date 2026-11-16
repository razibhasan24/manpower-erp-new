<?php

declare(strict_types=1);

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update Agent validation + authorisation (Phases 08-12).
 */
class UpdateAgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $agent = $this->route('agent');

        return $agent !== null && $this->user()->can('update', $agent);
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['sometimes', 'required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'nid' => ['nullable', 'string', 'max:50'],
            'trade_license' => ['nullable', 'string', 'max:100'],
            'branch_id' => ['sometimes', 'required', 'integer', 'exists:branches,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'country_ids' => ['nullable', 'array'],
            'country_ids.*' => ['integer', 'exists:countries,id'],
            'status' => ['sometimes', 'required', 'in:active,inactive'],
        ];
    }
}
