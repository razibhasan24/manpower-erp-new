@extends('layouts.app')
@section('title', __('Appointments'))
@section('content')
<h1 class="mb-3">{{ __('Appointments') }}</h1>
<form method="GET" class="row g-2 mb-3"><div class="col-md-5"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Search tracking number') }}"></div><div class="col-md-3"><select name="status" class="form-select"><option value="">{{ __('All statuses') }}</option>@foreach(['pending','confirmed','completed','cancelled'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ __(ucfirst($status)) }}</option>@endforeach</select></div><div class="col-auto"><button class="btn btn-primary">{{ __('Filter') }}</button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('visa-register.appointments') }}">{{ __('Clear') }}</a></div></form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>{{ __('Date') }}</th><th>{{ __('Tracking') }}</th><th>{{ __('Candidate') }}</th><th>{{ __('Location') }}</th><th>{{ __('Status') }}</th><th></th></tr></thead><tbody>
@forelse($records as $record)<tr><td>{{ $record->appointment_date?->format('Y-m-d H:i') ?? '—' }}</td><td>{{ $record->visa?->recruitment?->tracking_number ?? '—' }}</td><td>{{ $record->visa?->recruitment?->passportHolder?->full_name ?? '—' }}</td><td>{{ $record->location ?: '—' }}</td><td><x-badge :status="$record->status" /></td><td>@if($record->visa)<x-button href="{{ route('visas.show', $record->visa) }}" variant="secondary" size="sm">{{ __('Open visa') }}</x-button>@endif</td></tr>@empty<tr><td colspan="6" class="text-center text-muted">{{ __('No records found.') }}</td></tr>@endforelse
</tbody></table></div>{{ $records->links() }}
@endsection
