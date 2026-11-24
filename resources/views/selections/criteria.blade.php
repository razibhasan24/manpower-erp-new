@extends('layouts.app')

@section('title', __('Grading Criteria'))

@push('styles')
    <style>
        /* Custom Pagination Design */
        .custom-pagination .pagination {
            gap: 8px;
            margin-bottom: 0;
        }

        .custom-pagination .page-item .page-link {
            border-radius: 8px !important;
            border: 1px solid #e5e7eb;
            color: #374151;
            padding: 0.5rem 0.85rem;
            font-weight: 500;
            margin-left: 0 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: none !important;
        }

        .custom-pagination .page-item.active .page-link {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: #ffffff;
        }

        .custom-pagination .page-item.disabled .page-link {
            color: #9ca3af;
            background-color: #ffffff;
            border-color: #e5e7eb;
        }

        .custom-pagination .page-link:hover:not(.active) {
            background-color: #f3f4f6;
            color: #374151;
        }

        /* ✅ FIX: Laravel এর ডিফল্ট Showing টেক্সট হাইড করা হচ্ছে */
        .custom-pagination nav>div>div:first-child {
            display: none !important;
        }

        /* ✅ FIX: পেজিনেশন বাটনগুলোকে ডান দিকে রাখার জন্য */
        .custom-pagination nav>div {
            justify-content: flex-end !important;
        }
    </style>
@endpush

@section('content')
    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="h3 mb-1">{{ __('Grading Criteria') }}</h1>
        <p class="text-muted small mb-0">
            {{ __('Criteria are configurable here and snapshotted onto each selection decision, so later changes never rewrite past decisions.') }}
        </p>
    </div>

    {{-- ✅ Add Criterion Form --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-plus-circle me-1"></i> {{ __('Add New Criterion') }}</h6>
            <form action="{{ route('selections.criteria.store') }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">{{ __('Criterion Name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Interview Score"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">{{ __('Max Score') }} <span
                                class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="max_score" class="form-control" placeholder="e.g. 100"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">{{ __('Weight') }}</label>
                        <input type="number" step="0.01" name="weight" class="form-control" placeholder="e.g. 1.5">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('Add') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ✅ Criteria Table --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold">{{ __('Name') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Max Score') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Weight') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($criteria as $criterion)
                        <tr>
                            <td class="ps-4 fw-medium text-dark">{{ $criterion->name }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $criterion->max_score }}</span></td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $criterion->weight ?? '—' }}</span>
                            </td>
                            <td>
                                {{-- Assuming status is a string like 'active' or a boolean --}}
                                @if (is_string($criterion->status))
                                    <x-badge :status="$criterion->status" />
                                @else
                                    <x-badge :status="$criterion->status ? 'active' : 'inactive'" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        fill="currentColor" class="bi bi-list-check mb-3 opacity-25" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3.854 2.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 3.293l1.146-1.147a.5.5 0 0 1 .708 0m0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 7.293l1.146-1.147a.5.5 0 0 1 .708 0m0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">{{ __('No criteria found') }}</h6>
                                    <p class="small mb-0">{{ __('Add a new criterion using the form above.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ✅ Custom Pagination --}}
        <div class="card-body border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted small">
                Showing {{ $criteria->firstItem() ?? 0 }}–{{ $criteria->lastItem() ?? 0 }} of {{ $criteria->total() }}
                results
            </div>
            <div class="custom-pagination">
                {{ $criteria->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
