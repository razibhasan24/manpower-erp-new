<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountTypeRequest;
use App\Http\Resources\AccountTypeResource;
use App\Models\AccountType;
use Illuminate\Http\Request;

/**
 * Account Types (Phase 23, §21.B): the five seeded types — Assets,
 * Liabilities, Equity, Income, Expenses. Seeded, not user-editable:
 * create/update/delete are intentionally unavailable; the routes expose
 * read-only listing so the UI never offers a dead button.
 */
class AccountTypeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', AccountType::class);

        $items = AccountType::orderBy('id')->paginate(20);

        if ($request->wantsJson()) {
            return AccountTypeResource::collection($items);
        }

        return view('accounting.account-types.index', compact('items'));
    }

    public function show(AccountType $accountType)
    {
        $this->authorize('view', $accountType);

        if (request()->wantsJson()) {
            return new AccountTypeResource($accountType->load('accounts'));
        }

        return view('accounting.account-types.show', ['item' => $accountType]);
    }
}
