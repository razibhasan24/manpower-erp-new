<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHolidayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorisation is enforced by the Policy in the controller
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date', 'unique:holidays,date' . ($this->route('holiday') ? ',' . $this->route('holiday')->id : '')],
            'type' => ['nullable', 'in:public,company,religious'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
