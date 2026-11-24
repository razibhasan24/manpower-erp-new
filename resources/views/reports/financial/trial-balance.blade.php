@extends('layouts.app')

@section('title', 'Trial Balance')

@section('content')
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Trial Balance</h1>
            <p class="text-muted small mb-0">View and export the trial balance for a specific period.</p>
        </div>
    </div>

    {{-- ✅ Success Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                        <th class="ps-4 py-3 fw-semibold" style="width: 120px;">Code</th>
                        <th class="py-3 fw-semibold">Account Name</th>
                        <th class="text-end py-3 fw-semibold" style="width: 180px;">Debit</th>
                        <th class="text-end pe-4 py-3 fw-semibold" style="width: 180px;">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td class="ps-4 fw-medium text-muted">{{ $row['code'] }}</td>
                            <td class="fw-medium text-dark">{{ $row['name'] }}</td>
                            {{-- ✅ Debit Amount (Red) --}}
                            <td class="text-end text-danger fw-semibold">
                                {{ $row['debit'] > 0 ? number_format($row['debit'], 2) : '—' }}
                            </td>
                            {{-- ✅ Credit Amount (Green) --}}
                            <td class="text-end pe-4 text-success fw-semibold">
                                {{ $row['credit'] > 0 ? number_format($row['credit'], 2) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" class="bi bi-file-earmark-bar-graph mb-3 opacity-25"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M10 13.5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-6a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm-2.5.5a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 .5.5zm-4 0a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5z" />
                                        <path
                                            d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">No posted entries found</h6>
                                    <p class="small mb-0">There are no transactions in the selected period.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                {{-- ✅ Footer (Total Row) --}}
                @if (count($rows) > 0)
                    <tfoot class="table-light border-top border-2">
                        <tr>
                            <th colspan="2" class="ps-4 py-3 fs-6 text-uppercase">Total</th>
                            <th class="text-end py-3 fs-6 text-danger">{{ number_format($total_debit, 2) }}</th>
                            <th class="text-end pe-4 py-3 fs-6 text-success">{{ number_format($total_credit, 2) }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- ✅ Balance Status (Modern Alert) --}}
    <div class="mt-4 d-flex justify-content-end">
        @if ($balanced)
            <div class="alert alert-success d-inline-flex align-items-center border-0 shadow-sm px-4 py-3 mb-0">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>
                    <strong class="d-block">Balanced</strong>
                    <span class="small opacity-75">Debit and Credit totals match perfectly.</span>
                </div>
            </div>
        @else
            <div class="alert alert-danger d-inline-flex align-items-center border-0 shadow-sm px-4 py-3 mb-0">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div>
                    <strong class="d-block">Out of Balance</strong>
                    <span class="small opacity-75">Investigate immediately! Total debit and credit do not match.</span>
                </div>
            </div>
        @endif
    </div>
@endsection
