<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorisation is enforced by the Policy in the controller
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'purpose' => ['required', 'string', 'max:500'],
            'person_to_meet' => ['nullable', 'string', 'max:255'],
            'entry_time' => ['nullable', 'date'],
            'exit_time' => ['nullable', 'date', 'after_or_equal:entry_time'],
        ];
    }
}
