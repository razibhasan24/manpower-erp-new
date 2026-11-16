<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePayrollRequest;
use App\Http\Resources\PayrollResource;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\ChartOfAccountsService;
use App\Services\PayrollService;
use Illuminate\Http\Request;

/**
 * Payroll (Phase 26). Flow: compute (draft) -> approve & post (Journal
 * Voucher via VoucherService) -> pay (Salary Payable -> Cash/Bank).
 * A posted payroll is never edited; a wrong payroll is corrected by
 * reversing its voucher (see VoucherController::reverse).
 */
class PayrollController extends Controller
{
    public function __construct(
        protected PayrollService $payroll,
        protected ChartOfAccountsService $chart
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Payroll::class);

        $items = Payroll::with('employee')
            ->when($request->filled('month'), fn ($q) => $q->where('month', $request->month))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('employee_id'), fn ($q) => $q->where('employee_id', $request->employee_id))
            ->orderByDesc('month')->orderByDesc('id')
            ->paginate(20)->withQueryString();

        if ($request->wantsJson()) {
            return PayrollResource::collection($items);
        }

        return view('hrm.payroll.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', Payroll::class);

        return view('hrm.payroll.create', [
            'employees' => Employee::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(StorePayrollRequest $request)
    {
        $this->authorize('create', Payroll::class);
        $data = $request->validated();

        $employee = Employee::findOrFail($data['employee_id']);
        $payroll = $this->payroll->compute($employee, $data['month'], $data);

        return redirect()->route('hrm.payroll.show', $payroll)
            ->with('success', 'Payroll computed as draft.');
    }

    public function show(Payroll $payroll)
    {
        $this->authorize('view', $payroll);
        $payroll->load(['employee', 'voucher']);

        if (request()->wantsJson()) {
            return new PayrollResource($payroll);
        }

        return view('hrm.payroll.show', ['item' => $payroll]);
    }

    public function approve(Payroll $payroll)
    {
        $this->authorize('approve', $payroll);

        $this->payroll->approveAndPost($payroll);

        return redirect()->route('hrm.payroll.show', $payroll)
            ->with('success', 'Payroll approved and posted as a Journal Voucher.');
    }

    public function pay(Request $request, Payroll $payroll)
    {
        $this->authorize('pay', $payroll);

        $data = $request->validate([
            'account_id' => ['required', 'exists:accounts,id'],
            'date' => ['nullable', 'date'],
        ]);

        $this->payroll->pay($payroll, (int) $data['account_id'], $data['date'] ?? null);

        return redirect()->route('hrm.payroll.show', $payroll)
            ->with('success', 'Salary paid and voucher posted.');
    }

    public function advance(Request $request, Employee $employee)
    {
        $this->authorize('create', Payroll::class);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
            'voucher_date' => ['nullable', 'date'],
            'narration' => ['nullable', 'string', 'max:500'],
        ]);

        $voucher = $this->payroll->advanceSalary($employee, (float) $data['amount'], $data);

        return redirect()->route('accounting.vouchers.show', $voucher)
            ->with('success', "Advance salary of {$data['amount']} posted as {$voucher->voucher_number}.");
    }
}
