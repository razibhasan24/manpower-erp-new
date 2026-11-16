<?php

namespace App\Http\Requests\VisaReturn;

use App\Services\VisaReturnService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeVisaReturnStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $record = $this->route('visaReturn');
        return $record !== null && $this->user() !== null && $this->user()->can('visa-return.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(VisaReturnService::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
