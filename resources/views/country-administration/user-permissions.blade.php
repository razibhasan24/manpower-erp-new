@extends('layouts.app')

@section('title', __('Country User Permissions'))

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
    <h1 class="mb-3">{{ __('Country User Permissions') }}</h1>
    <div class="alert alert-info">
        {{ __('This page shows the country access currently assigned to each user. Assignments are managed through user administration.') }}
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input name="search" value="{{ request('search') }}" class="form-control"
                placeholder="{{ __('Search user name or email') }}">
        </div>
        <div class="col-md-4">
            <select name="country_id" class="form-select">
                <option value="">{{ __('All countries') }}</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" @selected((string) request('country_id') === (string) $country->id)>{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary">{{ __('Filter') }}</button>
        </div>
        <div class="col-auto">
            <a class="btn btn-danger" href="{{ route('country-user-permissions.index') }}">{{ __('Clear') }}</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Branch') }}</th>
                    <th>{{ __('Roles') }}</th>
                    <th>{{ __('Assigned countries') }}</th>
                    <th>{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->branch?->name ?? '—' }}</td>
                        <td>{{ $user->roles->pluck('name')->map(fn($role) => __(\Illuminate\Support\Str::headline($role)))->join(', ') ?: '—' }}
                        </td>
                        <td>{{ $user->countries->pluck('name')->join(', ') ?: __('No countries assigned') }}</td>
                        <td><x-badge :status="$user->status ?? 'active'" /></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">{{ __('No users found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ✅ Pagination (শুধু বাম পাশে Showing দেখাবে, ডান পাশে শুধু বাটন) --}}
    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-muted small">
            Showing {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results
        </div>
        <div class="custom-pagination">
            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
