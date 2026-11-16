<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Branch API representation (Phases 08-12).
 * The API never returns a raw model.
 */
class BranchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'manager_id' => $this->manager_id,
            'manager' => $this->whenLoaded('manager', fn () => $this->manager ? ['id' => $this->manager->id, 'name' => $this->manager->name] : null),
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
