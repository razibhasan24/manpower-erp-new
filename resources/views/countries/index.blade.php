@extends('layouts.app')

@section('title', 'Countries')

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
        <h1 class="h3 mb-0">Countries</h1>
        @can('core.manage')
            <x-button href="{{ route('countries.create') }}" variant="success">Add New Country</x-button>
        @endcan
    </div>

    <x-filter-bar>
        <form method="GET" action="{{ route('countries.index') }}" class="row g-2 align-items-end">
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
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('countries.index') }}" class="btn btn-dark w-100">Reset</a>
            </div>
        </form>
    </x-filter-bar>

    <p class="text-muted">{{ $items->total() }} countries found.</p>

    <x-table>
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->code }}</td>
                        <td><x-badge>{{ $item->status }}</x-badge></td>
                        <td class="text-nowrap">
                            @can('core.view')
                                <x-button href="{{ route('countries.show', $item) }}" variant="primary"
                                    class="btn-sm">View</x-button>
                            @endcan
                            @can('core.manage')
                                <x-button href="{{ route('countries.edit', $item) }}" variant="warning"
                                    class="btn-sm">Edit</x-button>
                            @endcan
                            @can('core.manage')
                                {{-- ✅ SweetAlert2 ট্রিগার করার জন্য ক্লাস এবং data-url অ্যাট্রিবিউট --}}
                                <x-button href="#" variant="danger" class="btn-sm delete-btn"
                                    data-url="{{ route('countries.destroy', $item) }}">Delete</x-button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No countries found. Adjust the filters or
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
                            // ইউজার Confirm করলে ফর্ম সাবমিট হবে
                            deleteForm.action = url;
                            deleteForm.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush