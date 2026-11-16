<?php

namespace App\Http\Requests\PassportReturn;

use App\Services\PassportReturnService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangePassportReturnStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $record = $this->route('passportReturn');
        return $record !== null && $this->user() !== null && $this->user()->can('passport-return.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(PassportReturnService::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
