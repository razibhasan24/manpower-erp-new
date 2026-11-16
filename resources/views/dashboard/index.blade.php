@extends('layouts.app')

@section('title', __('Dashboard') . ' — ' . \App\Services\BrandingService::appName())

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Dashboard') }}</li>
@endsection

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Total Recruitments')" :value="$stats['total_recruitments']" /></div>
        <div class="col-6 col-lg-3"><x-stat-card :label="__('In Progress')" :value="$stats['in_progress']" /></div>
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Completed')" :value="$stats['completed']" /></div>
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Passport Holders')" :value="$stats['passport_holders']" /></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">{{ __('Recruitments by Stage') }}</div>
                <div class="card-body">
                    @if(empty($byStage))
                        <p class="text-muted mb-0">{{ __('No recruitments yet.') }}</p>
                    @else
                        <canvas id="stageChart" height="220" data-labels="{{ json_encode(array_keys($byStage)) }}" data-values="{{ json_encode(array_values($byStage)) }}"></canvas>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">{{ __('Recruitments by Country') }}</div>
                <div class="card-body">
                    @if(empty($byCountry))
                        <p class="text-muted mb-0">{{ __('No recruitments yet.') }}</p>
                    @else
                        <canvas id="countryChart" height="220" data-labels="{{ json_encode(array_keys($byCountry)) }}" data-values="{{ json_encode(array_values($byCountry)) }}"></canvas>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span>{{ __('Recent Recruitments') }}</span>
                    <span class="text-muted small">{{ $recent->count() }} {{ __('shown') }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Tracking No.') }}</th>
                                <th>{{ __('Candidate') }}</th>
                                <th>{{ __('Country') }}</th>
                                <th>{{ __('Stage') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent as $recruitment)
                                <tr>
                                    <td>{{ $recruitment->tracking_number }}</td>
                                    <td>{{ $recruitment->passportHolder?->full_name }}</td>
                                    <td>{{ $recruitment->country?->name }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $recruitment->current_stage)) }}</td>
                                    <td><x-badge :status="$recruitment->current_status" /></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">{{ __('No recruitments yet.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">{{ __('Passports Expiring Soon (60 days)') }}</div>
                <ul class="list-group list-group-flush">
                    @forelse($expiringPassports as $holder)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $holder->full_name }}</span>
                            <span class="text-muted">{{ $holder->passport_expiry_date?->format('d-m-Y') }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">{{ __('No passports expiring soon.') }}</li>
                    @endforelse
                </ul>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <div class="small text-muted">{{ __('Posted vouchers this month') }}</div>
                    <div class="h4 mb-0">{{ $stats['posted_vouchers_this_month'] }}</div>
                    <div class="small text-muted mt-2">{{ __('Receipts collected this month') }}</div>
                    <div class="h5 mb-0">{{ number_format($stats['total_collected_this_month'], 2) }} {{ \App\Services\BrandingService::currency() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('canvas[data-labels]').forEach(function (el) {
        new Chart(el, {
            type: 'bar',
            data: {
                labels: JSON.parse(el.dataset.labels),
                datasets: [{ data: JSON.parse(el.dataset.values), backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--brand-primary') || '#0d6efd' }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    });
});
</script>
@endpush
