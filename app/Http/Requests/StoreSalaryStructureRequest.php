<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalaryStructureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorisation is enforced by the Policy in the controller
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'basic' => ['required', 'numeric', 'min:0'],
            'allowances' => ['nullable', 'array'],
            'allowances.*' => ['numeric', 'min:0'],
            'deductions' => ['nullable', 'array'],
            'deductions.*' => ['numeric', 'min:0'],
            'effective_from' => ['required', 'date'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
