@extends('layouts.app')

@section('title', __('Training Centers'))

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
            <h1 class="h3 mb-1">{{ __('Training Centers') }}</h1>
            <p class="text-muted small mb-0">Manage training centers for candidate skill development.</p>
        </div>
        @can('training-centre.create')
            <x-button href="{{ route('training-centres.create') }}" variant="success" class="px-3">
                <i class="bi bi-plus-lg me-1"></i> {{ __('Add Training Center') }}
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
                    <x-button type="submit" variant="primary" class="px-4">
                        <i class="bi bi-funnel me-1"></i> {{ __('Filter') }}
                    </x-button>
                    <x-button href="{{ route('training-centres.index') }}" variant="dark" class="px-3">
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
                                    @can('training-centre.edit')
                                        <x-button href="{{ route('training-centres.edit', $centre) }}" variant="warning"
                                            size="sm" class="px-3">
                                            {{ __('Edit') }}
                                        </x-button>
                                    @endcan

                                    @can('training-centre.delete')
                                        {{-- ✅ SweetAlert2 ট্রিগার করার জন্য বাটন --}}
                                        <button type="button" class="btn btn-sm btn-outline-danger px-3 delete-btn"
                                            data-url="{{ route('training-centres.destroy', $centre) }}">
                                            {{ __('Delete') }}
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" class="bi bi-mortarboard mb-3 opacity-25" viewBox="0 0 16 16">
                                        <path
                                            d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917zM8 8.46 1.758 5.965 8 3.052l6.242 2.913z" />
                                        <path
                                            d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466zm-.068 1.873.22-.748 3.496 1.311a.5.5 0 0 0 .352 0l3.496-1.311.22.748L8 12.46z" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">{{ __('No training centres found') }}</h6>
                                    <p class="small mb-0">{{ __('Adjust your filters or add a new training center.') }}</p>
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
                        text: "{{ __('Delete this training centre? Existing records will be kept without a centre.') }}",
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
