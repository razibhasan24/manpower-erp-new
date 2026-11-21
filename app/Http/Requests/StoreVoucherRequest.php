<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // VoucherPolicy is checked by the controller.
    }

    public function rules(): array
    {
        $rules = [
            'voucher_type' => ['required', 'in:payment,receipt,journal,contra'],
            'voucher_date' => ['required', 'date'],
            'branch_id' => ['required', 'exists:branches,id'],
            'payment_mode_id' => ['nullable', 'integer', Rule::exists('payment_modes', 'id')->where('status', 'active')->whereNull('deleted_at')],
            'payee' => ['nullable', 'string', 'max:255'],
            'party_type' => ['nullable', 'in:agent,passport_holder,vendor,employee'],
            'party_id' => ['nullable', 'integer', 'min:1'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'narration' => ['required', 'string', 'max:500'],
            'reference_type' => ['nullable', 'string', 'max:255'],
            'reference_id' => ['nullable', 'integer'],
        ];

        if (in_array($this->input('voucher_type'), ['payment', 'receipt'], true)) {
            return array_merge($rules, [
                'payment_mode_id' => ['required', 'integer', Rule::exists('payment_modes', 'id')->where('status', 'active')->whereNull('deleted_at')],
                'lines' => ['required', 'array', 'min:1'],
                'lines.*.account_id' => ['required', 'exists:accounts,id'],
                'lines.*.amount' => ['required', 'numeric', 'gt:0'],
                'lines.*.narration' => ['nullable', 'string', 'max:500'],
            ]);
        }

        if ($this->input('voucher_type') === 'contra') {
            return $rules + [
                'lines' => ['required', 'array', 'min:1'],
                'lines.*.debit_account_id' => ['required', 'exists:accounts,id'],
                'lines.*.debit_amount' => ['required', 'numeric', 'gt:0'],
                'lines.*.credit_account_id' => ['required', 'exists:accounts,id'],
                'lines.*.credit_amount' => ['required', 'numeric', 'gt:0'],
                'lines.*.narration' => ['nullable', 'string', 'max:500'],
            ];
        }

        return $rules + [
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.account_id' => ['required', 'exists:accounts,id'],
            'lines.*.entry_type' => ['required', 'in:debit,credit'],
            'lines.*.amount' => ['required', 'numeric', 'gt:0'],
            'lines.*.sub_ledger_type' => ['nullable', 'in:agent,passport_holder,vendor,employee'],
            'lines.*.sub_ledger_id' => ['nullable', 'integer', 'min:1'],
            'lines.*.narration' => ['nullable', 'string', 'max:500'],
        ];
    }
}
