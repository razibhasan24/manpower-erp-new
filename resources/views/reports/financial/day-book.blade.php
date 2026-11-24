@extends('layouts.app')

@section('title', 'Day Book')

@section('content')
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Day Book</h1>
            <p class="text-muted small mb-0">A daily summary of all posted vouchers and their transactions.</p>
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
                        <th class="py-3 fw-semibold" style="width: 130px;">Type</th>
                        <th class="py-3 fw-semibold">Narration</th>
                        <th class="text-end py-3 fw-semibold" style="width: 120px;">Debit</th>
                        <th class="text-end pe-4 py-3 fw-semibold" style="width: 120px;">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $v)
                        <tr>
                            {{-- Date --}}
                            <td class="ps-4 text-muted">{{ $v['voucher_date'] }}</td>

                            {{-- Voucher Number --}}
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    {{ $v['voucher_number'] }}
                                </span>
                            </td>

                            {{-- Type --}}
                            <td>
                                @php
                                    $typeColor = match (strtolower($v['voucher_type'])) {
                                        'receipt', 'receive' => 'success',
                                        'payment' => 'danger',
                                        'journal' => 'primary',
                                        'contra' => 'info',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span
                                    class="badge bg-{{ $typeColor }} bg-opacity-10 text-{{ $typeColor }} border border-{{ $typeColor }}-subtle px-2 py-1">
                                    {{ ucfirst($v['voucher_type']) }}
                                </span>
                            </td>

                            {{-- Narration --}}
                            <td class="text-muted small">{{ $v['narration'] }}</td>

                            {{-- Debit --}}
                            <td class="text-end text-danger fw-semibold">
                                {{ $v['total_debit'] > 0 ? number_format($v['total_debit'], 2) : '—' }}
                            </td>

                            {{-- Credit --}}
                            <td class="text-end pe-4 text-success fw-semibold">
                                {{ $v['total_credit'] > 0 ? number_format($v['total_credit'], 2) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" class="bi bi-journal-x mb-3 opacity-25" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M6.146 6.146a.5.5 0 0 1 .708 0L8 7.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 8l1.147 1.146a.5.5 0 0 1-.708.708L8 8.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 8 6.146 6.854a.5.5 0 0 1 0-.708" />
                                        <path
                                            d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m0 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">No vouchers found</h6>
                                    <p class="small mb-0">There are no posted vouchers in the selected period.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- Footer (Total Row) --}}
                @if (count($vouchers) > 0)
                    <tfoot class="table-light border-top border-2">
                        <tr>
                            <th colspan="4" class="ps-4 py-3 fs-6 text-uppercase">
                                Total <span class="text-muted fw-normal text-capitalize ms-1">({{ $count }}
                                    voucher(s))</span>
                            </th>
                            <th class="text-end py-3 fs-6 text-danger">{{ number_format($total_debit, 2) }}</th>
                            <th class="text-end pe-4 py-3 fs-6 text-success">{{ number_format($total_credit, 2) }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
