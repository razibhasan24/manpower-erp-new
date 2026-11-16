<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorisation is enforced by the Policy in the controller
    }

    public function rules(): array
    {
        return [
            'voucher_type' => ['required', 'in:payment,receipt,journal,contra'],
            'voucher_date' => ['required', 'date'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'narration' => ['nullable', 'string', 'max:500'],
            'reference_type' => ['nullable', 'string', 'max:255'],
            'reference_id' => ['nullable', 'integer'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.account_id' => ['required', 'exists:accounts,id'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.sub_ledger_type' => ['nullable', 'in:agent,passport_holder,vendor,employee'],
            'lines.*.sub_ledger_id' => ['nullable', 'integer'],
            'lines.*.narration' => ['nullable', 'string', 'max:500'],
        ];
    }
}
