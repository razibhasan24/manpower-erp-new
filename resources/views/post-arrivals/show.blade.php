@extends('layouts.app')
@section('title', __('Post Arrival Support'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ __('Post Arrival Support') }}</h1>
    <div>
        @can('post-arrival.edit')
        <x-button href="{{ route('post-arrivals.edit', $record) }}">{{ __('Edit') }}</x-button>
        @endcan
        <x-button href="{{ route('post-arrivals.index') }}" variant="secondary">{{ __('Back to List') }}</x-button>
    </div>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card mb-3"><div class="card-body">
    <dl class="row mb-0">
        <dt class="col-sm-4">{{ __('Status') }}</dt><dd class="col-sm-8"><x-badge :status="$record->status" /></dd>
        <dt class="col-sm-4">Recruitment</dt><dd class="col-sm-8">{{ $record->recruitment?->tracking_number ?? '—' }} — {{ $record->recruitment?->passportHolder?->full_name ?? '' }}</dd>
<dt class="col-sm-4">Arrival Date</dt><dd class="col-sm-8">{{ $record->arrival_date ?? '—' }}</dd>
<dt class="col-sm-4">Airport Pickup</dt><dd class="col-sm-8">{{ $record->airport_pickup ? __('Yes') : __('No') }}</dd>
<dt class="col-sm-4">Employer Contact</dt><dd class="col-sm-8">{{ $record->employer_contact ?? '—' }}</dd>
<dt class="col-sm-4">Accommodation</dt><dd class="col-sm-8">{{ $record->accommodation ?? '—' }}</dd>
<dt class="col-sm-4">Candidate Feedback</dt><dd class="col-sm-8">{{ $record->feedback ?? '—' }}</dd>
<dt class="col-sm-4">Status</dt><dd class="col-sm-8">{{ $record->status ?? '—' }}</dd>
    </dl>
</div></div>

<hr><h5>{{ __('Complaints & Follow-up') }}</h5>
@foreach($record->complaints ?? [] as $complaint)<p><strong>{{ $complaint->subject }}</strong> — <x-badge :status="$complaint->status" /> {{ $complaint->description }}</p>@endforeach
<form action="{{ route('post-arrivals.complaint', $record) }}" method="POST" class="row g-2 mb-3">@csrf
    <div class="col-md-4"><input type="text" name="subject" class="form-control" placeholder="{{ __('Complaint subject') }}" required></div>
    <div class="col-md-5"><input type="text" name="description" class="form-control" placeholder="{{ __('Description') }}" required></div>
    <div class="col-md-3"><x-button type="submit">{{ __('Record Complaint') }}</x-button></div>
</form>
<form action="{{ route('post-arrivals.support', $record) }}" method="POST" class="row g-2">@csrf
    <div class="col-md-4"><input type="text" name="type" class="form-control" placeholder="{{ __('Support type') }}" required></div>
    <div class="col-md-4"><input type="text" name="description" class="form-control" placeholder="{{ __('Details') }}"></div>
    <div class="col-md-4"><x-button type="submit">{{ __('Record Follow-up') }}</x-button></div>
</form>

@if($recruitment)
    <h5>{{ __('Workflow History') }}</h5>
    <x-workflow-timeline :recruitment="$recruitment" />
@endif
<hr>
<h5>{{ __('Change Status') }}</h5>
<form action="{{ route('post-arrivals.status', $record) }}" method="POST" class="row g-2">@csrf
    <div class="col-md-4"><select name="status" class="form-select">@foreach($statuses as $status)<option value="{{ $status }}" @selected($record->status == $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>@endforeach</select></div>
    <div class="col-md-5"><input type="text" name="remarks" class="form-control" placeholder="{{ __('Remarks') }}"></div>
    <div class="col-md-3"><x-button type="submit">{{ __('Update Status') }}</x-button></div>
</form>
<hr>
<x-modal id="cancel-modal" title="{{ __('Cancel Record') }}">
    <p>{{ __('This cancels (archives) the record. It is not deleted.') }}</p>
    <form action="{{ route('post-arrivals.destroy', $record) }}" method="POST">@csrf @method('DELETE')<x-button type="submit" variant="danger">{{ __('Confirm Cancel') }}</x-button></form>
</x-modal>
@endsection
