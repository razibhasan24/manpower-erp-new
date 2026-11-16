<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorisation is enforced by the Policy in the controller
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'month' => ['required', 'date_format:Y-m'],
            'basic' => ['nullable', 'numeric', 'min:0'],
            'allowances' => ['nullable', 'array'],
            'allowances.*' => ['numeric', 'min:0'],
            'deduction_items' => ['nullable', 'array'],
            'deduction_items.*' => ['numeric', 'min:0'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'overtime' => ['nullable', 'numeric', 'min:0'],
            'overtime_amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
