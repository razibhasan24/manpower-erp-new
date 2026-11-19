@extends('layouts.app')
@section('title', __('Work Permits'))
@section('content')
<h1 class="mb-3">{{ __('Work Permits') }}</h1>
<form method="GET" class="row g-2 mb-3"><div class="col-md-6"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Permit or tracking number') }}"></div><div class="col-auto"><button class="btn btn-primary">{{ __('Search') }}</button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('visa-register.work-permits') }}">{{ __('Clear') }}</a></div></form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>{{ __('Permit number') }}</th><th>{{ __('Tracking') }}</th><th>{{ __('Candidate') }}</th><th>{{ __('Issue date') }}</th><th>{{ __('Expiry date') }}</th><th></th></tr></thead><tbody>
@forelse($records as $record)<tr><td>{{ $record->permit_number ?: '—' }}</td><td>{{ $record->visa?->recruitment?->tracking_number ?? '—' }}</td><td>{{ $record->visa?->recruitment?->passportHolder?->full_name ?? '—' }}</td><td>{{ $record->issue_date?->format('Y-m-d') ?? '—' }}</td><td>{{ $record->expiry_date?->format('Y-m-d') ?? '—' }}</td><td>@if($record->visa)<x-button href="{{ route('visas.show', $record->visa) }}" variant="secondary" size="sm">{{ __('Open visa') }}</x-button>@endif</td></tr>@empty<tr><td colspan="6" class="text-center text-muted">{{ __('No records found.') }}</td></tr>@endforelse
</tbody></table></div>{{ $records->links() }}
@endsection
