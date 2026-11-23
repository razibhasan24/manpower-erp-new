@extends('layouts.app')

@section('title', 'Positions')

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
        <h1 class="h3 mb-0">Positions</h1>
        @can('core.manage')
            <x-button variant="success" href="{{ route('positions.create') }}">Add New Position</x-button>
        @endcan
    </div>

    <x-filter-bar>
        <form method="GET" action="{{ route('positions.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search...">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="country_id" class="form-select">
                    <option value="">All countries</option>
                    @foreach ($filterCountries as $country)
                        <option value="{{ $country->id }}" @selected((string) request('country_id') === (string) $country->id)>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="company_id" class="form-select">
                    <option value="">All companies</option>
                    @foreach ($filterCompanies as $company)
                        <option value="{{ $company->id }}" @selected((string) request('company_id') === (string) $company->id)>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('positions.index') }}" class="btn btn-dark w-100">Reset</a>
            </div>
        </form>
    </x-filter-bar>

    <p class="text-muted">{{ $items->total() }} positions found.</p>

    <x-table>
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Company</th>
                    <th>Country</th>
                    <th>Salary</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->company?->name ?? '—' }}</td>
                        <td>{{ $item->country?->name ?? '—' }}</td>
                        <td>{{ $item->currency ?? '' }} {{ number_format((float) ($item->salary_min ?? 0), 2) }} –
                            {{ number_format((float) ($item->salary_max ?? 0), 2) }}</td>
                        <td><x-badge>{{ $item->status }}</x-badge></td>
                        <td class="text-nowrap">
                            @can('core.view')
                                <x-button href="{{ route('positions.show', $item) }}" variant="primary"
                                    size="sm">View</x-button>
                            @endcan
                            @can('core.manage')
                                <x-button href="{{ route('positions.edit', $item) }}" variant="warning"
                                    size="sm">Edit</x-button>
                            @endcan
                            @can('core.manage')
                                {{-- ✅ SweetAlert2 ট্রিগার করার জন্য delete-btn ক্লাস এবং data-url --}}
                                <x-button href="#" variant="danger" size="sm" class="btn-sm delete-btn"
                                    data-url="{{ route('positions.destroy', $item) }}">Delete</x-button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No positions found. Adjust the filters or
                            create a new one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-table>

    {{-- ✅ Pagination (শুধু বাম পাশে Showing দেখাবে, ডান পাশে শুধু বাটন) --}}
    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-muted small">
            Showing {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} of {{ $items->total() }} results
        </div>
        <div class="custom-pagination">
            {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- ✅ ডিলিট করার জন্য হিডেন ফর্ম (SweetAlert কনফার্ম করলে এটি সাবমিট হবে) --}}
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
            // সব ডিলিট বাটন সিলেক্ট করা
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteForm = document.getElementById('deleteForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault(); // ডিফল্ট লিংক অ্যাকশন বন্ধ করা

                    const url = this.getAttribute('data-url'); // ডিলিট URL নেওয়া

                    // SweetAlert2 পপআপ কনফিগারেশন
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This record will be removed. Recruitment and passport holder records are archived or cancelled, never hard-deleted.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545', // Danger Red
                        cancelButtonColor: '#6c757d', // Secondary Gray
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Keep it',
                        reverseButtons: true // Cancel বাটন বামে, Confirm বাটন ডানে দেখাবে
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // ইউজার Confirm করলে হিডেন ফর্ম সাবমিট হবে
                            deleteForm.action = url;
                            deleteForm.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
