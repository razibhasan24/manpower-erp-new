@extends('layouts.app')

@section('title', 'Create Vendor')

@section('content')
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Create Vendor</h1>
            <p class="text-muted small mb-0">Add a new vendor and automatically create its payable account.</p>
        </div>
        <x-button href="{{ route('reports.financial.sub-ledger.due-list', ['type' => 'vendor']) }}" variant="dark"
            class="btn btn-dark btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Vendor Due List
        </x-button>
    </div>

    {{-- ✅ Error Summary --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ✅ Form Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('reports.financial.sub-ledger.vendor.store') }}">
                @csrf

                <div class="row g-4">
                    {{-- Vendor Name --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold small text-uppercase">
                            Vendor Name <span class="text-danger">*</span>
                        </label>
                        <input id="name" name="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" required maxlength="255"
                            placeholder="e.g. Acme Corporation">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold small text-uppercase">Phone</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}"
                            class="form-control @error('phone') is-invalid @enderror" maxlength="30"
                            placeholder="e.g. +880 1712345678">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold small text-uppercase">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="e.g. contact@acme.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Branch --}}
                    <div class="col-md-6">
                        <label for="branch_id" class="form-label fw-semibold small text-uppercase">Branch</label>
                        <select id="branch_id" name="branch_id"
                            class="form-select @error('branch_id') is-invalid @enderror">
                            <option value="">Select branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected(old('branch_id', auth()->user()->branch_id) == $branch->id)>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="col-12">
                        <label for="address" class="form-label fw-semibold small text-uppercase">Address</label>
                        <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                            placeholder="Enter full vendor address...">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ✅ Action Buttons --}}
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <x-button href="{{ route('reports.financial.sub-ledger.due-list', ['type' => 'vendor']) }}"
                        variant="danger" class="btn btn-danger px-4">
                        Cancel
                    </x-button>
                    <x-button variant="primary" type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-person-plus me-1"></i> Create Vendor & Payable Account
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
