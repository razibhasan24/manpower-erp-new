@extends('layouts.app')
@section('title', __('Create Travel Agency'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">{{ __('Create Travel Agency') }}</h1>
    <a class="btn btn-outline-secondary" href="{{ route('travel-agencies.index') }}">{{ __('Back to Travel Agencies') }}</a>
</div>

<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('travel-agencies.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">{{ __('Agency Name') }} *</label>
                <input id="name" name="name" value="{{ old('name') }}" maxlength="255" required class="form-control @error('name') is-invalid @enderror">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label">{{ __('Phone') }}</label>
                <input id="phone" name="phone" value="{{ old('phone') }}" maxlength="50" class="form-control @error('phone') is-invalid @enderror">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" maxlength="255" class="form-control @error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label">{{ __('Status') }} *</label>
                <select id="status" name="status" required class="form-select @error('status') is-invalid @enderror">
                    <option value="active" @selected(old('status', 'active') === 'active')>{{ __('Active') }}</option>
                    <option value="inactive" @selected(old('status') === 'inactive')>{{ __('Inactive') }}</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="address" class="form-label">{{ __('Address') }}</label>
                <textarea id="address" name="address" rows="3" maxlength="5000" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">{{ __('Save Agency') }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('travel-agencies.index') }}">{{ __('Cancel') }}</a>
        </div>
    </form>
</div></div>
@endsection
