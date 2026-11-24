@extends('layouts.app')

@section('title', 'University Management')

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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">University Management</h1>
        @can('student-package.create')
            <x-button variant="success" size="sm" href="{{ route('universities.create') }}">Add University</x-button>
        @endcan
    </div>

    {{-- Filter Bar --}}
    <form class="row g-2 mb-3 align-items-end">
        <div class="col-md-4">
            <input class="form-control" name="search" value="{{ request('search') }}"
                placeholder="Search university by name or code...">
        </div>
        <div class="col-auto">
            <x-button variant="primary" type="submit">Search</x-button>
        </div>
        <div class="col-auto">
            <x-button class="btn btn-dark" href="{{ route('universities.index') }}">Reset</x-button>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold">Code</th>
                        <th class="py-3 fw-semibold">University</th>
                        <th class="py-3 fw-semibold">Country</th>
                        <th class="py-3 fw-semibold">City</th>
                        <th class="py-3 fw-semibold">Status</th>
                        <th class="text-end pe-4 py-3 fw-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($universities as $university)
                        <tr>
                            <td class="ps-4 text-secondary">{{ $university->code }}</td>
                            <td class="fw-medium text-dark">{{ $university->name }}</td>
                            <td>{{ $university->country ?? '—' }}</td>
                            <td>{{ $university->city ?? '—' }}</td>
                            <td>
                                {{-- ✅ সুন্দর ব্যাজ ডিজাইন --}}
                                <span class="badge {{ $university->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $university->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    @can('student-package.edit')
                                        <x-button class="btn btn-sm btn-primary px-3"
                                            href="{{ route('universities.edit', $university) }}">Edit</x-button>
                                    @endcan

                                    @can('student-package.delete')
                                        {{-- ✅ SweetAlert2 ট্রিগার করার জন্য বাটন --}}
                                        <x-button class="btn btn-sm btn-outline-danger px-3 delete-btn"
                                            data-url="{{ route('universities.destroy', $university) }}">
                                            Archive
                                        </x-button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
                                    class="bi bi-buildings mb-3 opacity-50" viewBox="0 0 16 16">
                                    <path
                                        d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .263.075M6 8.36 1 10.16v5.34h4V8.36Zm1-7.36v14h6v-14H7Zm-1 3h3v2H6V4Zm3 3H8v2h1V7Zm-3 2h3v2H6V9Z" />
                                </svg>
                                <h6 class="fw-bold mb-1">No universities found</h6>
                                <p class="small mb-0">Click "Add University" to create one.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ✅ Custom Pagination --}}
        <div class="card-body border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted small">
                Showing {{ $universities->firstItem() ?? 0 }}–{{ $universities->lastItem() ?? 0 }} of
                {{ $universities->total() }} results
            </div>
            <div class="custom-pagination">
                {{ $universities->appends(request()->query())->links('pagination::bootstrap-5') }}
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
                        title: 'Archive University?',
                        text: "This university will be archived and won't be available for new selections.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, archive it!',
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
