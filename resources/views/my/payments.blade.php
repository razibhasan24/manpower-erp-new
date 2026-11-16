@extends('layouts.app')

@section('title', __('My Payments') . ' — ' . \App\Services\BrandingService::appName())

@section('content')
    <x-page-header :title="__('My Payments')" :subtitle="$party['name']">
        <x-slot:actions>
            <a href="{{ route('my.ledger') }}" class="btn btn-outline-primary btn-sm">{{ __('Full Ledger') }}</a>
        </x-slot:actions>
    </x-page-header>

    <form method="GET" action="{{ route('my.payments') }}" class="card mb-3">
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
                    <a href="{{ route('my.payments') }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-3 mb-3">
        <div class="col-md-4"><x-stat-card :label="__('Payments shown')" :value="count($payments)" /></div>
        <div class="col-md-4"><x-stat-card :label="__('Total paid in period')" :value="number_format($total_paid, 2)" :sub="\App\Services\BrandingService::currency()" /></div>
        <div class="col-md-4"><x-stat-card :label="__('Closing balance')" :value="number_format($closing_balance, 2)" :sub="\App\Services\BrandingService::currency()" /></div>
    </div>

    <div class="card">
        <div class="card-header">{{ __('Payment receipts') }}</div>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Voucher') }}</th>
                        <th>{{ __('Account') }}</th>
                        <th>{{ __('Narration') }}</th>
                        <th class="text-end">{{ __('Amount paid') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment['date'] }}</td>
                            <td>{{ $payment['voucher_number'] }}</td>
                            <td>{{ $payment['account'] }}</td>
                            <td>{{ $payment['narration'] }}</td>
                            <td class="text-end fw-semibold">{{ number_format($payment['credit'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">{{ __('No payments recorded in this period.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($payments !== [])
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">{{ __('Total') }}</th>
                            <th class="text-end">{{ number_format($total_paid, 2) }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <p class="small text-secondary mt-3 mb-0">{{ __('Payments are credit entries from posted vouchers. Contact the accounts office if a receipt is missing.') }}</p>
@endsection
