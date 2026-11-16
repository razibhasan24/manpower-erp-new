<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recruitment API representation (Phases 08-12).
 * The API never returns a raw model.
 */
class RecruitmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tracking_number' => $this->tracking_number,
            'passport_holder_id' => $this->passport_holder_id,
            'passport_holder' => $this->whenLoaded('passportHolder', fn () => $this->passportHolder ? ['id' => $this->passportHolder->id, 'full_name' => $this->passportHolder->full_name] : null),
            'agent_id' => $this->agent_id,
            'agent' => $this->whenLoaded('agent', fn () => $this->agent ? ['id' => $this->agent->id, 'name' => $this->agent->name] : null),
            'branch_id' => $this->branch_id,
            'country_id' => $this->country_id,
            'company_id' => $this->company_id,
            'position_id' => $this->position_id,
            'expected_salary' => $this->expected_salary,
            // Financial responsibility is undecided at registration and
            // is only set at Selection (Group C). Null here is correct.
            'financial_responsible_type' => $this->financial_responsible_type,
            'financial_responsible_id' => $this->financial_responsible_id,
            'current_stage' => $this->current_stage,
            'current_status' => $this->current_status,
            'remarks' => $this->remarks,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
