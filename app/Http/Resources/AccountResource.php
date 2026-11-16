<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'account_type' => $this->whenLoaded('type', fn () => $this->type->name),
            'normal_balance' => $this->normal_balance,
            'status' => $this->status,
        ];
    }
}
