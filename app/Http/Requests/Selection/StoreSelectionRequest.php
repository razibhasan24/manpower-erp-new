<?php

namespace App\Http\Requests\Selection;

use Illuminate\Foundation\Http\FormRequest;

class StoreSelectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('selection.create');
    }

    public function rules(): array
    {
        return [
            'recruitment_id' => ['required', 'exists:recruitments,id'],
            'grade' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:pending,approved,rejected,returned,cancelled'],
            'decided_at' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
            'financial_responsible_type' => ['nullable', 'in:agent,passport_holder'],
            'financial_responsible_id' => ['nullable', 'integer'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'company_service_charge' => ['nullable', 'numeric', 'min:0'],
            'agent_charge' => ['nullable', 'numeric', 'min:0'],
            'medical_cost' => ['nullable', 'numeric', 'min:0'],
            'training_cost' => ['nullable', 'numeric', 'min:0'],
            'visa_cost' => ['nullable', 'numeric', 'min:0'],
            'other_cost' => ['nullable', 'numeric', 'min:0'],
            'advance_amount' => ['nullable', 'numeric', 'min:0'],
            'grades' => ['nullable', 'array'],
            'grades.*' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
