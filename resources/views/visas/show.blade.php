@extends('layouts.app')
@section('title', __('Visa & MOFA'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ __('Visa & MOFA') }}</h1>
    <div>
        @can('visa.edit')
        <x-button href="{{ route('visas.edit', $record) }}">{{ __('Edit') }}</x-button>
        @endcan
        <x-button href="{{ route('visas.index') }}" variant="secondary">{{ __('Back to List') }}</x-button>
    </div>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card mb-3"><div class="card-body">
    <dl class="row mb-0">
        <dt class="col-sm-4">{{ __('Status') }}</dt><dd class="col-sm-8"><x-badge :status="$record->status" /></dd>
        <dt class="col-sm-4">Recruitment</dt><dd class="col-sm-8">{{ $record->recruitment?->tracking_number ?? '—' }} — {{ $record->recruitment?->passportHolder?->full_name ?? '' }}</dd>
<dt class="col-sm-4">Visa Lot</dt><dd class="col-sm-8">{{ $record->visa_lot_id ?? '—' }}</dd>
<dt class="col-sm-4">Submission Date</dt><dd class="col-sm-8">{{ $record->submission_date ?? '—' }}</dd>
<dt class="col-sm-4">Status</dt><dd class="col-sm-8">{{ $record->status ?? '—' }}</dd>
<dt class="col-sm-4">MOFA Status</dt><dd class="col-sm-8">{{ $record->mofa_status ?? '—' }}</dd>
<dt class="col-sm-4">Remarks</dt><dd class="col-sm-8">{{ $record->remarks ?? '—' }}</dd>
@isset($record->document_path)<dt class="col-sm-4">Visa Document</dt><dd class="col-sm-8"><a href="{{ route('visas.download', [$record, 'document_path']) }}">{{ __('Download') }}</a></dd>@endisset
    </dl>
</div></div>

<hr><h5>{{ __('MOFA Processing') }}</h5>
<form action="{{ route('visas.mofa', $record) }}" method="POST" class="row g-2 mb-3">@csrf
    <div class="col-md-4"><select name="mofa_status" class="form-select">@foreach(['pending','submitted','approved','rejected','returned'] as $s)<option value="{{ $s }}" @selected($record->mofa_status == $s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
    <div class="col-md-4"><input type="text" name="reference_number" class="form-control" placeholder="{{ __('MOFA Reference Number') }}"></div>
    <div class="col-md-4"><x-button type="submit">{{ __('Update MOFA') }}</x-button></div>
</form>
<hr><h5>{{ __('Work Permit') }}</h5>
<form action="{{ route('visas.work-permit', $record) }}" method="POST" class="row g-2 mb-3">@csrf
    <div class="col-md-4"><input type="text" name="permit_number" class="form-control" placeholder="{{ __('Permit Number') }}" required></div>
    <div class="col-md-3"><input type="date" name="issue_date" class="form-control"></div>
    <div class="col-md-3"><input type="date" name="expiry_date" class="form-control"></div>
    <div class="col-md-2"><x-button type="submit">{{ __('Save Permit') }}</x-button></div>
</form>
@if($record->workPermit)<p>{{ __('Permit') }}: {{ $record->workPermit->permit_number }} ({{ $record->workPermit->issue_date }} – {{ $record->workPermit->expiry_date }})</p>@endif
<hr><h5>{{ __('Appointment') }}</h5>
<form action="{{ route('visas.appointment', $record) }}" method="POST" class="row g-2 mb-3">@csrf
    <div class="col-md-4"><input type="date" name="appointment_date" class="form-control" required></div>
    <div class="col-md-4"><input type="text" name="location" class="form-control" placeholder="{{ __('Location') }}"></div>
    <div class="col-md-4"><x-button type="submit">{{ __('Save Appointment') }}</x-button></div>
</form>

@if($recruitment)
    <h5>{{ __('Workflow History') }}</h5>
    <x-workflow-timeline :recruitment="$recruitment" />
@endif
<hr>
<h5>{{ __('Change Status') }}</h5>
<form action="{{ route('visas.status', $record) }}" method="POST" class="row g-2">@csrf
    <div class="col-md-4"><select name="status" class="form-select">@foreach($statuses as $status)<option value="{{ $status }}" @selected($record->status == $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>@endforeach</select></div>
    <div class="col-md-5"><input type="text" name="remarks" class="form-control" placeholder="{{ __('Remarks') }}"></div>
    <div class="col-md-3"><x-button type="submit">{{ __('Update Status') }}</x-button></div>
</form>
<hr>
<x-modal id="cancel-modal" title="{{ __('Cancel Record') }}">
    <p>{{ __('This cancels (archives) the record. It is not deleted.') }}</p>
    <form action="{{ route('visas.destroy', $record) }}" method="POST">@csrf @method('DELETE')<x-button type="submit" variant="danger">{{ __('Confirm Cancel') }}</x-button></form>
</x-modal>
@endsection
