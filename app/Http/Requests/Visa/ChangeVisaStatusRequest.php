<?php

namespace App\Http\Requests\Visa;

use App\Services\VisaService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeVisaStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $record = $this->route('visa');
        return $record !== null && $this->user() !== null && $this->user()->can('visa.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(VisaService::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
