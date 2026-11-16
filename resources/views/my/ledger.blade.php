@extends('layouts.app')

@section('title', __('My Ledger') . ' — ' . \App\Services\BrandingService::appName())

@section('content')
    <x-page-header :title="__('My Ledger')" :subtitle="$party['name']">
        <x-slot:actions>
            <a href="{{ route('my.payments') }}" class="btn btn-outline-primary btn-sm">{{ __('My Payments') }}</a>
        </x-slot:actions>
    </x-page-header>

    <form method="GET" action="{{ route('my.ledger') }}" class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="from" class="form-label">{{ __('From') }}</label>
                    <input type="date" name="from" id="from" class="form-control" value="{{ $filters['from'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="to" class="form-label">{{ __('To') }}</label>
                    <input type="date" name="to" id="to" class="form-control" value="{{ $filters['to'] ?? '' }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                    <a href="{{ route('my.ledger') }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Opening balance')" :value="number_format($ledger['opening_balance'], 2)" :sub="\App\Services\BrandingService::currency()" /></div>
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Total debit')" :value="number_format($ledger['total_debit'], 2)" :sub="\App\Services\BrandingService::currency()" /></div>
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Total credit')" :value="number_format($ledger['total_credit'], 2)" :sub="\App\Services\BrandingService::currency()" /></div>
        <div class="col-6 col-lg-3"><x-stat-card :label="__('Closing balance')" :value="number_format($ledger['closing_balance'], 2)" :sub="\App\Services\BrandingService::currency()" /></div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('Ledger entries') }}</span>
            <span class="small text-secondary">{{ count($ledger['lines']) }} {{ __('entries') }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Voucher') }}</th>
                        <th>{{ __('Account') }}</th>
                        <th>{{ __('Narration') }}</th>
                        <th class="text-end">{{ __('Debit') }}</th>
                        <th class="text-end">{{ __('Credit') }}</th>
                        <th class="text-end">{{ __('Balance') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ledger['lines'] as $line)
                        <tr>
                            <td>{{ $line['date'] }}</td>
                            <td>{{ $line['voucher_number'] }}</td>
                            <td>{{ $line['account'] }}</td>
                            <td>{{ $line['narration'] }}</td>
                            <td class="text-end">{{ number_format($line['debit'], 2) }}</td>
                            <td class="text-end">{{ number_format($line['credit'], 2) }}</td>
                            <td class="text-end fw-semibold">{{ number_format($line['balance'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-4">{{ __('No ledger entries in this period.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p class="small text-secondary mt-3 mb-0">{{ __('Ledger entries come only from posted vouchers.') }}</p>
@endsection
