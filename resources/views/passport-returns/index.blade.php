@extends('layouts.app')

@section('title', __('Passport Return'))

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
            <h1 class="h3 mb-1">{{ __('Passport Return') }}</h1>
            <p class="text-muted small mb-0">{{ __('Manage and track returned passport records.') }}</p>
        </div>
        @can('passport-return.create')
            <x-button variant="success" size="sm" class="px-3" href="{{ route('passport-returns.create') }}">
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
                                    <x-button href="{{ route('passport-returns.show', $record) }}" variant="primary" size="sm" class="px-3">
                                        {{ __('View') }}
                                    </x-button>
                                    @can('passport-return.edit')
                                        <x-button href="{{ route('passport-returns.edit', $record) }}" variant="warning" size="sm" class="px-3">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-box-arrow-in-left mb-3 opacity-25" viewBox="0 0 16 16">
                                      <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z"/>
                                      <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
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
                Showing {{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} of {{ $records->total() }} results
            </div>
            <div class="custom-pagination">
                {{ $records->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection