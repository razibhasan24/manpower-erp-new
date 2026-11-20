@extends('layouts.app')
@section('title', __('Create Visa Lot'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">{{ __('Create Visa Lot') }}</h1>
    <a class="btn btn-outline-secondary" href="{{ route('visa-register.lots') }}">{{ __('Back to Visa Lots') }}</a>
</div>

<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('visa-register.lots.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label for="lot_number" class="form-label">{{ __('Lot Number') }} *</label>
                <input id="lot_number" name="lot_number" value="{{ old('lot_number') }}" maxlength="255" required class="form-control @error('lot_number') is-invalid @enderror">
                @error('lot_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="country_id" class="form-label">{{ __('Country') }}</label>
                <select id="country_id" name="country_id" class="form-select @error('country_id') is-invalid @enderror">
                    <option value="">{{ __('Select country (optional)') }}</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" @selected((string) old('country_id') === (string) $country->id)>{{ $country->name }}</option>
                    @endforeach
                </select>
                @error('country_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label">{{ __('Status') }} *</label>
                <select id="status" name="status" required class="form-select @error('status') is-invalid @enderror">
                    @foreach(['open', 'submitted', 'closed', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected(old('status', 'open') === $status)>{{ __(ucfirst($status)) }}</option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="remarks" class="form-label">{{ __('Remarks') }}</label>
                <textarea id="remarks" name="remarks" rows="4" maxlength="5000" class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks') }}</textarea>
                @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">{{ __('Save Lot') }}</button>
            <a class="btn btn-outline-secondary" href="{{ route('visa-register.lots') }}">{{ __('Cancel') }}</a>
        </div>
    </form>
</div></div>
@endsection
