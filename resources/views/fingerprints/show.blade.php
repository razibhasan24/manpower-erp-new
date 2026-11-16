@extends('layouts.app')
@section('title', __('Fingerprint'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ __('Fingerprint') }}</h1>
    <div>
        @can('fingerprint.edit')
        <x-button href="{{ route('fingerprints.edit', $record) }}">{{ __('Edit') }}</x-button>
        @endcan
        <x-button href="{{ route('fingerprints.index') }}" variant="secondary">{{ __('Back to List') }}</x-button>
    </div>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card mb-3"><div class="card-body">
    <dl class="row mb-0">
        <dt class="col-sm-4">{{ __('Status') }}</dt><dd class="col-sm-8"><x-badge :status="$record->status" /></dd>
        <dt class="col-sm-4">Recruitment</dt><dd class="col-sm-8">{{ $record->recruitment?->tracking_number ?? '—' }} — {{ $record->recruitment?->passportHolder?->full_name ?? '' }}</dd>
<dt class="col-sm-4">Appointment Date</dt><dd class="col-sm-8">{{ $record->appointment_date ?? '—' }}</dd>
<dt class="col-sm-4">Submission Date</dt><dd class="col-sm-8">{{ $record->submission_date ?? '—' }}</dd>
<dt class="col-sm-4">Status</dt><dd class="col-sm-8">{{ $record->status ?? '—' }}</dd>
<dt class="col-sm-4">Reference Number</dt><dd class="col-sm-8">{{ $record->reference_number ?? '—' }}</dd>
<dt class="col-sm-4">Remarks</dt><dd class="col-sm-8">{{ $record->remarks ?? '—' }}</dd>
@isset($record->document_path)<dt class="col-sm-4">Fingerprint Document</dt><dd class="col-sm-8"><a href="{{ route('fingerprints.download', [$record, 'document_path']) }}">{{ __('Download') }}</a></dd>@endisset
    </dl>
</div></div>

@if($recruitment)
    <h5>{{ __('Workflow History') }}</h5>
    <x-workflow-timeline :recruitment="$recruitment" />
@endif
<hr>
<h5>{{ __('Change Status') }}</h5>
<form action="{{ route('fingerprints.status', $record) }}" method="POST" class="row g-2">@csrf
    <div class="col-md-4"><select name="status" class="form-select">@foreach($statuses as $status)<option value="{{ $status }}" @selected($record->status == $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>@endforeach</select></div>
    <div class="col-md-5"><input type="text" name="remarks" class="form-control" placeholder="{{ __('Remarks') }}"></div>
    <div class="col-md-3"><x-button type="submit">{{ __('Update Status') }}</x-button></div>
</form>
<hr>
<x-modal id="cancel-modal" title="{{ __('Cancel Record') }}">
    <p>{{ __('This cancels (archives) the record. It is not deleted.') }}</p>
    <form action="{{ route('fingerprints.destroy', $record) }}" method="POST">@csrf @method('DELETE')<x-button type="submit" variant="danger">{{ __('Confirm Cancel') }}</x-button></form>
</x-modal>
@endsection
