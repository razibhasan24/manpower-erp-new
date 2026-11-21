@extends('layouts.app')
@section('title', 'Create Vendor')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Create Vendor</h1>
    <a href="{{ route('reports.financial.sub-ledger.due-list', ['type' => 'vendor']) }}" class="btn btn-outline-secondary">Back to Vendor Due List</a>
</div>
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('reports.financial.sub-ledger.vendor.store') }}">
        @csrf
        <div class="mb-3"><label for="name" class="form-label">Vendor Name *</label><input id="name" name="name" value="{{ old('name') }}" class="form-control" required maxlength="255"></div>
        <div class="mb-3"><label for="phone" class="form-label">Phone</label><input id="phone" name="phone" value="{{ old('phone') }}" class="form-control" maxlength="30"></div>
        <div class="mb-3"><label for="email" class="form-label">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control"></div>
        <div class="mb-3"><label for="branch_id" class="form-label">Branch</label><select id="branch_id" name="branch_id" class="form-select"><option value="">Select branch</option>@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(old('branch_id', auth()->user()->branch_id)==$branch->id)>{{ $branch->name }}</option>@endforeach</select></div>
        <div class="mb-3"><label for="address" class="form-label">Address</label><textarea id="address" name="address" rows="3" class="form-control">{{ old('address') }}</textarea></div>
        <button type="submit" class="btn btn-primary">Create Vendor & Payable Account</button>
    </form>
</div></div>
@endsection
