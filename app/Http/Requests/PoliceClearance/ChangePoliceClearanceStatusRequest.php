<?php

namespace App\Http\Requests\PoliceClearance;

use App\Services\PoliceClearanceService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangePoliceClearanceStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $record = $this->route('policeClearance');
        return $record !== null && $this->user() !== null && $this->user()->can('police-clearance.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(PoliceClearanceService::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
