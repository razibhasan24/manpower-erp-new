<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\AccountType;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(protected AccountService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Account::class);

        $items = Account::query()
            ->with(['type', 'parent'])
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        if ($request->wantsJson()) {
            return AccountResource::collection($items);
        }

        return view('accounting.accounts.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', Account::class);

        return view('accounting.accounts.create', $this->formData());
    }

    public function store(StoreAccountRequest $request)
    {
        $this->authorize('create', Account::class);

        $item = $this->service->create($request->validated());

        return redirect()->route('accounting.accounts.show', $item)
            ->with('success', 'Account created successfully.');
    }

    public function show(Account $item)
    {
        $this->authorize('view', $item);

        if (request()->wantsJson()) {
            return new AccountResource($item);
        }

        return view('accounting.accounts.show', compact('item'));
    }

    public function edit(Account $item)
    {
        $this->authorize('update', $item);

        return view('accounting.accounts.edit', array_merge(['item' => $item], $this->formData($item)));
    }

    public function update(StoreAccountRequest $request, Account $item)
    {
        $this->authorize('update', $item);

        $this->service->update($item, $request->validated());

        return redirect()->route('accounting.accounts.show', $item)
            ->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $item)
    {
        $this->authorize('delete', $item);

        $deleted = $this->service->delete($item);

        return redirect()->route('accounting.accounts.index')
            ->with('success', $deleted ? 'Account deleted.' : 'Account has posted records, so it was deactivated instead of deleted.');
    }

    protected function formData(?Account $item = null): array
    {
        return [
            'types' => AccountType::orderBy('id')->get(),
            'parents' => Account::orderBy('code')->get(),
        ];
    }
}
