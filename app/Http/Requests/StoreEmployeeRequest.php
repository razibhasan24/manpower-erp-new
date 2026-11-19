<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Branch;
use App\Models\Employee;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Employee::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id', Rule::in(Branch::visibleTo($this->user())->pluck('id')->all())],
            'employee_code' => ['required', 'string', 'max:50', 'unique:employees,employee_code'],
            'joining_date' => ['nullable', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive,terminated'],
        ];
    }
}
