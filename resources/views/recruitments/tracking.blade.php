@extends('layouts.app')

@section('title', __('Recruitment Tracking') . ' — ' . \App\Services\BrandingService::appName())

@php
    $stagePositions = array_flip($stages);
@endphp

@section('content')
    <x-page-header :title="__('Recruitment Tracking')" :subtitle="__('A read-only view of the current workflow stage for every visible registration.')">
        <x-slot:actions>
            <a href="{{ route('recruitments.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('All registrations') }}</a>
            @can('recruitment.create')
                <a href="{{ route('recruitments.create') }}" class="btn btn-primary btn-sm">{{ __('New registration') }}</a>
            @endcan
        </x-slot:actions>
    </x-page-header>

    <form method="GET" action="{{ route('recruitments.tracking') }}" class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label">{{ __('Search') }}</label>
                    <input type="search" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="{{ __('Tracking number') }}">
                </div>
                <div class="col-md-4">
                    <label for="current_stage" class="form-label">{{ __('Stage') }}</label>
                    <select name="current_stage" id="current_stage" class="form-select">
                        <option value="">{{ __('All stages') }}</option>
                        @foreach($stages as $stage)
                            <option value="{{ $stage }}" @selected(request('current_stage') === $stage)>{{ ucwords(str_replace('_', ' ', $stage)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                    <a href="{{ route('recruitments.tracking') }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>
                </div>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('Tracking board') }}</span>
            <span class="small text-secondary">{{ $items->total() }} {{ __('registrations') }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Tracking') }}</th>
                        <th>{{ __('Candidate') }}</th>
                        <th>{{ __('Country') }}</th>
                        <th>{{ __('Stage') }}</th>
                        <th style="min-width: 170px;">{{ __('Progress') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-end">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            $currentIndex = $stagePositions[$item->current_stage] ?? 0;
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $item->tracking_number }}</td>
                            <td>{{ $item->passportHolder?->full_name ?? '—' }}</td>
                            <td>{{ $item->country?->name ?? '—' }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $item->current_stage)) }}</td>
                            <td>
                                <div class="workflow-progress" role="img" aria-label="{{ __('Stage :current of :total', ['current' => $currentIndex + 1, 'total' => count($stages)]) }}">
                                    @foreach($stages as $index => $stage)
                                        <span class="{{ $index < $currentIndex ? 'is-done' : '' }} {{ $index === $currentIndex ? 'is-current' : '' }}" title="{{ ucwords(str_replace('_', ' ', $stage)) }}"></span>
                                    @endforeach
                                </div>
                                <span class="small text-secondary">{{ $currentIndex + 1 }}/{{ count($stages) }}</span>
                            </td>
                            <td><x-badge :status="$item->current_status" /></td>
                            <td class="text-end">
                                <a href="{{ route('recruitments.show', $item) }}" class="btn btn-sm btn-outline-primary">{{ __('View') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-4">{{ __('No registrations match the filter.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $items->links() }}</div>

    <p class="small text-secondary mt-2 mb-0">{{ __('Stage changes are performed only through the workflow actions on each registration record and are fully audited.') }}</p>
@endsection
