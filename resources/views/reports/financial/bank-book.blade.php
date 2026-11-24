@extends('layouts.app')

@section('title', 'Bank Book')

@section('content')
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Bank Book</h1>
            <p class="text-muted small mb-0">Track all bank transactions, deposits, withdrawals, and running balances.</p>
        </div>
    </div>

    {{-- ✅ Filter & Export Bar --}}
    <form method="GET" class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                {{-- From Date --}}
                <div class="col-md-3">
                    <label for="from" class="form-label small fw-semibold text-muted">From Date</label>
                    <input type="date" name="from" id="from" value="{{ $filters['from'] ?? '' }}"
                        class="form-control">
                </div>

                {{-- To Date --}}
                <div class="col-md-3">
                    <label for="to" class="form-label small fw-semibold text-muted">To Date</label>
                    <input type="date" name="to" id="to" value="{{ $filters['to'] ?? '' }}"
                        class="form-control">
                </div>

                {{-- Action Buttons --}}
                <div class="col-md-6 d-flex gap-2 justify-content-md-end align-items-center flex-wrap mt-3 mt-md-0">
                    <x-button variant='primary' class="btn btn-primary px-4" type="submit">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </x-button>
                    <div class="vr mx-1 d-none d-md-block"></div>
                    <x-button variant="danger" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">
                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                    </x-button>
                    <x-button variant="success" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel
                    </x-button>
                </div>
            </div>
        </div>
    </form>

    {{-- ✅ Table Card --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold" style="width: 110px;">Date</th>
                        <th class="py-3 fw-semibold" style="width: 130px;">Voucher</th>
                        <th class="py-3 fw-semibold">Account</th>
                        <th class="py-3 fw-semibold">Narration</th>
                        <th class="text-end py-3 fw-semibold" style="width: 120px;">Debit</th>
                        <th class="text-end py-3 fw-semibold" style="width: 120px;">Credit</th>
                        <th class="text-end pe-4 py-3 fw-semibold" style="width: 140px;">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lines as $line)
                        <tr>
                            {{-- Date --}}
                            <td class="ps-4 text-muted">{{ $line['date'] }}</td>

                            {{-- Voucher --}}
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    {{ $line['voucher_number'] }}
                                </span>
                            </td>

                            {{-- Account --}}
                            <td class="fw-medium text-dark">{{ $line['account'] }}</td>

                            {{-- Narration --}}
                            <td class="text-muted small">{{ $line['narration'] }}</td>

                            {{-- Debit --}}
                            <td class="text-end text-danger fw-semibold">
                                {{ $line['debit'] > 0 ? number_format($line['debit'], 2) : '—' }}
                            </td>

                            {{-- Credit --}}
                            <td class="text-end text-success fw-semibold">
                                {{ $line['credit'] > 0 ? number_format($line['credit'], 2) : '—' }}
                            </td>

                            {{-- Balance --}}
                            <td class="text-end pe-4 fw-bold text-dark bg-light bg-opacity-25">
                                {{ number_format($line['balance'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" class="bi bi-bank mb-3 opacity-25" viewBox="0 0 16 16">
                                        <path
                                            d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.379l.5 2A.5.5 0 0 1 15.5 16H.5a.5.5 0 0 1-.485-.621l.5-2A.5.5 0 0 1 1 13V6a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 1 3h.89zM2 3l6-2.726L14 3H2zm1 3v7h2V6H3zm3 0v7h2V6H6zm3 0v7h2V6H9zM2 2v1h12V2H2zm-.348 12-.25 1h13.196l-.25-1H1.652z" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">No bank entries found</h6>
                                    <p class="small mb-0">There are no bank transactions in the selected period.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- Footer (Total Row) --}}
                @if (count($lines) > 0)
                    <tfoot class="table-light border-top border-2">
                        <tr>
                            <th colspan="4" class="ps-4 py-3 fs-6 text-uppercase">Total</th>
                            <th class="text-end py-3 fs-6 text-danger">{{ number_format($total_debit, 2) }}</th>
                            <th class="text-end py-3 fs-6 text-success">{{ number_format($total_credit, 2) }}</th>
                            <th class="text-end pe-4 py-3 fs-6 text-dark bg-light bg-opacity-50">
                                {{ number_format($closing_balance, 2) }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
