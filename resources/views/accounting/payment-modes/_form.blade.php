@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<form method="POST" action="{{ $action }}">
    @csrf
    @if($method !== 'POST') @method($method) @endif
    <div class="mb-3">
        <label for="name" class="form-label">Payment Mode Name</label>
        <input id="name" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" placeholder="e.g. Cash Counter, Dutch-Bangla Bank" required>
    </div>
    <div class="mb-3">
        <label for="mode_type" class="form-label">Type</label>
        <select id="mode_type" name="mode_type" class="form-select" required>
            <option value="cash" @selected(old('mode_type', $item->mode_type ?? '') === 'cash')>Cash</option>
            <option value="bank" @selected(old('mode_type', $item->mode_type ?? '') === 'bank')>Bank</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="account_id" class="form-label">Linked Cash / Bank Account</label>
        <select id="account_id" name="account_id" class="form-select" required>
            <option value="">Select chart account</option>
            @foreach($accounts as $account)
                <option value="{{ $account->id }}" @selected((string) old('account_id', $item->account_id ?? '') === (string) $account->id)>{{ $account->code }} — {{ $account->name }}</option>
            @endforeach
        </select>
        <div class="form-text">Only active posting accounts under Cash &amp; Bank are listed. To add a new ledger account, use <a href="{{ route('accounting.accounts.create') }}">Add Account</a> first.</div>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select" required>
            <option value="active" @selected(old('status', $item->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $item->status ?? '') === 'inactive')>Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save Payment Mode</button>
    <a href="{{ route('accounting.payment-modes.index') }}" class="btn btn-secondary">Cancel</a>
</form>
