<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, 'name' => $this->name, 'phone' => $this->phone,
            'purpose' => $this->purpose, 'person_to_meet' => $this->person_to_meet,
            'entry_time' => $this->entry_time, 'exit_time' => $this->exit_time,
            'pass_number' => $this->pass_number,
        ];
    }
}
