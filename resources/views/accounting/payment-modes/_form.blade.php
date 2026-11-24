@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
            <strong>{{ __('Please fix the following errors:') }}</strong>
        </div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="row g-4">
        {{-- Payment Mode Name --}}
        <div class="col-md-6">
            <label for="name" class="form-label fw-semibold">{{ __('Payment Mode Name') }} <span
                    class="text-danger">*</span></label>
            <input id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $item->name ?? '') }}" placeholder="e.g. Cash Counter, Dutch-Bangla Bank"
                required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Type --}}
        <div class="col-md-6">
            <label for="mode_type" class="form-label fw-semibold">{{ __('Type') }} <span
                    class="text-danger">*</span></label>
            <select id="mode_type" name="mode_type" class="form-select @error('mode_type') is-invalid @enderror"
                required>
                <option value="cash" @selected(old('mode_type', $item->mode_type ?? '') === 'cash')>Cash</option>
                <option value="bank" @selected(old('mode_type', $item->mode_type ?? '') === 'bank')>Bank</option>
            </select>
            @error('mode_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Linked Account --}}
        <div class="col-md-12">
            <label for="account_id" class="form-label fw-semibold">{{ __('Linked Cash / Bank Account') }} <span
                    class="text-danger">*</span></label>
            <select id="account_id" name="account_id" class="form-select @error('account_id') is-invalid @enderror"
                required>
                <option value="">{{ __('Select chart account') }}</option>
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}" @selected((string) old('account_id', $item->account_id ?? '') === (string) $account->id)>
                        {{ $account->code }} — {{ $account->name }}
                    </option>
                @endforeach
            </select>
            @error('account_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text small text-muted mt-2">
                <i class="bi bi-info-circle me-1"></i> Only active posting accounts under Cash & Bank are listed. To add
                a new ledger account, use <a href="{{ route('accounting.accounts.create') }}"
                    class="fw-medium text-decoration-none">Add Account</a> first.
            </div>
        </div>

        {{-- Status --}}
        <div class="col-md-6">
            <label for="status" class="form-label fw-semibold">{{ __('Status') }} <span
                    class="text-danger">*</span></label>
            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="active" @selected(old('status', $item->status ?? 'active') === 'active')>Active</option>
                <option value="inactive" @selected(old('status', $item->status ?? '') === 'inactive')>Inactive</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="d-flex gap-2 mt-4 pt-3 border-top">
        <x-button variant="success" type="submit" class="btn btn-success px-4">
            <i class="bi bi-check-lg me-1"></i> {{ __('Save Payment Mode') }}
        </x-button>
        <x-button href="{{ route('accounting.payment-modes.index') }}" variant="danger" class="btn btn-danger px-3">
            {{ __('Cancel') }}
        </x-button>
    </div>
</form>
