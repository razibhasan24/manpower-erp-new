@extends('layouts.app')

@section('title', __('Recruitment Dashboard') . ' — ' . \App\Services\BrandingService::appName())

@section('content')
    <x-page-header :title="__('Recruitment Dashboard')" :subtitle="__('Pipeline health across every registration you are allowed to see.')">
        <x-slot:actions>
            <a href="{{ route('recruitments.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('All registrations') }}</a>
            <a href="{{ route('recruitments.tracking') }}" class="btn btn-primary btn-sm">{{ __('Tracking board') }}</a>
        </x-slot:actions>
    </x-page-header>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Total registrations')" :value="$stats['total']" icon="user-search" /></div>
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Pending review')" :value="$stats['pending']" icon="clock" /></div>
        <div class="col-6 col-lg-3"><x-stat-card :label="__('In progress')" :value="$stats['in_progress']" icon="activity" /></div>
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Completed')" :value="$stats['completed']" icon="circle-check-big" /></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Registrations by workflow stage') }}</span>
                    <span class="small text-secondary">{{ __('15 stages') }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr><th>{{ __('Stage') }}</th><th class="text-end">{{ __('Count') }}</th><th style="width:42%">{{ __('Share') }}</th></tr>
                        </thead>
                        <tbody>
                            @foreach($stageCounts as $stage => $count)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $stage)) }}</td>
                                    <td class="text-end fw-semibold">{{ $count }}</td>
                                    <td>
                                        <div class="progress" style="height: 6px;" role="progressbar" aria-label="{{ ucwords(str_replace('_', ' ', $stage)) }}" aria-valuenow="{{ $count }}" aria-valuemin="0" aria-valuemax="{{ $maxStageCount }}">
                                            <div class="progress-bar" style="width: {{ round(($count / $maxStageCount) * 100) }}%; background-color: var(--brand-primary);"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">{{ __('Top countries') }}</div>
                <div class="card-body">
                    @forelse($byCountry as $country => $count)
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light-subtle">
                            <span class="text-truncate pe-2">{{ $country }}</span>
                            <span class="badge text-bg-light">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-secondary mb-0 small">{{ __('No registrations yet.') }}</p>
                    @endforelse
                </div>
                <div class="card-footer bg-transparent small text-secondary">
                    {{ __('Cancelled') }}: <strong>{{ $stats['cancelled'] }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('Recent registrations') }}</span>
            <a href="{{ route('recruitments.index') }}" class="small">{{ __('View all') }}</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr><th>{{ __('Tracking') }}</th><th>{{ __('Candidate') }}</th><th>{{ __('Country') }}</th><th>{{ __('Stage') }}</th><th>{{ __('Status') }}</th></tr>
                </thead>
                <tbody>
                    @forelse($recent as $recruitment)
                        <tr>
                            <td><a href="{{ route('recruitments.show', $recruitment) }}">{{ $recruitment->tracking_number }}</a></td>
                            <td>{{ $recruitment->passportHolder?->full_name ?? '—' }}</td>
                            <td>{{ $recruitment->country?->name ?? '—' }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $recruitment->current_stage)) }}</td>
                            <td><x-badge :status="$recruitment->current_status" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">{{ __('No registrations yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
