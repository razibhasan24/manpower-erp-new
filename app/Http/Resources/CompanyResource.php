<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Company API representation (Phases 08-12).
 * The API never returns a raw model.
 */
class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country_id' => $this->country_id,
            'country' => $this->whenLoaded('country', fn () => $this->country ? ['id' => $this->country->id, 'name' => $this->country->name, 'code' => $this->country->code] : null),
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
