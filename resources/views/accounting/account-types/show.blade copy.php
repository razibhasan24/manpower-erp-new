<!-- @extends('layouts.app')
@section('title', 'Account Type')
@section('content')
    <h1 class="mb-3">{{ $item->name }}</h1>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <tr>
                    <th style="width:200px">Name</th>
                    <td>{{ $item->name }}</td>
                </tr>
                <tr>
                    <th>Normal Balance</th>
                    <td>{{ $item->normal_balance }}</td>
                </tr>
            </table>
            <h5 class="mt-4">Accounts</h5>
            <ul>
                @foreach ($item->accounts as $account)
                    <li>{{ $account->code }} — {{ $account->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection -->

@extends('layouts.app')

@section('title', 'Account Type')

<!-- @section('content')
{{-- ✅ Header Section --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $item->name }}</h1>
        <p class="text-muted small mb-0">Account Type Details and Linked Accounts.</p>
    </div>
    <div class="d-flex gap-2">
        <x-button href="{{ route('accounting.account-types.index') }}" variant="secondary" class="px-3">
            <i class="bi bi-arrow-left me-1"></i> {{ __('Back to List') }}
        </x-button>
    </div>
</div>

{{-- ✅ Details Card --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            {{-- Name --}}
            <div class="col-md-6 col-lg-4">
                <div class="text-muted small text-uppercase fw-semibold mb-1">{{ __('Name') }}</div>
                <div class="fw-bold fs-5 text-dark">{{ $item->name }}</div>
            </div>

            {{-- Normal Balance --}}
            <div class="col-md-6 col-lg-4">
                <div class="text-muted small text-uppercase fw-semibold mb-1">{{ __('Normal Balance') }}</div>
                <div>
                    {{-- ✅ Debit/Credit এর জন্য সুন্দর ব্যাজ --}}
                    @if(strtolower($item->normal_balance) === 'debit')
                    <span
                        class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-2 fs-6">
                        <i class="bi bi-arrow-up-right me-1"></i> {{ ucfirst($item->normal_balance) }}
                    </span>
                    @elseif(strtolower($item->normal_balance) === 'credit')
                    <span
                        class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-2 fs-6">
                        <i class="bi bi-arrow-down-left me-1"></i> {{ ucfirst($item->normal_balance) }}
                    </span>
                    @else
                    <span class="badge bg-light text-dark border px-3 py-2 fs-6 text-capitalize">
                        {{ $item->normal_balance }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ Linked Accounts Section --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom pt-4 pb-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold"><i class="bi bi-journal-text me-2 text-primary"></i> {{ __('Linked Accounts') }}
        </h5>
        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">
            {{ $item->accounts->count() }} {{ __('Total') }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4 py-3 fw-semibold" style="width: 150px;">{{ __('Code') }}</th>
                    <th class="py-3 fw-semibold">{{ __('Account Name') }}</th>
                    <th class="text-end pe-4 py-3 fw-semibold">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($item->accounts as $account)
                <tr>
                    <td class="ps-4 fw-medium text-dark">{{ $account->code }}</td>
                    <td>{{ $account->name }}</td>
                    <td class="text-end pe-4">
                        {{-- যদি অ্যাকাউন্ট ভিউ করার রাউট থাকে তবে এটি ব্যবহার করতে পারেন --}}
                        <a href="{{ route('accounting.accounts.show', $account) }}"
                            class="btn btn-sm btn-outline-primary px-3">
                            {{ __('View Account') }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-4 text-muted">
                        <div class="d-flex flex-column align-items-center">
                            <i class="bi bi-folder-x fs-2 mb-2 opacity-50"></i>
                            <span class="fw-medium">{{ __('No accounts linked to this type yet.') }}</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection -->