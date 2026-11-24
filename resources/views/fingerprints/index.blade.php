@extends('layouts.app')

@section('title', __('Fingerprint'))

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
            <h1 class="h3 mb-1">{{ __('Fingerprint') }}</h1>
            <p class="text-muted small mb-0">{{ __('Manage and track fingerprint records for candidates.') }}</p>
        </div>
        @can('fingerprint.create')
            <x-button variant="success" size="sm" class="px-3" href="{{ route('fingerprints.create') }}">
                <i class="bi bi-plus-lg me-1"></i> {{ __('New Record') }}
            </x-button>
        @endcan
    </div>

    {{-- Filter Bar --}}
    <x-filter-bar :statuses="$statuses" />

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
                                    <x-button href="{{ route('fingerprints.show', $record) }}" variant="primary"
                                        size="sm" class="px-3">
                                        {{ __('View') }}
                                    </x-button>
                                    @can('fingerprint.edit')
                                        <x-button href="{{ route('fingerprints.edit', $record) }}" variant="warning"
                                            size="sm" class="px-3">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" class="bi bi-fingerprint mb-3 opacity-25" viewBox="0 0 16 16">
                                        <path
                                            d="M8.06 6.5a.5.5 0 0 1 .5.5v.776a11.5 11.5 0 0 1-.552 3.519l-1.331-1.047C6.499 10.665 6.5 10.586 6.5 10.5v-3.5a.5.5 0 0 1 .5-.5z" />
                                        <path
                                            d="M5.06 6.5a.5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 0 1.5 1.5 1.5 1.5 0 0 0 1.5-1.5v-2.5a.5.5 0 0 1 1 0v2.5a2.5 2.5 0 0 1-2.5 2.5 2.5 2.5 0 0 1-2.5-2.5V7a.5.5 0 0 1 .5-.5z" />
                                        <path
                                            d="M8.5 2.5a.5.5 0 0 1 .5-.5 5 5 0 0 1 5 5 .5.5 0 0 1-1 0 4 4 0 0 0-4-4 .5.5 0 0 1-.5-.5m-5 0a.5.5 0 0 0-.5.5 5 5 0 0 0 1 3 .5.5 0 0 0 .866-.5A4 4 0 0 1 4 3a.5.5 0 0 0-.5-.5" />
                                        <path
                                            d="M8.5 6.5a.5.5 0 0 0-1 0v1.5a.5.5 0 0 1-1 0V6.5a1.5 1.5 0 1 1 3 0v1a.5.5 0 0 1-1 0z" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">{{ __('No records found') }}</h6>
                                    <p class="small mb-0">{{ __('Create a new record to get started.') }}</p>
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
