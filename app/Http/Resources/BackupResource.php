<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BackupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'filename' => $this->filename,
            'type' => $this->type,
            'size' => $this->size,
            'size_human' => $this->humanSize(),
            'status' => $this->status,
            'verified_at' => $this->verified_at?->toIso8601String(),
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }

    protected function humanSize(): string
    {
        $bytes = (float) ($this->size ?? 0);
        foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
            if ($bytes < 1024) {
                return round($bytes, 2).' '.$unit;
            }
            $bytes /= 1024;
        }

        return round($bytes, 2).' TB';
    }
}
