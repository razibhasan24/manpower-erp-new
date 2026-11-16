<?php

namespace App\Http\Requests\Medical;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('medical.create');
    }

    public function rules(): array
    {
        return [
            'recruitment_id' => ['required', 'exists:recruitments,id'],
            'medical_centre_id' => ['required', 'exists:medical_centres,id'],
            'medical_date' => ['nullable', 'date'],
            'result' => ['required', 'in:pending,fit,unfit,expired,cancelled'],
            'remarks' => ['nullable', 'string'],
            'report_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
