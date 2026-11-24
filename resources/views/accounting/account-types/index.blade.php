@extends('layouts.app')

@section('title', 'Account Types')

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
            <h1 class="h3 mb-1">Account Types</h1>
            <p class="text-muted small mb-0">The five account types are seeded and not user-editable.</p>
        </div>
    </div>

    {{-- ✅ Table Card --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold">Name</th>
                        <th class="py-3 fw-semibold">Normal Balance</th>
                        <th class="text-end pe-4 py-3 fw-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td class="ps-4 fw-medium text-dark">{{ $item->name }}</td>
                            <td>
                                {{-- ✅ Debit/Credit এর জন্য সুন্দর ব্যাজ --}}
                                @if (strtolower($item->normal_balance) === 'debit')
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-2 py-1">
                                        <i class="bi bi-arrow-up-right me-1"></i> {{ ucfirst($item->normal_balance) }}
                                    </span>
                                @elseif(strtolower($item->normal_balance) === 'credit')
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1">
                                        <i class="bi bi-arrow-down-left me-1"></i> {{ ucfirst($item->normal_balance) }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark border px-2 py-1 text-capitalize">
                                        {{ $item->normal_balance }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                <x-button href="{{ route('accounting.account-types.show', $item) }}" variant="primary"
                                    size="sm" class="px-3">
                                    {{ __('View') }}
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" class="bi bi-tags mb-3 opacity-25" viewBox="0 0 16 16">
                                        <path
                                            d="M3 2v4.586l7 7L14.586 9l-7-7H3zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586V2z" />
                                        <path
                                            d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                        <path
                                            d="M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1v5.086z" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">No account types found</h6>
                                    <p class="small mb-0">Run the <code>AccountTypeSeeder</code> to populate the initial
                                        data.</p>
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
