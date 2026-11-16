<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Passport Holder API representation (Phases 08-12).
 * The API never returns a raw model.
 */
class PassportHolderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'phone' => $this->phone,
            'email' => $this->email,
            'present_address' => $this->present_address,
            'permanent_address' => $this->permanent_address,
            // Passport and NID numbers are masked in API responses. Only
            // a Super Admin sees the full values (Phase 01 section 13).
            'nid' => $this->maskIdentifier($this->nid),
            'passport_number' => $this->maskIdentifier($this->passport_number),
            'passport_issue_date' => $this->passport_issue_date,
            'passport_expiry_date' => $this->passport_expiry_date,
            'agent_id' => $this->agent_id,
            'agent' => $this->whenLoaded('agent', fn () => $this->agent ? ['id' => $this->agent->id, 'name' => $this->agent->name] : null),
            'branch_id' => $this->branch_id,
            'country_id' => $this->country_id,
            'company_id' => $this->company_id,
            'position_id' => $this->position_id,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    protected function maskIdentifier(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $user = request()->user();

        if ($user !== null && $user->hasRole('Super Admin')) {
            return $value;
        }

        $length = mb_strlen($value);

        if ($length <= 4) {
            return str_repeat('*', $length);
        }

        return mb_substr($value, 0, 2).str_repeat('*', $length - 4).mb_substr($value, -2);
    }
}
