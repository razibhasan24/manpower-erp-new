<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\PaymentMode;
use App\Services\ChartOfAccountsService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PaymentModeController extends Controller
{
    public function __construct(protected ChartOfAccountsService $chart) {}

    public function index()
    {
        $this->authorize('viewAny', Account::class);
        $items = PaymentMode::with('account')->orderBy('mode_type')->orderBy('name')->paginate(20);

        return view('accounting.payment-modes.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', Account::class);

        return view('accounting.payment-modes.create', $this->formData());
    }

    public function store(Request $request)
    {
        $this->authorize('create', Account::class);
        PaymentMode::create($this->validated($request));

        return redirect()->route('accounting.payment-modes.index')->with('success', 'Payment mode added.');
    }

    public function edit(PaymentMode $paymentMode)
    {
        $this->authorize('update', $paymentMode->account);

        return view('accounting.payment-modes.edit', array_merge(
            ['item' => $paymentMode],
            $this->formData($paymentMode)
        ));
    }

    public function update(Request $request, PaymentMode $paymentMode)
    {
        $this->authorize('update', $paymentMode->account);
        $paymentMode->update($this->validated($request, $paymentMode));

        return redirect()->route('accounting.payment-modes.index')->with('success', 'Payment mode updated.');
    }

    public function destroy(PaymentMode $paymentMode)
    {
        $this->authorize('delete', $paymentMode->account);
        $paymentMode->delete();

        return redirect()->route('accounting.payment-modes.index')->with('success', 'Payment mode removed.');
    }

    protected function validated(Request $request, ?PaymentMode $item = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('payment_modes', 'name')->ignore($item?->id)->whereNull('deleted_at')],
            'mode_type' => ['required', Rule::in(['cash', 'bank'])],
            'account_id' => ['required', 'integer', Rule::exists('accounts', 'id')],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $account = Account::where('status', 'active')->find($data['account_id']);
        if (! $account || ! str_starts_with($account->code, '11')) {
            throw ValidationException::withMessages(['account_id' => 'Select an active Cash & Bank chart account.']);
        }

        $duplicateAccount = PaymentMode::where('account_id', $account->id)
            ->when($item, fn ($query) => $query->where('id', '!=', $item->id))
            ->exists();
        if ($duplicateAccount) {
            throw ValidationException::withMessages(['account_id' => 'This chart account is already assigned to another payment mode.']);
        }

        return $data;
    }

    protected function formData(?PaymentMode $item = null): array
    {
        $linkedIds = PaymentMode::when($item, fn ($query) => $query->where('id', '!=', $item->id))
            ->pluck('account_id');
        $accounts = $this->chart->postingAccounts()
            ->filter(fn (Account $account) => str_starts_with($account->code, '11') && ! $linkedIds->contains($account->id))
            ->when($item, fn ($collection) => $collection->push($item->account)->filter()->unique('id'))
            ->sortBy('code')->values();

        return compact('accounts');
    }
}
