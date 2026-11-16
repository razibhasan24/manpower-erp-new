<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'voucher_number' => $this->voucher_number,
            'voucher_type' => $this->voucher_type,
            'voucher_date' => $this->voucher_date,
            'branch_id' => $this->branch_id,
            'reference_type' => $this->reference_type,
            'reference_id' => $this->reference_id,
            'total_debit' => (float) $this->total_debit,
            'total_credit' => (float) $this->total_credit,
            'narration' => $this->narration,
            'status' => $this->status,
            'posted_at' => $this->posted_at,
            'reversal_voucher_id' => $this->reversal_voucher_id,
            'journal_entry' => new JournalEntryResource($this->whenLoaded('journalEntry')),
        ];
    }
}
