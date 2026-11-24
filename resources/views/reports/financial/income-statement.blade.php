@extends('layouts.app')

@section('title', 'Income Statement')

@section('content')
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Income Statement</h1>
            <p class="text-muted small mb-0">A financial statement showing revenues, expenses, and net profit for a specific
                period.</p>
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

    {{-- ✅ Income Statement Data Columns --}}
    <div class="row g-4 mb-4">
        {{-- Left Column: Income --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom pt-4 pb-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-graph-up-arrow me-2 text-success"></i> Income</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @forelse($income as $row)
                                <tr>
                                    <td class="ps-4 text-muted small" style="width: 80px;">{{ $row['code'] }}</td>
                                    <td class="fw-medium text-dark">{{ $row['name'] }}</td>
                                    <td class="text-end pe-4 fw-semibold text-success">
                                        {{ number_format($row['amount'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No income found for this period.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light border-top border-2">
                            <tr>
                                <th colspan="2" class="ps-4 py-3 text-uppercase fs-6">Total Income</th>
                                <th class="text-end pe-4 py-3 text-success fs-5">{{ number_format($total_income, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Column: Expenses --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom pt-4 pb-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-graph-down-arrow me-2 text-danger"></i> Expenses</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @forelse($expenses as $row)
                                <tr>
                                    <td class="ps-4 text-muted small" style="width: 80px;">{{ $row['code'] }}</td>
                                    <td class="fw-medium text-dark">{{ $row['name'] }}</td>
                                    <td class="text-end pe-4 fw-semibold text-danger">
                                        {{ number_format($row['amount'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No expenses found for this
                                        period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light border-top border-2">
                            <tr>
                                <th colspan="2" class="ps-4 py-3 text-uppercase fs-6">Total Expenses</th>
                                <th class="text-end pe-4 py-3 text-danger fs-5">{{ number_format($total_expenses, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Net Profit Summary Card --}}
    <div class="card border-0 shadow-sm {{ $net_profit >= 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10">
        <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-{{ $net_profit >= 0 ? 'success' : 'danger' }} bg-opacity-25 p-3 rounded-circle">
                    <i
                        class="bi {{ $net_profit >= 0 ? 'bi-emoji-smile' : 'bi-emoji-frown' }} fs-4 text-{{ $net_profit >= 0 ? 'success' : 'danger' }}"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">{{ $net_profit >= 0 ? 'Net Profit' : 'Net Loss' }}</h5>
                    <p class="text-muted small mb-0">Calculated as Total Income minus Total Expenses.</p>
                </div>
            </div>
            <div class="fs-2 fw-bold text-{{ $net_profit >= 0 ? 'success' : 'danger' }}">
                {{ number_format($net_profit, 2) }}
            </div>
        </div>
    </div>
@endsection
