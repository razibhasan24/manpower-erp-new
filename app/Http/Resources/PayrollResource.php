<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, 'employee_id' => $this->employee_id, 'month' => $this->month,
            'gross' => (float) $this->gross, 'deductions' => (float) $this->deductions,
            'bonus' => (float) $this->bonus, 'overtime' => (float) $this->overtime,
            'net' => (float) $this->net, 'status' => $this->status,
            'voucher_id' => $this->voucher_id, 'paid_at' => $this->paid_at,
        ];
    }
}
