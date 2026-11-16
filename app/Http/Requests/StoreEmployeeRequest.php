<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorisation is enforced by the Policy in the controller
    }

    public function rules(): array
    {
        return [
            // NOTE: name/email live on the linked User (employees.user_id) — the employees table has no name/email columns.
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'user_id' => ['nullable', 'exists:users,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'employee_code' => ['required', 'string', 'max:50'],
            'joining_date' => ['nullable', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:active,inactive,terminated'],
        ];
    }
}
