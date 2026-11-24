@extends('layouts.app')

@section('title', __('Visa & MOFA'))

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
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ __('Visa & MOFA') }}</h1>
            <p class="text-muted small mb-0">{{ __('Manage visa processing and MOFA status updates.') }}</p>
        </div>
        @can('visa.create')
            <x-button variant="success" size="sm" class="px-3"
                href="{{ route('visas.create', request()->only('service_type')) }}">
                <i class="bi bi-plus-lg me-1"></i> {{ __('New Record') }}
            </x-button>
        @endcan
    </div>

    {{-- Filter Bar --}}
    <x-filter-bar :statuses="$statuses" :filters="[
        [
            'name' => 'mofa_status',
            'label' => __('MOFA status'),
            'type' => 'select',
            'options' => [
                'pending' => __('Pending'),
                'submitted' => __('Submitted'),
                'approved' => __('Approved'),
                'rejected' => __('Rejected'),
                'returned' => __('Returned'),
            ],
        ],
    ]" />

    {{-- Stat Card --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <x-stat-card :title="__('Total Records')" :value="$records->total()" />
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold">{{ __('Tracking') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Candidate') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Status') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Updated') }}</th>
                        <th class="text-end pe-4 py-3 fw-semibold">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td class="ps-4 fw-medium text-dark">{{ $record->recruitment?->tracking_number ?? '—' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{ $record->recruitment?->passportHolder?->full_name ?? '—' }}
                                </div>
                            </td>
                            <td><x-badge :status="$record->status" /></td>
                            <td class="text-secondary">{{ $record->updated_at?->format('M d, Y') }}</td>
                            <td class="text-end pe-4 text-nowrap">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <x-button href="{{ route('visas.show', $record) }}" variant="primary" size="sm"
                                        class="px-3">
                                        {{ __('View') }}
                                    </x-button>
                                    @can('visa.edit')
                                        <x-button href="{{ route('visas.edit', $record) }}" variant="warning" size="sm"
                                            class="px-3">
                                            {{ __('Edit') }}
                                        </x-button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        fill="currentColor" class="bi bi-passport mb-3 opacity-50" viewBox="0 0 16 16">
                                        <path d="M8 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6M6 8a2 2 0 1 1 4 0 2 2 0 0 1-4 0" />
                                        <path
                                            d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">{{ __('No records found') }}</h6>
                                    <p class="small mb-0">{{ __('Create a new record or adjust your filters.') }}</p>
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
                Showing {{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} of {{ $records->total() }}
                results
            </div>
            <div class="custom-pagination">
                {{ $records->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
