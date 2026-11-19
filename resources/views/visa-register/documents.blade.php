@extends('layouts.app')
@section('title', __('Visa Documents'))
@section('content')
<h1 class="mb-3">{{ __('Visa Documents') }}</h1>
<form method="GET" class="row g-2 mb-3"><div class="col-md-6"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Search file or tracking number') }}"></div><div class="col-auto"><button class="btn btn-primary">{{ __('Search') }}</button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('visa-register.documents') }}">{{ __('Clear') }}</a></div></form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>{{ __('Document') }}</th><th>{{ __('Tracking') }}</th><th>{{ __('Candidate') }}</th><th>{{ __('Uploaded') }}</th><th></th></tr></thead><tbody>
@forelse($records as $record)<tr><td>{{ $record->original_name }}</td><td>{{ $record->recruitment?->tracking_number ?? '—' }}</td><td>{{ $record->recruitment?->passportHolder?->full_name ?? '—' }}</td><td>{{ $record->created_at?->format('Y-m-d') ?? '—' }}</td><td>@if($record->recruitment?->visa)<x-button href="{{ route('visas.show', $record->recruitment->visa) }}" variant="secondary" size="sm">{{ __('Open visa') }}</x-button>@endif</td></tr>@empty<tr><td colspan="5" class="text-center text-muted">{{ __('No visa documents found.') }}</td></tr>@endforelse
</tbody></table></div>{{ $records->links() }}
@endsection
