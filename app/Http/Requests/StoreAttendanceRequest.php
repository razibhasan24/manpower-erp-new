<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorisation is enforced by the Policy in the controller
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'date' => ['required', 'date'],
            'check_in' => ['nullable', 'date_format:H:i:s'],
            'check_out' => ['nullable', 'date_format:H:i:s', 'after:check_in'],
            'status' => ['nullable', 'in:present,late,absent,leave'],
            'source' => ['nullable', 'in:manual,biometric'],
        ];
    }
}
