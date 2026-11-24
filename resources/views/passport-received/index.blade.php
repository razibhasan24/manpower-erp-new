@extends('layouts.app')

@section('title', __('Passport Received'))

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
            <h1 class="h3 mb-1">{{ __('Passport Received') }}</h1>
            <p class="text-muted small mb-0">{{ __('Manage and track received passports from candidates.') }}</p>
        </div>
        @can('passport-return.create')
            <x-button variant="success" size="sm" class="px-3" href="{{ route('passport-returns.create') }}">
                <i class="bi bi-plus-lg me-1"></i> {{ __('Record passport return') }}
            </x-button>
        @endcan
    </div>

    {{-- ✅ Filter Bar --}}
    <form method="GET" class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search" class="form-label small fw-semibold text-muted">{{ __('Search') }}</label>
                    <input name="search" id="search" value="{{ request('search') }}" class="form-control"
                        placeholder="{{ __('Tracking number, candidate or passport number') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label small fw-semibold text-muted">{{ __('Status') }}</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">{{ __('All statuses') }}</option>
                        @foreach (['pending', 'returned', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>
                                {{ __(ucfirst($status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-funnel me-1"></i> {{ __('Filter') }}
                    </button>
                    <x-button href="{{ route('passport-received.index') }}" variant="dark" class="btn btn-dark px-3">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> {{ __('Clear') }}
                    </x-button>
                </div>
            </div>
        </div>
    </form>

    {{-- Stat Card --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <x-stat-card :title="__('Total Records')" :value="$records->total()" />
        </div>
    </div>

    {{-- ✅ Table Card --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold">{{ __('Return date') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Tracking') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Candidate') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Passport number') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Received by') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Delivered to') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Status') }}</th>
                        <th class="text-end pe-4 py-3 fw-semibold">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td class="ps-4 text-secondary">{{ $record->return_date?->format('M d, Y') ?? '—' }}</td>
                            <td class="fw-medium text-dark">{{ $record->recruitment?->tracking_number ?? '—' }}</td>
                            <td>{{ $record->recruitment?->passportHolder?->full_name ?? '—' }}</td>
                            <td>{{ $record->recruitment?->passportHolder?->passport_number ?? '—' }}</td>
                            <td>{{ $record->received_by ?: '—' }}</td>
                            <td>{{ $record->delivered_to ?: '—' }}</td>
                            <td><x-badge :status="$record->status" /></td>
                            <td class="text-end pe-4 text-nowrap">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <x-button href="{{ route('passport-returns.show', $record) }}" variant="primary"
                                        size="sm" class="px-3">
                                        {{ __('View') }}
                                    </x-button>
                                    @can('passport-return.edit')
                                        <x-button href="{{ route('passport-returns.edit', $record) }}" variant="warning"
                                            size="sm" class="px-3">
                                            {{ __('Edit') }}
                                        </x-button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" class="bi bi-journal-check mb-3 opacity-25" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                                        <path
                                            d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m0 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">{{ __('No passport receipt records found') }}</h6>
                                    <p class="small mb-0">{{ __('Record a new passport return to get started.') }}</p>
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
