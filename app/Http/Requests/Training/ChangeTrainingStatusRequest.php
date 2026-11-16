<?php

namespace App\Http\Requests\Training;

use App\Services\TrainingService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeTrainingStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $record = $this->route('training');
        return $record !== null && $this->user() !== null && $this->user()->can('training.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(TrainingService::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
