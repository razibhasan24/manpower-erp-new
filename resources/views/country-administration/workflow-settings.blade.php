@extends('layouts.app')
@section('title', __('Country-wise Workflow Settings'))
@section('content')
<h1 class="mb-3">{{ __('Country-wise Workflow Settings') }}</h1>
<div class="alert alert-info"><strong>{{ __('Shared workflow is active') }}</strong><br>{{ __('All countries currently use the same recruitment workflow and stage rules. Per-country overrides are not configured in the application yet.') }}</div>

<h2 class="h5 mt-4">{{ __('Workflow stages') }}</h2>
<div class="table-responsive mb-4"><table class="table table-striped align-middle"><thead><tr><th>{{ __('Order') }}</th><th>{{ __('Stage') }}</th><th>{{ __('Available statuses') }}</th></tr></thead><tbody>
@foreach($stages as $index => $stage)<tr><td>{{ $index + 1 }}</td><td>{{ __(\Illuminate\Support\Str::headline($stage)) }}</td><td>{{ collect($stageStatuses[$stage] ?? [])->map(fn ($status) => __(\Illuminate\Support\Str::headline($status)))->join(', ') }}</td></tr>@endforeach
</tbody></table></div>

<h2 class="h5">{{ __('Countries using this workflow') }}</h2>
<form method="GET" class="row g-2 mb-3"><div class="col-md-5"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Search country or code') }}"></div><div class="col-auto"><button class="btn btn-primary">{{ __('Search') }}</button></div><div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('country-workflow-settings.index') }}">{{ __('Clear') }}</a></div></form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>{{ __('Country') }}</th><th>{{ __('Code') }}</th><th>{{ __('Status') }}</th><th>{{ __('Recruitments') }}</th><th>{{ __('Workflow') }}</th></tr></thead><tbody>
@forelse($countries as $country)<tr><td>{{ $country->name }}</td><td>{{ $country->code ?: '—' }}</td><td><x-badge :status="$country->status" /></td><td>{{ $country->recruitments_count }}</td><td>{{ __('Shared 15-stage workflow') }}</td></tr>@empty<tr><td colspan="5" class="text-center text-muted">{{ __('No countries found.') }}</td></tr>@endforelse
</tbody></table></div>{{ $countries->links() }}
@endsection
