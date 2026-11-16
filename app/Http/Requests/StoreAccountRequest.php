<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorisation is enforced by the Policy in the controller
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:accounts,code' . ($this->route('account') ? ',' . $this->route('account')->id : '')],
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:accounts,id'],
            'account_type_id' => ['required', 'exists:account_types,id'],
            'normal_balance' => ['nullable', 'in:debit,credit'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
