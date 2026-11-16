<?php

namespace App\Http\Requests\Medical;

use App\Services\MedicalService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeMedicalStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $record = $this->route('medical');
        return $record !== null && $this->user() !== null && $this->user()->can('medical.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(MedicalService::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
