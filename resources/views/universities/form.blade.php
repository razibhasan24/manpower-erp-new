@extends('layouts.app')

@section('title', ($university->exists ? 'Edit' : 'Add') . ' University')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-0">{{ $university->exists ? 'Edit' : 'Add' }} University</h1>
        <p class="text-muted small mt-1">Fill in the details below to manage university information.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST"
                action="{{ $university->exists ? route('universities.update', $university) : route('universities.store') }}">
                @csrf
                @if ($university->exists)
                    @method('PUT')
                @endif

                <div class="row g-4">
                    {{-- Code --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Code <span class="text-danger">*</span></label>
                        <input name="code" class="form-control @error('code') is-invalid @enderror" required
                            value="{{ old('code', $university->code) }}" placeholder="e.g. DU-101">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- University Name --}}
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">University Name <span class="text-danger">*</span></label>
                        <input name="name" class="form-control @error('name') is-invalid @enderror" required
                            value="{{ old('name', $university->name) }}" placeholder="e.g. University of Dhaka">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Country --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Country</label>
                        <input name="country" class="form-control @error('country') is-invalid @enderror"
                            value="{{ old('country', $university->country) }}" placeholder="e.g. Bangladesh">
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- City --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">City</label>
                        <input name="city" class="form-control @error('city') is-invalid @enderror"
                            value="{{ old('city', $university->city) }}" placeholder="e.g. Dhaka">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Contact Email --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Contact Email</label>
                        <input type="email" name="contact_email"
                            class="form-control @error('contact_email') is-invalid @enderror"
                            value="{{ old('contact_email', $university->contact_email) }}"
                            placeholder="admission@university.edu">
                        @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Contact Phone --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Contact Phone</label>
                        <input name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror"
                            value="{{ old('contact_phone', $university->contact_phone) }}" placeholder="+880 1234567890">
                        @error('contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Active Status (Modern Switch) --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold d-block">Status</label>
                        <input type="hidden" name="is_active" value="0">
                        <div
                            class="form-check form-switch border rounded p-3 ps-5 bg-light h-100 d-flex align-items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                class="form-check-input mt-0 @error('is_active') is-invalid @enderror"
                                @checked(old('is_active', $university->is_active ?? true))>
                            <label class="form-check-label fw-medium ms-2" for="is_active">Active</label>
                            @error('is_active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <x-button variant="success" class="px-4">Save</x-button>
                    <x-button variant="danger" href="{{ route('universities.index') }}">Cancel</x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
