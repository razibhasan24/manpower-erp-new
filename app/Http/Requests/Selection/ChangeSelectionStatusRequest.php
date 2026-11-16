<?php

namespace App\Http\Requests\Selection;

use App\Services\SelectionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeSelectionStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $record = $this->route('selection');
        return $record !== null && $this->user() !== null && $this->user()->can('selection.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(SelectionService::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
