<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReverseVoucherRequest;
use App\Http\Requests\StoreVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use App\Models\PaymentMode;
use App\Models\Branch;
use App\Services\ChartOfAccountsService;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

        $items = Voucher::visibleTo($request->user())->with(['journalEntry.lines.account'])
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
            'branches' => Branch::visibleTo(request()->user())->orderBy('name')->get(),
            'item' => null,
            'defaultType' => in_array(request('type'), VoucherService::TYPES, true) ? request('type') : 'payment',
        ]);
    }

    public function store(StoreVoucherRequest $request)
    {
        $this->authorize('create', Voucher::class);
        $data = $request->validated();
        $this->assertBranchAllowed($request, $data['branch_id'] ?? null);
        $attachment = $data['attachment'] ?? null;
        unset($data['attachment']);

        $voucher = $this->vouchers->createDraft($this->normalizeVoucherData($data));
        if ($attachment instanceof UploadedFile) $this->saveAttachment($voucher, $attachment);

        return redirect()->route('accounting.vouchers.show', $voucher)
            ->with('success', "Voucher {$voucher->voucher_number} saved as draft. It will affect accounts after approval.");
    }

    public function edit(Voucher $voucher)
    {
        $this->authorize('update', $voucher);
        $voucher->setAttribute('form_lines', $this->displayLines($voucher));
        $firstDraftLine = $voucher->draft_lines[0] ?? [];
        $voucher->setAttribute('form_party_type', $firstDraftLine['sub_ledger_type'] ?? null);
        $voucher->setAttribute('form_party_id', $firstDraftLine['sub_ledger_id'] ?? null);
        return view('accounting.vouchers.create', [
            'accounts' => $this->chart->postingAccounts(),
            'paymentModes' => PaymentMode::with('account')->where('status', 'active')->whereHas('account', fn ($query) => $query->where('status', 'active'))->orderBy('mode_type')->orderBy('name')->get(),
            'types' => VoucherService::TYPES,
            'branches' => Branch::visibleTo(request()->user())->orderBy('name')->get(),
            'item' => $voucher,
            'defaultType' => $voucher->voucher_type,
        ]);
    }

    public function update(StoreVoucherRequest $request, Voucher $voucher)
    {
        $this->authorize('update', $voucher);
        $data = $request->validated();
        $this->assertBranchAllowed($request, $data['branch_id'] ?? $voucher->branch_id);
        $attachment = $data['attachment'] ?? null;
        unset($data['attachment']);
        $this->vouchers->updateDraft($voucher, $this->normalizeVoucherData($data));
        if ($attachment instanceof UploadedFile) $this->saveAttachment($voucher, $attachment);
        return redirect()->route('accounting.vouchers.show', $voucher)->with('success', 'Draft voucher updated. It has not affected accounts.');
    }

    public function approve(Request $request, Voucher $voucher)
    {
        $this->authorize('approve', $voucher);
        $this->vouchers->approveDraft($voucher);
        return redirect()->route('accounting.vouchers.show', $voucher)->with('success', "Voucher {$voucher->voucher_number} approved and posted to accounts.");
    }

    public function reject(Request $request, Voucher $voucher)
    {
        $this->authorize('reject', $voucher);
        $this->vouchers->rejectDraft($voucher);
        return redirect()->route('accounting.vouchers.show', $voucher)->with('success', "Voucher {$voucher->voucher_number} rejected.");
    }

    protected function assertBranchAllowed(Request $request, mixed $branchId): void
    {
        if ($branchId) {
            Branch::visibleTo($request->user())->findOrFail((int) $branchId);
        }
    }

    protected function normalizeVoucherData(array $data): array
    {
        $inputLines = $data['lines'];
        $lines = [];
        if (in_array($data['voucher_type'], ['payment', 'receipt'], true)) {
            $mode = PaymentMode::with('account')->where('status', 'active')->findOrFail($data['payment_mode_id']);
            if (! $mode->account || $mode->account->status !== 'active') {
                throw ValidationException::withMessages(['payment_mode_id' => 'Select an active Cash / Bank payment mode.']);
            }
            $total = 0.0;
            foreach ($inputLines as $line) {
                if ((int) $line['account_id'] === (int) $mode->account_id) {
                    throw ValidationException::withMessages(['lines' => 'Select a different account from the selected Cash / Bank payment mode.']);
                }
                $amount = round((float) $line['amount'], 2);
                $total += $amount;
                $lines[] = [
                    'account_id' => $line['account_id'],
                    'debit' => $data['voucher_type'] === 'payment' ? $amount : 0,
                    'credit' => $data['voucher_type'] === 'receipt' ? $amount : 0,
                    'narration' => $line['narration'] ?? $data['narration'],
                ];
            }
            $lines[] = [
                'account_id' => $mode->account_id,
                'debit' => $data['voucher_type'] === 'receipt' ? round($total, 2) : 0,
                'credit' => $data['voucher_type'] === 'payment' ? round($total, 2) : 0,
                'narration' => $data['payee'] ?: $data['narration'],
            ];
        } elseif ($data['voucher_type'] === 'contra') {
            foreach ($inputLines as $line) {
                $lines[] = ['account_id' => $line['debit_account_id'], 'debit' => $line['debit_amount'], 'credit' => 0, 'narration' => $line['narration'] ?? $data['narration']];
                $lines[] = ['account_id' => $line['credit_account_id'], 'debit' => 0, 'credit' => $line['credit_amount'], 'narration' => $line['narration'] ?? $data['narration']];
            }
        } else {
            foreach ($inputLines as $line) {
                $lines[] = [
                    'account_id' => $line['account_id'],
                    'debit' => $line['entry_type'] === 'debit' ? $line['amount'] : 0,
                    'credit' => $line['entry_type'] === 'credit' ? $line['amount'] : 0,
                    'sub_ledger_type' => $line['sub_ledger_type'] ?? $data['party_type'] ?? null,
                    'sub_ledger_id' => $line['sub_ledger_id'] ?? $data['party_id'] ?? null,
                    'narration' => $line['narration'] ?? $data['narration'],
                ];
            }
        }
        $data['lines'] = $lines;
        return $data;
    }

    protected function displayLines(Voucher $voucher): array
    {
        $lines = $voucher->draft_lines ?? [];
        if (in_array($voucher->voucher_type, ['payment', 'receipt'], true)) {
            $modeAccountId = $voucher->paymentMode?->account_id;
            $visible = array_filter($lines, fn ($line) => (int) $line['account_id'] !== (int) $modeAccountId);
            return array_values(array_map(fn ($line) => [
                'account_id' => $line['account_id'], 'amount' => $line['debit'] ?: $line['credit'], 'narration' => $line['narration'] ?? '',
            ], $visible));
        }
        if ($voucher->voucher_type === 'contra') {
            $result = [];
            for ($i = 0; $i + 1 < count($lines); $i += 2) {
                $result[] = [
                    'debit_account_id' => $lines[$i]['account_id'], 'debit_amount' => $lines[$i]['debit'],
                    'credit_account_id' => $lines[$i + 1]['account_id'], 'credit_amount' => $lines[$i + 1]['credit'],
                    'narration' => $lines[$i]['narration'] ?? '',
                ];
            }
            return $result ?: [['debit_account_id' => '', 'debit_amount' => '', 'credit_account_id' => '', 'credit_amount' => '']];
        }
        return array_values(array_map(fn ($line) => [
            'account_id' => $line['account_id'], 'entry_type' => (float) $line['debit'] > 0 ? 'debit' : 'credit',
            'amount' => (float) $line['debit'] > 0 ? $line['debit'] : $line['credit'],
            'sub_ledger_type' => $line['sub_ledger_type'] ?? '', 'sub_ledger_id' => $line['sub_ledger_id'] ?? '',
            'narration' => $line['narration'] ?? '',
        ], $lines));
    }

    protected function saveAttachment(Voucher $voucher, UploadedFile $file): void
    {
        $oldPath = $voucher->attachment_path;
        $path = $file->store('vouchers/'.$voucher->id, 'private');
        $voucher->forceFill(['attachment_path' => $path, 'attachment_original_name' => $file->getClientOriginalName()])->save();
        if ($oldPath && $oldPath !== $path) Storage::disk('private')->delete($oldPath);
    }

    public function downloadAttachment(Voucher $voucher)
    {
        $this->authorize('view', $voucher);
        abort_unless($voucher->attachment_path && Storage::disk('private')->exists($voucher->attachment_path), 404);
        return Storage::disk('private')->download($voucher->attachment_path, $voucher->attachment_original_name ?? basename($voucher->attachment_path));
    }

    public function show(Voucher $voucher)
    {
        $this->authorize('view', $voucher);
        $voucher->load(['journalEntry.lines.account', 'journalEntry.lines']);
        $draftAccounts = \App\Models\Account::whereIn('id', collect($voucher->draft_lines ?? [])->pluck('account_id')->filter())->get()->keyBy('id');

        if (request()->wantsJson()) {
            return new VoucherResource($voucher);
        }

        return view('accounting.vouchers.show', ['item' => $voucher, 'draftAccounts' => $draftAccounts]);
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
        $this->assertBranchAllowed($request, $data['branch_id'] ?? null);

        $voucher = $this->vouchers->createDraftOperation($data['operation'], $data);

        return redirect()->route('accounting.vouchers.show', $voucher)
            ->with('success', "Voucher {$voucher->voucher_number} saved as draft. It will affect accounts after approval.");
    }

    public function reverse(ReverseVoucherRequest $request, Voucher $voucher)
    {
        $this->authorize('reverse', $voucher);

        $reversal = $this->vouchers->reverse($voucher, $request->validated()['reason'] ?? null);

        return redirect()->route('accounting.vouchers.show', $reversal)
            ->with('success', "Voucher {$voucher->voucher_number} reversed by {$reversal->voucher_number}.");
    }
}
