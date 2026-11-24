@extends('layouts.app')

@section('title', __('Recruitment Tracking') . ' — ' . \App\Services\BrandingService::appName())

{{-- @push('styles')
   
@endpush --}}

@php
    $stagePositions = array_flip($stages);
@endphp

@section('content')
    <x-page-header :title="__('Recruitment Tracking')" :subtitle="__('A read-only view of the current workflow stage for every visible registration.')">
        <x-slot:actions>
            <x-button href="{{ route('recruitments.index') }}" variant="success"
                class="btn btn-success btn-sm">{{ __('All registrations') }}</x-button>
            @can('recruitment.create')
                <x-button href="{{ route('recruitments.create') }}" variant="primary"
                    class="btn btn-primary btn-sm">{{ __('New registration') }}</x-button>
            @endcan
        </x-slot:actions>
    </x-page-header>

    {{-- ✅ Modern Filter Form --}}
    <form method="GET" action="{{ route('recruitments.tracking') }}" class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label small fw-semibold text-muted">{{ __('Search') }}</label>
                    <input type="search" name="search" id="search" class="form-control" value="{{ request('search') }}"
                        placeholder="{{ __('Tracking number') }}">
                </div>
                <div class="col-md-4">
                    <label for="current_stage" class="form-label small fw-semibold text-muted">{{ __('Stage') }}</label>
                    <select name="current_stage" id="current_stage" class="form-select">
                        <option value="">{{ __('All stages') }}</option>
                        @foreach ($stages as $stage)
                            <option value="{{ $stage }}" @selected(request('current_stage') === $stage)>
                                {{ ucwords(str_replace('_', ' ', $stage)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <x-button variant="primary" type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-funnel me-1"></i> {{ __('Filter') }}
                    </x-button>
                    <x-button href="{{ route('recruitments.tracking') }}" variant="dark" class="btn btn-dark px-3">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> {{ __('Clear') }}
                    </x-button>
                </div>
            </div>
        </div>
    </form>

    {{-- ✅ Tracking Board Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-semibold">{{ __('Tracking board') }}</h5>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                {{ $items->total() }} {{ __('registrations') }}
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold">{{ __('Tracking') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Candidate') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Country') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Stage') }}</th>
                        <th class="py-3 fw-semibold" style="min-width: 170px;">{{ __('Progress') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Status') }}</th>
                        <th class="text-end pe-4 py-3 fw-semibold">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            $currentIndex = $stagePositions[$item->current_stage] ?? 0;
                        @endphp
                        <tr>
                            <td class="ps-4 fw-semibold text-dark">{{ $item->tracking_number }}</td>
                            <td>{{ $item->passportHolder?->full_name ?? '—' }}</td>
                            <td>{{ $item->country?->name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    {{ ucwords(str_replace('_', ' ', $item->current_stage)) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div class="workflow-progress" role="img"
                                        aria-label="{{ __('Stage :current of :total', ['current' => $currentIndex + 1, 'total' => count($stages)]) }}">
                                        @foreach ($stages as $index => $stage)
                                            <span
                                                class="{{ $index < $currentIndex ? 'is-done' : '' }} {{ $index === $currentIndex ? 'is-current' : '' }}"
                                                title="{{ ucwords(str_replace('_', ' ', $stage)) }}"></span>
                                        @endforeach
                                    </div>
                                    <span class="small text-muted" style="font-size: 0.75rem;">
                                        {{ $currentIndex + 1 }}/{{ count($stages) }} Completed
                                    </span>
                                </div>
                            </td>
                            <td><x-badge :status="$item->current_status" /></td>
                            <td class="text-end pe-4">
                                <x-button href="{{ route('recruitments.show', $item) }}" variant="primary"
                                    class="btn btn-sm px-3">
                                    {{ __('View') }}
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" class="bi bi-search mb-3 opacity-25" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">{{ __('No registrations found') }}</h6>
                                    <p class="small mb-0">{{ __('No registrations match your current filters.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ✅ Custom Pagination --}}
        <div class="card-body border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted small">
                Showing {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} of {{ $items->total() }} results
            </div>
            <div class="custom-pagination">
                {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <p class="small text-muted mt-3 mb-0 text-center fst-italic">
        {{ __('Stage changes are performed only through the workflow actions on each registration record and are fully audited.') }}
    </p>
@endsection
