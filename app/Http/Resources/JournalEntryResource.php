<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'voucher_id' => $this->voucher_id,
            'voucher_number' => $this->whenLoaded('voucher', fn () => $this->voucher->voucher_number),
            'entry_date' => $this->entry_date,
            'narration' => $this->narration,
            'branch_id' => $this->branch_id,
            'lines' => $this->whenLoaded('lines', fn () => $this->lines->map(fn ($line) => [
                'id' => $line->id,
                'account_id' => $line->account_id,
                'account' => $line->account->name ?? null,
                'debit' => (float) $line->debit,
                'credit' => (float) $line->credit,
                'sub_ledger_type' => $line->sub_ledger_type,
                'sub_ledger_id' => $line->sub_ledger_id,
                'narration' => $line->narration,
            ])),
        ];
    }
}
