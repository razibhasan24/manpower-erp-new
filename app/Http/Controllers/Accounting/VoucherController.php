<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReverseVoucherRequest;
use App\Http\Requests\StoreVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use App\Models\PaymentMode;
use App\Services\ChartOfAccountsService;
use App\Services\VoucherService;
use Illuminate\Http\Request;

/**
 * Voucher Management (Phase 24). Posted vouchers are immutable: there
 * is deliberately NO edit/update/destroy action — correction is only
 * via an authorised reversal (see reverse()).
 */
class VoucherController extends Controller
{
    public function __construct(
        protected VoucherService $vouchers,
        protected ChartOfAccountsService $chart
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Voucher::class);

        $items = Voucher::with(['journalEntry.lines.account'])
            ->when($request->filled('voucher_type'), fn ($q) => $q->where('voucher_type', $request->voucher_type))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('voucher_date', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('voucher_date', '<=', $request->to))
            ->when($request->filled('branch_id'), fn ($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->filled('search'), fn ($q) => $q->where('voucher_number', 'like', '%' . $request->search . '%'))
            ->orderByDesc('voucher_date')->orderByDesc('id')
            ->paginate(20)->withQueryString();

        if ($request->wantsJson()) {
            return VoucherResource::collection($items);
        }

        return view('accounting.vouchers.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', Voucher::class);

        return view('accounting.vouchers.create', [
            'accounts' => $this->chart->postingAccounts(),
            'paymentModes' => PaymentMode::with('account')->where('status', 'active')->whereHas('account', fn ($query) => $query->where('status', 'active'))->orderBy('mode_type')->orderBy('name')->get(),
            'types' => VoucherService::TYPES,
            'operations' => VoucherService::OPERATIONS,
        ]);
    }

    public function store(StoreVoucherRequest $request)
    {
        $this->authorize('create', Voucher::class);

        $voucher = $this->vouchers->post($request->validated());

        return redirect()->route('accounting.vouchers.show', $voucher)
            ->with('success', "Voucher {$voucher->voucher_number} posted successfully.");
    }

    public function show(Voucher $voucher)
    {
        $this->authorize('view', $voucher);
        $voucher->load(['journalEntry.lines.account', 'journalEntry.lines']);

        if (request()->wantsJson()) {
            return new VoucherResource($voucher);
        }

        return view('accounting.vouchers.show', ['item' => $voucher]);
    }

    /** Post one of the §21.G financial operations (Daily Income, Vendor Payment, ...). */
    public function storeOperation(Request $request)
    {
        $this->authorize('create', Voucher::class);

        $data = $request->validate([
            'operation' => ['required', 'in:' . implode(',', VoucherService::OPERATIONS)],
            'amount' => ['required', 'numeric', 'gt:0'],
            'voucher_date' => ['required', 'date'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'narration' => ['nullable', 'string', 'max:500'],
            'debit_account_id' => ['nullable', 'exists:accounts,id'],
            'credit_account_id' => ['nullable', 'exists:accounts,id'],
            'debit_account_code' => ['nullable', 'string'],
            'credit_account_code' => ['nullable', 'string'],
            'sub_ledger_type' => ['nullable', 'in:agent,passport_holder,vendor,employee'],
            'sub_ledger_id' => ['nullable', 'integer'],
            'reference_type' => ['nullable', 'string', 'max:255'],
            'reference_id' => ['nullable', 'integer'],
            'lines' => ['nullable', 'array'],
            'lines.*.account_id' => ['required_with:lines', 'exists:accounts,id'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0'],
        ]);

        $voucher = $this->vouchers->postOperation($data['operation'], $data);

        return redirect()->route('accounting.vouchers.show', $voucher)
            ->with('success', "Voucher {$voucher->voucher_number} posted successfully.");
    }

    public function reverse(ReverseVoucherRequest $request, Voucher $voucher)
    {
        $this->authorize('reverse', $voucher);

        $reversal = $this->vouchers->reverse($voucher, $request->validated()['reason'] ?? null);

        return redirect()->route('accounting.vouchers.show', $reversal)
            ->with('success', "Voucher {$voucher->voucher_number} reversed by {$reversal->voucher_number}.");
    }
}
