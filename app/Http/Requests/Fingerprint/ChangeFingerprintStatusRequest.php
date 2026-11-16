<?php

namespace App\Http\Requests\Fingerprint;

use App\Services\FingerprintService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeFingerprintStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $record = $this->route('fingerprint');
        return $record !== null && $this->user() !== null && $this->user()->can('fingerprint.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(FingerprintService::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
