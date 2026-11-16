@extends('layouts.app')

@section('title', __('Financial Dashboard') . ' — ' . \App\Services\BrandingService::appName())

@php
    $currency = \App\Services\BrandingService::currency();
    $balanced = abs($stats['total_debit'] - $stats['total_credit']) < 0.005;
@endphp

@section('content')
    <x-page-header :title="__('Financial Dashboard')" :subtitle="__('Posted vouchers and account balances for the current month.')">
        <x-slot:actions>
            <a href="{{ route('accounting.vouchers.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('Voucher register') }}</a>
            <a href="{{ route('reports.financial.trial-balance') }}" class="btn btn-primary btn-sm">{{ __('Trial balance') }}</a>
        </x-slot:actions>
    </x-page-header>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Posted this month')" :value="$stats['posted_this_month']" icon="receipt" /></div>
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Receipts this month')" :value="number_format($stats['receipts_this_month'], 2)" :sub="$currency" icon="hand-coins" /></div>
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Payments this month')" :value="number_format($stats['payments_this_month'], 2)" :sub="$currency" icon="banknote" /></div>
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Draft vouchers')" :value="$stats['drafts']" icon="clock" /></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="small text-secondary">{{ __('Posted debit total') }}</div>
                    <div class="h4 mb-3">{{ number_format($stats['total_debit'], 2) }} <span class="fs-6 fw-normal text-secondary">{{ $currency }}</span></div>
                    <div class="small text-secondary">{{ __('Posted credit total') }}</div>
                    <div class="h4 mb-3">{{ number_format($stats['total_credit'], 2) }} <span class="fs-6 fw-normal text-secondary">{{ $currency }}</span></div>
                    <span class="badge {{ $balanced ? 'text-bg-success' : 'text-bg-danger' }}">
                        {{ $balanced ? __('Journal is balanced') : __('Journal is out of balance') }}
                    </span>
                </div>
                <div class="card-footer bg-transparent small text-secondary">
                    {{ __('All figures come from posted vouchers only.') }}
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">{{ __('Largest account balances') }}</div>
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr><th>{{ __('Account') }}</th><th class="text-end">{{ __('Debit') }}</th><th class="text-end">{{ __('Credit') }}</th><th class="text-end">{{ __('Balance') }}</th></tr>
                        </thead>
                        <tbody>
                            @forelse($balances as $row)
                                <tr>
                                    <td>{{ $row['account'] }}</td>
                                    <td class="text-end">{{ number_format($row['debit'], 2) }}</td>
                                    <td class="text-end">{{ number_format($row['credit'], 2) }}</td>
                                    <td class="text-end fw-semibold">{{ number_format($row['balance'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-secondary py-4">{{ __('No posted journal entries yet.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('Recent vouchers') }}</span>
            <a href="{{ route('accounting.vouchers.index') }}" class="small">{{ __('View all') }}</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr><th>{{ __('Voucher') }}</th><th>{{ __('Type') }}</th><th>{{ __('Date') }}</th><th>{{ __('Branch') }}</th><th class="text-end">{{ __('Amount') }}</th><th>{{ __('Status') }}</th></tr>
                </thead>
                <tbody>
                    @forelse($recent as $voucher)
                        <tr>
                            <td><a href="{{ route('accounting.vouchers.show', $voucher) }}">{{ $voucher->voucher_number }}</a></td>
                            <td>{{ ucfirst($voucher->voucher_type) }}</td>
                            <td>{{ $voucher->voucher_date?->format('d M Y') }}</td>
                            <td>{{ $voucher->branch?->name ?? '—' }}</td>
                            <td class="text-end">{{ number_format($voucher->total_debit, 2) }}</td>
                            <td><x-badge :status="$voucher->status" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">{{ __('No vouchers yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
