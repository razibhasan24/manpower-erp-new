<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollItem;
use App\Models\SalaryStructure;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Payroll (Phase 26). Computation is pure arithmetic from the salary
 * structure + attendance + bonus/overtime/deductions; posting and
 * payment go through VoucherService so payroll and accounting can
 * never disagree.
 *
 *   gross = basic + allowances + bonus + overtime_amount
 *   net   = gross - deductions
 */
class PayrollService
{
    public function __construct(protected VoucherService $vouchers) {}

    /** Build (or rebuild, while still draft) a payroll row for one employee + month (YYYY-MM). */
    public function compute(Employee $employee, string $month, array $overrides = []): Payroll
    {
        $structure = SalaryStructure::where('employee_id', $employee->id)
            ->whereDate('effective_from', '<=', $month . '-01')
            ->orderByDesc('effective_from')->first();

        if (! $structure && empty($overrides['basic'])) {
            throw ValidationException::withMessages([
                'salary_structure' => 'No salary structure is effective for this employee.',
            ]);
        }

        $basic = (float) ($overrides['basic'] ?? $structure?->basic ?? 0);
        $allowances = $overrides['allowances'] ?? $structure?->allowances ?? [];
        $allowanceTotal = array_sum(array_map('floatval', is_array($allowances) ? array_values($allowances) : []));
        $bonus = (float) ($overrides['bonus'] ?? 0);
        $overtimeAmount = (float) ($overrides['overtime_amount'] ?? 0);
        $overtimeHours = (float) ($overrides['overtime'] ?? 0);
        $deductionItems = $overrides['deduction_items'] ?? $structure?->deductions ?? [];
        $deductions = array_sum(array_map('floatval', is_array($deductionItems) ? array_values($deductionItems) : []));

        $gross = round($basic + $allowanceTotal, 2);

        return DB::transaction(function () use ($employee, $month, $gross, $bonus, $overtimeAmount, $overtimeHours, $deductions) {
            $payroll = Payroll::firstOrNew(['employee_id' => $employee->id, 'month' => $month]);

            if ($payroll->exists && $payroll->status !== 'draft') {
                throw ValidationException::withMessages([
                    'payroll' => 'Only a draft payroll can be recomputed.',
                ]);
            }

            $payroll->fill([
                'gross' => $gross,
                'bonus' => round($bonus, 2),
                'overtime' => $overtimeHours,
                'deductions' => round($deductions, 2),
                'net' => round($gross + $bonus + $overtimeAmount - $deductions, 2),
                'status' => 'draft',
            ]);
            $payroll->save();

            $this->syncItems($payroll, $basic, $allowances, $deductionItems, $bonus, $overtimeAmount);

            return $payroll->fresh();
        });
    }

    /** Line items for the payroll detail view (PayrollItem: earning/deduction). */
    protected function syncItems(Payroll $payroll, float $basic, array $allowances, array $deductions, float $bonus, float $overtimeAmount): void
    {
        $payroll->items()->delete();

        $payroll->items()->create(['label' => 'Basic Salary', 'type' => 'earning', 'amount' => $basic]);

        foreach ($allowances as $label => $amount) {
            $payroll->items()->create(['label' => 'Allowance: ' . $label, 'type' => 'earning', 'amount' => (float) $amount]);
        }

        if ($bonus > 0) {
            $payroll->items()->create(['label' => 'Bonus', 'type' => 'earning', 'amount' => $bonus]);
        }

        if ($overtimeAmount > 0) {
            $payroll->items()->create(['label' => 'Overtime', 'type' => 'earning', 'amount' => $overtimeAmount]);
        }

        foreach ($deductions as $label => $amount) {
            $payroll->items()->create(['label' => 'Deduction: ' . $label, 'type' => 'deduction', 'amount' => (float) $amount]);
        }
    }

    /** Approve a draft payroll and post its Journal Voucher (atomic: both or neither). */
    public function approveAndPost(Payroll $payroll): Payroll
    {
        if ($payroll->status !== 'draft') {
            throw ValidationException::withMessages(['payroll' => 'Only a draft payroll can be approved.']);
        }

        return DB::transaction(function () use ($payroll) {
            $voucher = $this->vouchers->postPayroll($payroll);

            $payroll->update([
                'status' => 'approved',
                'voucher_id' => $voucher->id,
            ]);

            return $payroll->fresh();
        });
    }

    /** Pay an approved payroll: Dr Salary Payable, Cr Cash/Bank; marks paid_at. */
    public function pay(Payroll $payroll, int $cashOrBankAccountId, ?string $date = null): Payroll
    {
        if ($payroll->status !== 'approved') {
            throw ValidationException::withMessages(['payroll' => 'Only an approved payroll can be paid.']);
        }

        return DB::transaction(function () use ($payroll, $cashOrBankAccountId, $date) {
            $this->vouchers->paySalary($payroll, $cashOrBankAccountId, $date);

            $payroll->update(['status' => 'paid', 'paid_at' => $date ?? now()->toDateString()]);

            return $payroll->fresh();
        });
    }

    /** Advance salary: a payment voucher, Dr Advance to Employee (1310), Cr Cash/Bank, sub-ledger = employee. */
    public function advanceSalary(Employee $employee, float $amount, array $data = []): \App\Models\Voucher
    {
        if ($amount <= 0) {
            throw ValidationException::withMessages(['amount' => 'Advance amount must be greater than zero.']);
        }

        return $this->vouchers->postOperation('advance_payment', array_merge($data, [
            'amount' => $amount,
            'debit_account_code' => '1310',
            'sub_ledger_type' => 'employee',
            'sub_ledger_id' => $employee->id,
            'narration' => $data['narration'] ?? 'Advance salary — ' . ($employee->user->name ?? $employee->employee_code),
        ]));
    }
}
