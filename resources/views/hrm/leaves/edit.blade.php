@extends('layouts.app')
@section('title', 'Edit Leave')
@section('content')
<h1 class="mb-3">Edit Leave</h1>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('hrm.leaves.update', $item) }}">
    @csrf
    @method('PUT')
    <div class="mb-3"><label class="form-label">Employee</label><select name="employee_id" class="form-select"><option value="">— Select —</option>@foreach($employees as $opt)<option value="{{ $opt->id }}" {{ (string) old('employee_id', $item->employee_id ?? '') === (string) $opt->id ? 'selected' : '' }}>{{ $opt->name }}</option>@endforeach</select>@error('employee_id')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Leave Type</label><input type="text" name="type" value="{{ old('type', $item->type ?? '') }}" class="form-control">@error('type')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">From Date</label><input type="date" name="start_date" value="{{ old('start_date', $item->start_date ?? '') }}" class="form-control">@error('start_date')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">To Date</label><input type="date" name="end_date" value="{{ old('end_date', $item->end_date ?? '') }}" class="form-control">@error('end_date')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Reason</label><textarea name="reason" class="form-control" rows="3">{{ old('reason', $item->reason ?? '') }}</textarea>@error('reason')<div class="text-danger small">{{ $message }}</div>@enderror</div><div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="pending" {{ old('status', $item->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option><option value="approved" {{ old('status', $item->status ?? '') == 'approved' ? 'selected' : '' }}>Approved</option><option value="rejected" {{ old('status', $item->status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option><option value="cancelled" {{ old('status', $item->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option></select>@error('status')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('hrm.leaves.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</div></div>
@endsection
