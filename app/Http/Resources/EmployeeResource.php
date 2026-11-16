<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->user->name ?? null,
            'employee_code' => $this->employee_code,
            'email' => $this->user->email ?? null,
            'phone' => $this->phone,
            'department' => $this->whenLoaded('department', fn () => $this->department->name),
            'designation' => $this->whenLoaded('designation', fn () => $this->designation->name),
            'branch_id' => $this->branch_id,
            'joining_date' => $this->joining_date,
            'salary' => (float) ($this->salary ?? 0),
            'status' => $this->status,
        ];
    }
}
