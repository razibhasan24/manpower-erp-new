@extends('layouts.app')
@section('title', __('Post Arrival Support'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ __('Post Arrival Support') }}</h1>
    @can('post-arrival.create')
        <x-button href="{{ route('post-arrivals.create') }}">{{ __('New Record') }}</x-button>
    @endcan
</div>
<x-filter-bar :statuses="$statuses" />
<div class="row mb-3">
    <div class="col-md-4"><x-stat-card :title="__('Total')" :value="$records->total()" /></div>
</div>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead><tr><th>{{ __('Tracking') }}</th><th>{{ __('Candidate') }}</th><th>{{ __('Status') }}</th><th>{{ __('Updated') }}</th><th>{{ __('Actions') }}</th></tr></thead>
        <tbody>
        @forelse($records as $record)
            <tr>
                <td>{{ $record->recruitment?->tracking_number ?? '—' }}</td>
                <td>{{ $record->recruitment?->passportHolder?->full_name ?? '—' }}</td>
                <td><x-badge :status="$record->status" /></td>
                <td>{{ $record->updated_at?->format('Y-m-d') }}</td>
                <td class="text-nowrap">
                    <x-button href="{{ route('post-arrivals.show', $record) }}" variant="secondary" size="sm">{{ __('View') }}</x-button>
                    @can('post-arrival.edit')
                    <x-button href="{{ route('post-arrivals.edit', $record) }}" variant="secondary" size="sm">{{ __('Edit') }}</x-button>
                    @endcan
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted">{{ __('No records found.') }}</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $records->links() }}
<p class="text-muted small">{{ __(':count records', ['count' => $records->total()]) }}</p>
@endsection
