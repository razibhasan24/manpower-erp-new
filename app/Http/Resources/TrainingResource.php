<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Training */
class TrainingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'recruitment_id' => $this->recruitment_id ?? null,
            'recruitment_tracking_number' => $this->recruitment?->tracking_number,
            'status' => $this->result,
            'remarks' => $this->remarks ?? null,
            'created_by' => $this->created_by ?? null,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
