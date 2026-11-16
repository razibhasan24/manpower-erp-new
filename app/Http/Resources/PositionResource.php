<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Position API representation (Phases 08-12).
 * The API never returns a raw model.
 */
class PositionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'company_id' => $this->company_id,
            'company' => $this->whenLoaded('company', fn () => $this->company ? ['id' => $this->company->id, 'name' => $this->company->name] : null),
            'country_id' => $this->country_id,
            'country' => $this->whenLoaded('country', fn () => $this->country ? ['id' => $this->country->id, 'name' => $this->country->name, 'code' => $this->country->code] : null),
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'currency' => $this->currency,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
