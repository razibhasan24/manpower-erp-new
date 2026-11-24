@extends('layouts.app')

@section('title', __('Medical Centers'))

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
            <h1 class="h3 mb-1">{{ __('Medical Centers') }}</h1>
            <p class="text-muted small mb-0">Manage medical centers used for medical assessments.</p>
        </div>
        @can('medical-centre.create')
            <x-button href="{{ route('medical-centres.create') }}" variant="success" class="px-3">
                <i class="bi bi-plus-lg me-1"></i> {{ __('Add Medical Center') }}
            </x-button>
        @endcan
    </div>

    {{-- ✅ Filter Bar --}}
    <form method="GET" class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search" class="form-label small fw-semibold text-muted">{{ __('Search') }}</label>
                    <input type="search" name="search" id="search" class="form-control" value="{{ request('search') }}"
                        placeholder="{{ __('Search by name') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label small fw-semibold text-muted">{{ __('Status') }}</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">{{ __('All statuses') }}</option>
                        <option value="active" @selected(request('status') === 'active')>{{ __('Active') }}</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>{{ __('Inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <x-button type="submit" variant="primary" class="btn btn-primary px-4">
                        <i class="bi bi-funnel me-1"></i> {{ __('Filter') }}
                    </x-button>
                    <x-button href="{{ route('medical-centres.index') }}" variant="dark" class="btn btn-dark px-3">
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
                        <th class="ps-4 py-3 fw-semibold">{{ __('Name') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Phone') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Email') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Status') }}</th>
                        <th class="text-end pe-4 py-3 fw-semibold">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($centres as $centre)
                        <tr>
                            <td class="ps-4 fw-medium text-dark">{{ $centre->name }}</td>
                            <td>{{ $centre->phone ?: '—' }}</td>
                            <td>{{ $centre->email ?: '—' }}</td>
                            <td><x-badge :status="$centre->status" /></td>
                            <td class="text-end pe-4 text-nowrap">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    @can('medical-centre.edit')
                                        <x-button href="{{ route('medical-centres.edit', $centre) }}" variant="warning"
                                            size="sm" class="px-3">
                                            {{ __('Edit') }}
                                        </x-button>
                                    @endcan

                                    @can('medical-centre.delete')
                                        {{-- ✅ SweetAlert2 ট্রিগার করার জন্য বাটন --}}
                                        <x-button type="button" variant="danger" class="btn btn-sm px-3 delete-btn"
                                            data-url="{{ route('medical-centres.destroy', $centre) }}">
                                            {{ __('Delete') }}
                                        </x-button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" class="bi bi-hospital mb-3 opacity-25" viewBox="0 0 16 16">
                                        <path
                                            d="M8.5 5.034v1.1l.953-.55.5.867L9 7l.953.55-.5.866-.953-.55v1.1h-1v-1.1l-.953.55-.5-.866L7 7l-.953-.55.5-.866.953.55v-1.1zM13.25 9a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25zM13 11.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25zm-5-6.5A.25.25 0 0 1 8.25 4.5h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25zM3 9.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5A.25.25 0 0 1 3 9.75zm.25 1.75a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25z" />
                                        <path
                                            d="M14 14V4.5a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 0-.5.5v.5h-1v-.5a.5.5 0 0 0-.5-.5h-1V.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5M4.5 1h4v13h-4zm5 4.5v9h-1v-9zm4 9h-3v-9h3z" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">{{ __('No medical centres found') }}</h6>
                                    <p class="small mb-0">{{ __('Adjust your filters or add a new medical center.') }}</p>
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
                Showing {{ $centres->firstItem() ?? 0 }}–{{ $centres->lastItem() ?? 0 }} of {{ $centres->total() }}
                results
            </div>
            <div class="custom-pagination">
                {{ $centres->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- ✅ ডিলিট করার জন্য হিডেন ফর্ম --}}
    <form id="deleteForm" method="POST" action="" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteForm = document.getElementById('deleteForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const url = this.getAttribute('data-url');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "{{ __('Delete this medical centre? Existing medical records will be kept without a centre.') }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteForm.action = url;
                            deleteForm.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
