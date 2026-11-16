<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Agent API representation (Phases 08-12).
 * The API never returns a raw model.
 */
class AgentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'branch_id' => $this->branch_id,
            'branch' => $this->whenLoaded('branch', fn () => $this->branch ? ['id' => $this->branch->id, 'name' => $this->branch->name, 'code' => $this->branch->code] : null),
            'countries' => $this->whenLoaded('countries', fn () => $this->countries->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'code' => $c->code])->all()),
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
