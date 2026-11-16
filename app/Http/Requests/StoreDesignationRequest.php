<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDesignationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorisation is enforced by the Policy in the controller
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
