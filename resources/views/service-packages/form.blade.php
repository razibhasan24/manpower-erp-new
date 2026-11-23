@extends('layouts.app')

@section('title', ($package->exists ? __('Edit') : __('Add')) . ' ' . $title)

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-0">{{ $package->exists ? __('Edit') : __('Add') }} {{ $title }}</h1>
        <p class="text-muted small mt-1">{{ __('Fill in the package details below.') }}</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST"
                action="{{ $package->exists ? route('service-packages.update', [$type, $package]) : route('service-packages.store', $type) }}">
                @csrf
                @if ($package->exists)
                    @method('PUT')
                @endif

                <div class="row g-4">
                    {{-- Package Code --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" for="code">{{ __('Package Code') }} <span
                                class="text-danger">*</span></label>
                        <input id="code" name="code" class="form-control @error('code') is-invalid @enderror"
                            maxlength="40" required value="{{ old('code', $package->code) }}" placeholder="e.g. PKG-001">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Package Name --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold" for="name">{{ __('Package Name') }} <span
                                class="text-danger">*</span></label>
                        <input id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                            required value="{{ old('name', $package->name) }}" placeholder="e.g. Premium Umrah Package">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Duration --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" for="duration_days">{{ __('Duration (days)') }}</label>
                        <input id="duration_days" type="number" min="1" max="3650" name="duration_days"
                            class="form-control @error('duration_days') is-invalid @enderror"
                            value="{{ old('duration_days', $package->duration_days) }}" placeholder="e.g. 15">
                        @error('duration_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Currency --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" for="currency">{{ __('Currency') }} <span
                                class="text-danger">*</span></label>
                        <input id="currency" name="currency"
                            class="form-control text-uppercase @error('currency') is-invalid @enderror" minlength="3"
                            maxlength="3" required value="{{ old('currency', $package->currency ?: 'BDT') }}"
                            placeholder="e.g. BDT">
                        @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" for="price">{{ __('Package Price') }} <span
                                class="text-danger">*</span></label>
                        <input id="price" type="number" min="0" step="0.01" name="price"
                            class="form-control @error('price') is-invalid @enderror" required
                            value="{{ old('price', $package->price ?? '0.00') }}" placeholder="0.00">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold" for="description">{{ __('Description') }}</label>
                        <textarea id="description" name="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="{{ __('Write a short description about this package...') }}">{{ old('description', $package->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Inclusions --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold" for="inclusions">{{ __('Package Includes') }}</label>
                        <textarea id="inclusions" name="inclusions" rows="4"
                            class="form-control @error('inclusions') is-invalid @enderror"
                            placeholder="{{ __('List services or inclusions, one per line') }}">{{ old('inclusions', $package->inclusions) }}</textarea>
                        @error('inclusions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status Checkbox (Modern Switch Style) --}}
                    <div class="col-12 mt-4">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check form-switch border rounded p-3 ps-5 bg-light">
                            <input id="is_active" type="checkbox" name="is_active" value="1"
                                class="form-check-input @error('is_active') is-invalid @enderror"
                                @checked(old('is_active', $package->is_active ?? true))>
                            <label for="is_active"
                                class="form-check-label fw-medium">{{ __('Active and available to agents') }}</label>
                            @error('is_active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <x-button
                        variant="success" class="btn btn-success px-4">{{ $package->exists ? __('Save Changes') : __('Add Package') }}</x-button>
                    <x-button
                        variant="danger" href="{{ route('service-packages.index', $type) }}">{{ __('Cancel') }}</x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
