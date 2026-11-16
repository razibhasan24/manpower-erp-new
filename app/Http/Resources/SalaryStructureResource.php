<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryStructureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, 'employee_id' => $this->employee_id,
            'basic' => (float) $this->basic, 'allowances' => $this->allowances,
            'deductions' => $this->deductions, 'effective_from' => $this->effective_from,
        ];
    }
}
