<?php

namespace App\Http\Requests\Delegation;

use App\Services\DelegationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeDelegationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $record = $this->route('delegation');
        return $record !== null && $this->user() !== null && $this->user()->can('delegation.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(DelegationService::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
