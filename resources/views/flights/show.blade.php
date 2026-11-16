@extends('layouts.app')
@section('title', __('Flight Booking'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ __('Flight Booking') }}</h1>
    <div>
        @can('flight.edit')
        <x-button href="{{ route('flights.edit', $record) }}">{{ __('Edit') }}</x-button>
        @endcan
        <x-button href="{{ route('flights.index') }}" variant="secondary">{{ __('Back to List') }}</x-button>
    </div>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card mb-3"><div class="card-body">
    <dl class="row mb-0">
        <dt class="col-sm-4">{{ __('Status') }}</dt><dd class="col-sm-8"><x-badge :status="$record->status" /></dd>
        <dt class="col-sm-4">Recruitment</dt><dd class="col-sm-8">{{ $record->recruitment?->tracking_number ?? '—' }} — {{ $record->recruitment?->passportHolder?->full_name ?? '' }}</dd>
<dt class="col-sm-4">Airline</dt><dd class="col-sm-8">{{ $record->airline_id ?? '—' }}</dd>
<dt class="col-sm-4">Flight Number</dt><dd class="col-sm-8">{{ $record->flight_number ?? '—' }}</dd>
<dt class="col-sm-4">Travel Agency</dt><dd class="col-sm-8">{{ $record->travel_agency_id ?? '—' }}</dd>
<dt class="col-sm-4">Departure Date</dt><dd class="col-sm-8">{{ $record->departure_date ?? '—' }}</dd>
<dt class="col-sm-4">Departure Airport</dt><dd class="col-sm-8">{{ $record->departure_airport ?? '—' }}</dd>
<dt class="col-sm-4">Arrival Airport</dt><dd class="col-sm-8">{{ $record->arrival_airport ?? '—' }}</dd>
<dt class="col-sm-4">Ticket Number</dt><dd class="col-sm-8">{{ $record->ticket_number ?? '—' }}</dd>
<dt class="col-sm-4">Status</dt><dd class="col-sm-8">{{ $record->status ?? '—' }}</dd>
@isset($record->ticket_path)<dt class="col-sm-4">Ticket</dt><dd class="col-sm-8"><a href="{{ route('flights.download', [$record, 'ticket_path']) }}">{{ __('Download') }}</a></dd>@endisset
@isset($record->noc_path)<dt class="col-sm-4">NOC</dt><dd class="col-sm-8"><a href="{{ route('flights.download', [$record, 'noc_path']) }}">{{ __('Download') }}</a></dd>@endisset
    </dl>
</div></div>

@if($recruitment)
    <h5>{{ __('Workflow History') }}</h5>
    <x-workflow-timeline :recruitment="$recruitment" />
@endif
<hr>
<h5>{{ __('Change Status') }}</h5>
<form action="{{ route('flights.status', $record) }}" method="POST" class="row g-2">@csrf
    <div class="col-md-4"><select name="status" class="form-select">@foreach($statuses as $status)<option value="{{ $status }}" @selected($record->status == $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>@endforeach</select></div>
    <div class="col-md-5"><input type="text" name="remarks" class="form-control" placeholder="{{ __('Remarks') }}"></div>
    <div class="col-md-3"><x-button type="submit">{{ __('Update Status') }}</x-button></div>
</form>
<hr>
<x-modal id="cancel-modal" title="{{ __('Cancel Record') }}">
    <p>{{ __('This cancels (archives) the record. It is not deleted.') }}</p>
    <form action="{{ route('flights.destroy', $record) }}" method="POST">@csrf @method('DELETE')<x-button type="submit" variant="danger">{{ __('Confirm Cancel') }}</x-button></form>
</x-modal>
@endsection
