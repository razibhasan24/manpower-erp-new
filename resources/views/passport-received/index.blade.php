@extends('layouts.app')
@section('title', __('Passport Received'))
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="mb-0">{{ __('Passport Received') }}</h1>
    @can('passport-return.create')
        <x-button href="{{ route('passport-returns.create') }}">{{ __('Record passport return') }}</x-button>
    @endcan
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-5">
        <input name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Tracking number, candidate or passport number') }}">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">{{ __('All statuses') }}</option>
            @foreach(['pending', 'returned', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ __(ucfirst($status)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto"><button class="btn btn-primary">{{ __('Filter') }}</button></div>
    <div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('passport-received.index') }}">{{ __('Clear') }}</a></div>
</form>

<div class="row mb-3">
    <div class="col-md-4"><x-stat-card :title="__('Records')" :value="$records->total()" /></div>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead><tr>
            <th>{{ __('Return date') }}</th><th>{{ __('Tracking') }}</th><th>{{ __('Candidate') }}</th>
            <th>{{ __('Passport number') }}</th><th>{{ __('Received by') }}</th><th>{{ __('Delivered to') }}</th>
            <th>{{ __('Status') }}</th><th>{{ __('Actions') }}</th>
        </tr></thead>
        <tbody>
        @forelse($records as $record)
            <tr>
                <td>{{ $record->return_date?->format('Y-m-d') ?? '—' }}</td>
                <td>{{ $record->recruitment?->tracking_number ?? '—' }}</td>
                <td>{{ $record->recruitment?->passportHolder?->full_name ?? '—' }}</td>
                <td>{{ $record->recruitment?->passportHolder?->passport_number ?? '—' }}</td>
                <td>{{ $record->received_by ?: '—' }}</td>
                <td>{{ $record->delivered_to ?: '—' }}</td>
                <td><x-badge :status="$record->status" /></td>
                <td class="text-nowrap">
                    <x-button href="{{ route('passport-returns.show', $record) }}" variant="secondary" size="sm">{{ __('View') }}</x-button>
                    @can('passport-return.edit')
                        <x-button href="{{ route('passport-returns.edit', $record) }}" variant="secondary" size="sm">{{ __('Edit') }}</x-button>
                    @endcan
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center text-muted">{{ __('No passport receipt records found.') }}</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $records->links() }}
<p class="text-muted small">{{ __(':count records', ['count' => $records->total()]) }}</p>
@endsection
