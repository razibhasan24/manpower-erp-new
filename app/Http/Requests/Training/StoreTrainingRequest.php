<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('training.create');
    }

    public function rules(): array
    {
        return [
            'recruitment_id' => ['required', 'exists:recruitments,id'],
            'training_centre_id' => ['required', 'exists:training_centres,id'],
            'course_id' => ['required', 'exists:training_courses,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'attendance_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'result' => ['nullable', 'in:pending,passed,failed,cancelled'],
            'remarks' => ['nullable', 'string'],
            'certificate_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
