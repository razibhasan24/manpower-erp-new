@extends('layouts.app')
@section('title', __('Training'))
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ __('Training') }}</h1>
        @can('training.create')
            <x-button variant="success" href="{{ route('trainings.create') }}">{{ __('New Record') }}</x-button>
        @endcan
    </div>
    <x-filter-bar :statuses="$statuses" />
    <div class="row mb-3">
        <div class="col-md-4"><x-stat-card :title="__('Total')" :value="$records->total()" /></div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>{{ __('Tracking') }}</th>
                    <th>{{ __('Candidate') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Updated') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>{{ $record->recruitment?->tracking_number ?? '—' }}</td>
                        <td>{{ $record->recruitment?->passportHolder?->full_name ?? '—' }}</td>
                        <td><x-badge :status="$record->result" /></td>
                        <td>{{ $record->updated_at?->format('Y-m-d') }}</td>
                        <td class="text-nowrap">
                            <x-button href="{{ route('trainings.show', $record) }}" variant="secondary"
                                size="sm">{{ __('View') }}</x-button>
                            @can('training.edit')
                                <x-button href="{{ route('trainings.edit', $record) }}" variant="secondary"
                                    size="sm">{{ __('Edit') }}</x-button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">{{ __('No records found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $records->links() }}
    <p class="text-muted small">{{ __(':count records', ['count' => $records->total()]) }}</p>
@endsection
