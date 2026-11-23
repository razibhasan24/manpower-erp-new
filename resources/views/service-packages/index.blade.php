@extends('layouts.app')

@section('title', $title)

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
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mb-1">{{ $title }}</h1>
            <p class="text-muted mb-0">{{ __('Manage package details, price and availability.') }}</p>
        </div>
        @can($permissionPrefix . '.create')
            <x-button variant="success" size="sm" class="btn btn-primary"
                href="{{ route('service-packages.create', $type) }}">{{ __('Add Package') }}</x-button>
        @endcan
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input class="form-control" name="search" value="{{ request('search') }}"
                placeholder="{{ __('Search by code or package name') }}">
        </div>
        <div class="col-auto">
            <x-button variant="primary" size="sm">{{ __('Search') }}</x-button>
        </div>
        <div class="col-auto">
            <x-button variant="dark" size="sm"
                href="{{ route('service-packages.index', $type) }}">{{ __('Clear') }}</x-button>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Code') }}</th>
                        <th>{{ __('Package') }}</th>
                        <th>{{ __('Duration') }}</th>
                        <th class="text-end">{{ __('Price') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td>{{ $package->code }}</td>
                            <td>
                                <strong>{{ $package->name }}</strong>
                                <div class="small text-muted">
                                    {{ \Illuminate\Support\Str::limit($package->description, 90) }}</div>
                            </td>
                            <td>{{ $package->duration_days ? $package->duration_days . ' ' . __('days') : '—' }}</td>
                            <td class="text-end">{{ $package->currency }} {{ number_format((float) $package->price, 2) }}
                            </td>
                            <td>
                                <span class="badge {{ $package->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $package->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                @can($permissionPrefix . '.edit')
                                    <x-button variant="warning" size="sm"
                                        href="{{ route('service-packages.edit', [$type, $package]) }}">{{ __('Edit') }}</x-button>
                                @endcan

                                @can($permissionPrefix . '.delete')
                                    {{-- ✅ SweetAlert2 ট্রিগার করার জন্য বাটন --}}
                                    <x-button variant="danger" size="sm" class="delete-btn"
                                        data-url="{{ route('service-packages.destroy', [$type, $package]) }}">
                                        {{ __('Delete') }}
                                    </x-button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">{{ __('No packages found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ✅ Custom Pagination --}}
        <div class="card-body border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted small">
                Showing {{ $packages->firstItem() ?? 0 }}–{{ $packages->lastItem() ?? 0 }} of {{ $packages->total() }}
                results
            </div>
            <div class="custom-pagination">
                {{ $packages->appends(request()->query())->links('pagination::bootstrap-5') }}
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
                        text: "{{ __('Delete this package? This action cannot be undone.') }}",
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
