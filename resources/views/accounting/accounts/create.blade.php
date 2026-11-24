@extends('layouts.app')

@section('title', 'New Account')

@section('content')
    {{-- ✅ Header Section --}}
    <div class="mb-4">
        <h1 class="h3 mb-1">New Account</h1>
        <p class="text-muted small mb-0">Create a new ledger account for your chart of accounts.</p>
    </div>

    {{-- ✅ Form Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('accounting.accounts.store') }}">
                @csrf

                <div class="row g-4">
                    {{-- Code --}}
                    <div class="col-md-6">
                        <label for="code" class="form-label fw-semibold">Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code" value="{{ old('code', $item->code ?? '') }}"
                            class="form-control @error('code') is-invalid @enderror" placeholder="e.g. 1010">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Name --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $item->name ?? '') }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Cash in Hand">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Parent Account --}}
                    <div class="col-md-6">
                        <label for="parent_id" class="form-label fw-semibold">Parent Account</label>
                        <select name="parent_id" id="parent_id"
                            class="form-select @error('parent_id') is-invalid @enderror">
                            <option value="">— Select Parent —</option>
                            @foreach ($parents as $opt)
                                <option value="{{ $opt->id }}" @selected((string) old('parent_id', $item->parent_id ?? '') === (string) $opt->id)>
                                    {{ $opt->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Account Type --}}
                    <div class="col-md-6">
                        <label for="account_type_id" class="form-label fw-semibold">Account Type <span
                                class="text-danger">*</span></label>
                        <select name="account_type_id" id="account_type_id"
                            class="form-select @error('account_type_id') is-invalid @enderror">
                            <option value="">— Select Type —</option>
                            @foreach ($types as $opt)
                                <option value="{{ $opt->id }}" @selected((string) old('account_type_id', $item->account_type_id ?? '') === (string) $opt->id)>
                                    {{ $opt->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('account_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Normal Balance --}}
                    <div class="col-md-6">
                        <label for="normal_balance" class="form-label fw-semibold">Normal Balance <span
                                class="text-danger">*</span></label>
                        <select name="normal_balance" id="normal_balance"
                            class="form-select @error('normal_balance') is-invalid @enderror">
                            <option value="debit" @selected(old('normal_balance', $item->normal_balance ?? '') == 'debit')>Debit</option>
                            <option value="credit" @selected(old('normal_balance', $item->normal_balance ?? '') == 'credit')>Credit</option>
                        </select>
                        @error('normal_balance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="active" @selected(old('status', $item->status ?? '') == 'active')>Active</option>
                            <option value="inactive" @selected(old('status', $item->status ?? '') == 'inactive')>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <x-button variant="success" type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-lg me-1"></i> Save
                    </x-button>
                    <x-button variant='danger' href="{{ route('accounting.accounts.index') }}" class="btn btn-danger px-3">
                        Cancel
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
