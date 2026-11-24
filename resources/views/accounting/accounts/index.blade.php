@extends('layouts.app')

@section('title', 'Chart of Accounts')

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
            <h1 class="h3 mb-1">Chart of Accounts</h1>
            <p class="text-muted small mb-0">Manage your general ledger accounts and payment modes.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            {{-- Payment Modes Button --}}
            <x-button href="{{ route('accounting.payment-modes.index') }}" variant="success" class="px-3">
                <i class="bi bi-credit-card me-1"></i> Payment Modes
            </x-button>

            {{-- Add Account Button --}}
            <x-button href="{{ route('accounting.accounts.create') }}" variant="primary" class="px-3">
                <i class="bi bi-plus-lg me-1"></i> Add Account
            </x-button>
        </div>
    </div>

    {{-- ✅ Success Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ✅ Filter Bar --}}
    <form method="GET" class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search" class="form-label small fw-semibold text-muted">{{ __('Search') }}</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control"
                        placeholder="{{ __('Search by account code or name...') }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <x-button variant="primary" type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-search me-1"></i> {{ __('Search') }}
                    </x-button>
                    <x-button href="{{ route('accounting.accounts.index') }}" variant="dark" class="btn btn-dark px-3">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> {{ __('Clear') }}
                    </x-button>
                </div>
            </div>
        </div>
    </form>

    {{-- ✅ Table Card --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold">Code</th>
                        <th class="py-3 fw-semibold">Name</th>
                        <th class="py-3 fw-semibold">Type</th>
                        <th class="py-3 fw-semibold">Normal Balance</th>
                        <th class="py-3 fw-semibold">Status</th>
                        <th class="text-end pe-4 py-3 fw-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td class="ps-4 fw-medium text-dark">{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    {{ $item->type->name ?? '—' }}
                                </span>
                            </td>
                            <td class="text-capitalize">{{ $item->normal_balance }}</td>
                            <td><x-badge :status="$item->status" /></td>
                            <td class="text-end pe-4 text-nowrap">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <x-button href="{{ route('accounting.accounts.show', $item) }}" variant="primary"
                                        size="sm" class="px-3">
                                        {{ __('View') }}
                                    </x-button>
                                    <x-button href="{{ route('accounting.accounts.edit', $item) }}" variant="warning"
                                        size="sm" class="px-3">
                                        {{ __('Edit') }}
                                    </x-button>
                                </div>
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
                                    <h6 class="fw-bold mb-1">{{ __('No accounts found') }}</h6>
                                    <p class="small mb-0">{{ __('Adjust your search or create a new account.') }}</p>
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
                Showing {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} of {{ $items->total() }} results
            </div>
            <div class="custom-pagination">
                {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
