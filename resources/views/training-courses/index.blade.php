@extends('layouts.app')

@section('title', __('Training Courses'))

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
            <h1 class="h3 mb-1">{{ __('Training Courses') }}</h1>
            <p class="text-muted small mb-0">Manage courses offered by training centers.</p>
        </div>
        @can('training-course.create')
            <x-button href="{{ route('training-courses.create') }}" variant="success" class="px-3">
                <i class="bi bi-plus-lg me-1"></i> {{ __('Add Training Course') }}
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
                        placeholder="{{ __('Search by course title') }}">
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
                    <x-button href="{{ route('training-courses.index') }}" variant="dark" class="px-3">
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
                        <th class="ps-4 py-3 fw-semibold">{{ __('Course') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Training Center') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Duration (days)') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Fee') }}</th>
                        <th class="py-3 fw-semibold">{{ __('Status') }}</th>
                        <th class="text-end pe-4 py-3 fw-semibold">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td class="ps-4 fw-medium text-dark">{{ $course->title }}</td>
                            <td>{{ $course->centre?->name ?? '—' }}</td>
                            <td>
                                @if ($course->duration_days)
                                    <span class="badge bg-light text-dark border">{{ $course->duration_days }}
                                        {{ __('days') }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="fw-semibold">
                                {{ $course->fee !== null ? number_format((float) $course->fee, 2) : '—' }}</td>
                            <td><x-badge :status="$course->status" /></td>
                            <td class="text-end pe-4 text-nowrap">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    @can('training-course.edit')
                                        <x-button href="{{ route('training-courses.edit', $course) }}" variant="warning"
                                            size="sm" class="px-3">
                                            {{ __('Edit') }}
                                        </x-button>
                                    @endcan

                                    @can('training-course.delete')
                                        {{-- ✅ SweetAlert2 ট্রিগার করার জন্য বাটন --}}
                                        <x-button type="button" variant="danger" size="sm" class="px-3 delete-btn"
                                            data-url="{{ route('training-courses.destroy', $course) }}">
                                            {{ __('Delete') }}
                                        </x-button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" class="bi bi-journal-bookmark mb-3 opacity-25"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8" />
                                        <path
                                            d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m0 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z" />
                                        <path fill-rule="evenodd"
                                            d="M8 10a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5" />
                                    </svg>
                                    <h6 class="fw-bold mb-1">{{ __('No training courses found') }}</h6>
                                    <p class="small mb-0">{{ __('Adjust your filters or add a new course.') }}</p>
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
                Showing {{ $courses->firstItem() ?? 0 }}–{{ $courses->lastItem() ?? 0 }} of {{ $courses->total() }}
                results
            </div>
            <div class="custom-pagination">
                {{ $courses->appends(request()->query())->links('pagination::bootstrap-5') }}
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
                        text: "{{ __('Delete this training course? Existing records will be kept without a course.') }}",
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
