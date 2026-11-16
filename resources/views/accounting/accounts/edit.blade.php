@extends('layouts.app')
@section('title', 'Edit Account')
@section('content')
<h1 class="mb-3">Edit Account</h1>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('accounting.accounts.update', $item) }}">
    @csrf
    @method('PUT')
    <div class="mb-3"><label class="form-label">Code</label><input type="text" name="code" value="{{ old('code', $item->code ?? '') }}" class="form-control">@error('code')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" class="form-control">@error('name')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Parent Account</label><select name="parent_id" class="form-select"><option value="">— Select —</option>@foreach($parents as $opt)<option value="{{ $opt->id }}" {{ (string) old('parent_id', $item->parent_id ?? '') === (string) $opt->id ? 'selected' : '' }}>{{ $opt->name }}</option>@endforeach</select>@error('parent_id')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Account Type</label><select name="account_type_id" class="form-select"><option value="">— Select —</option>@foreach($types as $opt)<option value="{{ $opt->id }}" {{ (string) old('account_type_id', $item->account_type_id ?? '') === (string) $opt->id ? 'selected' : '' }}>{{ $opt->name }}</option>@endforeach</select>@error('account_type_id')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Normal Balance</label><select name="normal_balance" class="form-select"><option value="debit" {{ old('normal_balance', $item->normal_balance ?? '') == 'debit' ? 'selected' : '' }}>Debit</option><option value="credit" {{ old('normal_balance', $item->normal_balance ?? '') == 'credit' ? 'selected' : '' }}>Credit</option></select>@error('normal_balance')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" {{ old('status', $item->status ?? '') == 'active' ? 'selected' : '' }}>Active</option><option value="inactive" {{ old('status', $item->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option></select>@error('status')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('accounting.accounts.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</div></div>
@endsection
