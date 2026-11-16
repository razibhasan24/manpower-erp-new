@extends('layouts.app')
@section('title', __('Selection & Grading'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ __('Selection & Grading') }}</h1>
    <div>
        @can('selection.edit')
        <x-button href="{{ route('selections.edit', $record) }}">{{ __('Edit') }}</x-button>
        @endcan
        <x-button href="{{ route('selections.index') }}" variant="secondary">{{ __('Back to List') }}</x-button>
    </div>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card mb-3"><div class="card-body">
    <dl class="row mb-0">
        <dt class="col-sm-4">{{ __('Status') }}</dt><dd class="col-sm-8"><x-badge :status="$record->status" /></dd>
        <dt class="col-sm-4">Recruitment</dt><dd class="col-sm-8">{{ $record->recruitment?->tracking_number ?? '—' }} — {{ $record->recruitment?->passportHolder?->full_name ?? '' }}</dd>
<dt class="col-sm-4">Grade</dt><dd class="col-sm-8">{{ $record->grade ?? '—' }}</dd>
<dt class="col-sm-4">Status</dt><dd class="col-sm-8">{{ $record->status ?? '—' }}</dd>
<dt class="col-sm-4">Decided At</dt><dd class="col-sm-8">{{ $record->decided_at ?? '—' }}</dd>
<dt class="col-sm-4">Remarks</dt><dd class="col-sm-8">{{ $record->remarks ?? '—' }}</dd>
    </dl>
</div></div>

@if($recruitment)
<hr><h5>{{ __('Financial Responsibility') }}</h5>
<dl class="row">
    <dt class="col-sm-4">{{ __('Responsible Party') }}</dt><dd class="col-sm-8">{{ $recruitment->financial_responsible_type ?? __('Undecided') }} ({{ $recruitment->financial_responsible_id ?? '—' }})</dd>
    <dt class="col-sm-4">{{ __('Total Cost') }}</dt><dd class="col-sm-8">{{ $recruitment->total_cost ?? '—' }}</dd>
    <dt class="col-sm-4">{{ __('Advance') }}</dt><dd class="col-sm-8">{{ $recruitment->advance_amount ?? '—' }}</dd>
    <dt class="col-sm-4">{{ __('Remaining Due') }}</dt><dd class="col-sm-8">{{ $recruitment->remaining_due ?? '—' }}</dd>
</dl>
@endif
<hr><h5>{{ __('Record Selection Decision') }}</h5>
<form action="{{ route('selections.decide', $record) }}" method="POST" class="row g-2 mb-3">@csrf
    <input type="hidden" name="recruitment_id" value="{{ $record->recruitment_id }}">
    <div class="col-md-3"><input type="text" name="grade" class="form-control" placeholder="{{ __('Grade') }}" value="{{ $record->grade }}" required></div>
    <div class="col-md-3"><select name="status" class="form-select">@foreach($statuses as $status)<option value="{{ $status }}" @selected($record->status == $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
    <div class="col-md-3"><select name="financial_responsible_type" class="form-select"><option value="agent" @selected($record->recruitment?->financial_responsible_type == 'agent')>{{ __('Agent') }}</option><option value="passport_holder" @selected($record->recruitment?->financial_responsible_type == 'passport_holder')>{{ __('Passport Holder') }}</option></select></div>
    <div class="col-md-3"><input type="number" name="financial_responsible_id" class="form-control" placeholder="{{ __('Responsible Party ID') }}" value="{{ $record->recruitment?->financial_responsible_id }}"></div>
    <div class="col-12"><x-button type="submit">{{ __('Save Decision') }}</x-button> <a href="{{ route('selections.criteria') }}">{{ __('Manage grading criteria') }}</a></div>
</form>

@if($recruitment)
    <h5>{{ __('Workflow History') }}</h5>
    <x-workflow-timeline :recruitment="$recruitment" />
@endif
<hr>
<h5>{{ __('Change Status') }}</h5>
<form action="{{ route('selections.status', $record) }}" method="POST" class="row g-2">@csrf
    <div class="col-md-4"><select name="status" class="form-select">@foreach($statuses as $status)<option value="{{ $status }}" @selected($record->status == $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>@endforeach</select></div>
    <div class="col-md-5"><input type="text" name="remarks" class="form-control" placeholder="{{ __('Remarks') }}"></div>
    <div class="col-md-3"><x-button type="submit">{{ __('Update Status') }}</x-button></div>
</form>
<hr>
<x-modal id="cancel-modal" title="{{ __('Cancel Record') }}">
    <p>{{ __('This cancels (archives) the record. It is not deleted.') }}</p>
    <form action="{{ route('selections.destroy', $record) }}" method="POST">@csrf @method('DELETE')<x-button type="submit" variant="danger">{{ __('Confirm Cancel') }}</x-button></form>
</x-modal>
@endsection
