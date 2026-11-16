<?php

namespace App\Http\Requests\ManpowerApproval;

use App\Services\ManpowerApprovalService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeManpowerApprovalStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $record = $this->route('manpowerApproval');
        return $record !== null && $this->user() !== null && $this->user()->can('manpower-approval.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(ManpowerApprovalService::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
