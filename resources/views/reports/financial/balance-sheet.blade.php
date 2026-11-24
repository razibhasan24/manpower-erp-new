@extends('layouts.app')

@section('title', 'Balance Sheet')

@section('content')
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Balance Sheet</h1>
            <p class="text-muted small mb-0">A financial statement summarizing the company's assets, liabilities, and equity.
            </p>
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

    {{-- ✅ Balance Sheet Data Columns --}}
    <div class="row g-4">
        {{-- Left Column: Assets --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom pt-4 pb-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-briefcase me-2 text-primary"></i> Assets</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @forelse($assets as $row)
                                <tr>
                                    <td class="ps-4 text-muted small" style="width: 80px;">{{ $row['code'] }}</td>
                                    <td class="fw-medium text-dark">{{ $row['name'] }}</td>
                                    <td class="text-end pe-4 fw-semibold text-dark">{{ number_format($row['amount'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No assets found for this period.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light border-top border-2">
                            <tr>
                                <th colspan="2" class="ps-4 py-3 text-uppercase fs-6">Total Assets</th>
                                <th class="text-end pe-4 py-3 text-primary fs-5">{{ number_format($total_assets, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Column: Liabilities & Equity --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom pt-4 pb-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-wallet2 me-2 text-success"></i> Liabilities & Equity</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            {{-- Liabilities --}}
                            @foreach ($liabilities as $row)
                                <tr>
                                    <td class="ps-4 text-muted small" style="width: 80px;">{{ $row['code'] }}</td>
                                    <td class="fw-medium text-dark">{{ $row['name'] }}</td>
                                    <td class="text-end pe-4 fw-semibold text-dark">{{ number_format($row['amount'], 2) }}
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Equity --}}
                            @foreach ($equity as $row)
                                <tr>
                                    <td class="ps-4 text-muted small" style="width: 80px;">{{ $row['code'] }}</td>
                                    <td class="fw-medium text-dark">{{ $row['name'] }}</td>
                                    <td class="text-end pe-4 fw-semibold text-dark">{{ number_format($row['amount'], 2) }}
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Net Profit --}}
                            <tr class="bg-light bg-opacity-50">
                                <td class="ps-4 text-muted small" style="width: 80px;">—</td>
                                <td class="fw-bold text-dark">Net Profit</td>
                                <td class="text-end pe-4 fw-bold {{ $net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($net_profit, 2) }}
                                </td>
                            </tr>

                            @if (empty($liabilities) && empty($equity))
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No liabilities or equity found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="table-light border-top border-2">
                            <tr>
                                <th colspan="2" class="ps-4 py-3 text-uppercase fs-6">Total Liabilities & Equity</th>
                                <th class="text-end pe-4 py-3 text-success fs-5">
                                    {{ number_format($total_liabilities_equity, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Balance Status Alert --}}
    <div class="mt-4 d-flex justify-content-end">
        @if ($balanced)
            <div class="alert alert-success d-inline-flex align-items-center border-0 shadow-sm px-4 py-3 mb-0">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>
                    <strong class="d-block fs-6">Balanced</strong>
                    <span class="small opacity-75">Total Assets match Total Liabilities & Equity.</span>
                </div>
            </div>
        @else
            <div class="alert alert-danger d-inline-flex align-items-center border-0 shadow-sm px-4 py-3 mb-0">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>
                    <strong class="d-block fs-6">Out of Balance</strong>
                    <span class="small opacity-75">Investigate immediately! Totals do not match.</span>
                </div>
            </div>
        @endif
    </div>
@endsection
