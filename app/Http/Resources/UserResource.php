<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API v1 resources (Phase 30). The API never returns a raw model.
 * Sensitive fields (passport numbers) are masked unless the caller
 * holds `passport_holder.view_sensitive` (Phase 01 §13).
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'branch_id' => $this->branch_id,
            'status' => $this->status,
            'roles' => $this->whenLoaded('roles', fn () => $this->roles->pluck('name')),
            'last_login_at' => $this->last_login_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
