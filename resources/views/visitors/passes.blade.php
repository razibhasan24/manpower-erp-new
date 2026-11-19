@extends('layouts.app')
@section('title', __('Visitor Passes'))
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="mb-0">{{ __('Visitor Passes') }}</h1>
    @can('visitor.create')
        <x-button href="{{ route('visitors.create') }}">{{ __('Register visitor') }}</x-button>
    @endcan
</div>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-5"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Pass number, visitor or phone') }}"></div>
    <div class="col-md-3"><select name="status" class="form-select"><option value="">{{ __('All pass statuses') }}</option>@foreach(['issued','returned','lost'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ __(ucfirst($status)) }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-primary">{{ __('Filter') }}</button></div>
    <div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('visitor-passes.index') }}">{{ __('Clear') }}</a></div>
</form>
<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>{{ __('Pass number') }}</th><th>{{ __('Visitor') }}</th><th>{{ __('Phone') }}</th><th>{{ __('Purpose') }}</th><th>{{ __('Issued') }}</th><th>{{ __('Returned') }}</th><th>{{ __('Status') }}</th><th>{{ __('Actions') }}</th></tr></thead><tbody>
@forelse($records as $record)<tr><td>{{ $record->pass_number }}</td><td>{{ $record->visitor?->name ?? '—' }}</td><td>{{ $record->visitor?->phone ?? '—' }}</td><td>{{ \Illuminate\Support\Str::limit($record->visitor?->purpose, 80) ?: '—' }}</td><td>{{ $record->issued_at?->format('Y-m-d H:i') ?? '—' }}</td><td>{{ $record->returned_at?->format('Y-m-d H:i') ?? '—' }}</td><td><x-badge :status="$record->status" /></td><td class="text-nowrap">@if($record->visitor)<a class="btn btn-sm btn-outline-primary" href="{{ route('visitors.pass', $record->visitor) }}">{{ __('Print pass') }}</a><a class="btn btn-sm btn-outline-secondary" href="{{ route('visitors.show', $record->visitor) }}">{{ __('Visitor') }}</a>@endif</td></tr>@empty<tr><td colspan="8" class="text-center text-muted">{{ __('No visitor passes found.') }}</td></tr>@endforelse
</tbody></table></div>
{{ $records->links() }}
<p class="text-muted small">{{ __(':count passes', ['count' => $records->total()]) }}</p>
@endsection
