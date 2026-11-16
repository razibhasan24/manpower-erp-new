<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorisation is enforced by the Policy in the controller
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'normal_balance' => ['required', 'in:debit,credit'],
        ];
    }
}
