@extends('layouts.app')

@section('title', 'Branches')

@section('content')

    <style>
        .branch-page {
            padding-top: 15px;
            padding-bottom: 25px;
        }

        .branch-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 22px;
        }

        .branch-title {
            font-size: 23px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .branch-page .card {
            border: 1px solid #e5eaf0;
            border-radius: 11px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .045);
            overflow: hidden;
        }

        .branch-page .card-body {
            padding: 20px;
        }

        .branch-page .form-control,
        .branch-page .form-select {
            min-height: 40px;
            border-radius: 7px;
            border: 1px solid #dce2e9;
            font-size: 13px;
            box-shadow: none;
        }

        .branch-page .form-control:focus,
        .branch-page .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .10);
        }

        .branch-page .btn {
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 13px;
            transition: all .2s ease;
        }

        .branch-page .btn:hover {
            transform: translateY(-1px);
        }

        .branch-info {
            color: #64748b;
            font-size: 13px;
            margin: 18px 0 12px;
        }

        .branch-page .table {
            margin-bottom: 0;
        }

        .branch-page .table thead th {
            background: #f1f5f9;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            padding: 14px 15px;
            white-space: nowrap;
            border-bottom: 1px solid #e2e8f0;
        }

        .branch-page .table tbody td {
            padding: 13px 15px;
            font-size: 13px;
            color: #475569;
            vertical-align: middle;
            border-color: #edf0f4;
        }

        .branch-page .table tbody tr:hover {
            background: #f8fafc;
        }

        .branch-actions {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 5px;
        }

        .branch-actions .btn {
            white-space: nowrap;
        }

        .branch-pagination {
            margin-top: 20px;
        }

        @media (max-width: 767px) {
            .branch-title {
                font-size: 19px;
            }

            .branch-header {
                align-items: flex-start;
            }

            .branch-page .card-body {
                padding: 14px;
            }

            .branch-page .table {
                min-width: 750px;
            }

            .branch-header .btn {
                width: 100%;
            }
        }
    </style>

    <div class="container-fluid branch-page">

        {{-- Page Header --}}
        <div class="branch-header">
            <div>
                <h1 class="branch-title">
                    <i class="fas fa-building text-primary me-2"></i>
                    Branches
                </h1>
                <p class="text-muted small mb-0 mt-1">
                    Manage and monitor your company branches.
                </p>
            </div>

            @can('branch.create')
                <x-button variant="success" href="{{ route('branches.create') }}">
                    <i class="fas fa-plus me-1"></i> New Branch
                </x-button>
            @endcan
        </div>

        {{-- Filter Section --}}
        <x-filter-bar>
            <div class="card-body">
                <form method="GET" action="{{ route('branches.index') }}" class="row g-3 align-items-end">

                    <div class="col-md-5">
                        <label class="form-label fw-semibold small">Search Branch</label>
                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search by name, code or phone...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All statuses</option>
                            <option value="active" @selected(request('status') === 'active')>
                                Active
                            </option>
                            <option value="inactive" @selected(request('status') === 'inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Actions</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>

                            <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary flex-fill">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </x-filter-bar>

        {{-- Total Branches --}}
        <div class="branch-info">
            <i class="fas fa-database me-1"></i>
            Showing <strong>{{ $items->total() }}</strong> branches found.
        </div>

        {{-- Branch Table --}}
        <x-table>
            <table class="table table-striped table-hover mb-0">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Phone</th>
                        <th>Manager</th>
                        <th>Status</th>
                        <th style="min-width: 220px;">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($items as $item)
                        <tr>

                            <td>
                                <div class="fw-semibold text-dark">
                                    <i class="fas fa-building text-muted me-1"></i>
                                    {{ $item->name }}
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $item->code }}
                                </span>
                            </td>

                            <td>{{ $item->phone ?? '—' }}</td>

                            <td>{{ $item->manager?->name ?? '—' }}</td>

                            <td>
                                <x-badge>{{ $item->status }}</x-badge>
                            </td>

                            <td>
                                <div class="branch-actions">

                                    @can('branch.view')
                                        <x-button href="{{ route('branches.show', $item) }}" variant="primary" size="sm">
                                            <i class="fas fa-eye"></i> View
                                        </x-button>
                                    @endcan

                                    @can('branch.edit')
                                        <x-button href="{{ route('branches.edit', $item) }}" variant="warning" size="sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </x-button>
                                    @endcan

                                    @can('branch.delete')
                                        <button type="button" class="btn btn-danger btn-sm delete-branch-btn"
                                            data-action="{{ route('branches.destroy', $item) }}"
                                            data-name="{{ $item->name }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    @endcan

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open fa-2x d-block mb-3"></i>
                                <strong>No branches found.</strong>
                                <div class="small mt-1">
                                    Adjust the filters or create a new branch.
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </x-table>

        {{-- Pagination --}}
        <div class="branch-pagination">
            {{ $items->links() }}
        </div>

    </div>

@endsection


{{-- SweetAlert2 Delete Confirmation --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.delete-branch-btn').forEach(function(button) {

                button.addEventListener('click', function() {

                    const action = this.dataset.action;
                    const branchName = this.dataset.name;

                    Swal.fire({
                        title: 'Are you sure?',
                        html: 'You are about to delete <strong>' +
                            branchName +
                            '</strong>.<br><small>This action cannot be undone!</small>',

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',

                        confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete',
                        cancelButtonText: '<i class="fas fa-times"></i> Cancel',

                        reverseButtons: true,
                        focusCancel: true,

                        customClass: {
                            popup: 'rounded-4',
                            confirmButton: 'rounded-3 px-4',
                            cancelButton: 'rounded-3 px-4'
                        }

                    }).then((result) => {

                        if (result.isConfirmed) {

                            const form = document.createElement('form');

                            form.method = 'POST';
                            form.action = action;

                            const csrfToken = document.querySelector(
                                'meta[name="csrf-token"]'
                            )?.getAttribute('content');

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = csrfToken || '{{ csrf_token() }}';

                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';

                            form.appendChild(csrfInput);
                            form.appendChild(methodInput);

                            document.body.appendChild(form);

                            form.submit();
                        }

                    });

                });

            });

        });
    </script>
@endpush
