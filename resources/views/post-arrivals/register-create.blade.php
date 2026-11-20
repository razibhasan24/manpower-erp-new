@extends('layouts.app')
@section('title', $titles[$register])
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">{{ $titles[$register] }}</h1>
    <a class="btn btn-outline-secondary" href="{{ route($register.'.index') }}">{{ __('Back to list') }}</a>
</div>

<div class="card"><div class="card-body">
    <form method="POST" action="{{ route($register.'.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label for="recruitment_id" class="form-label">{{ __('Candidate / Recruitment') }} *</label>
                <select id="recruitment_id" name="recruitment_id" required class="form-select @error('recruitment_id') is-invalid @enderror">
                    <option value="">{{ __('Select candidate') }}</option>
                    @foreach($recruitments as $recruitment)
                        <option value="{{ $recruitment->id }}" @selected((string) old('recruitment_id') === (string) $recruitment->id)>{{ $recruitment->tracking_number }} — {{ $recruitment->passportHolder?->full_name }}</option>
                    @endforeach
                </select>
                @error('recruitment_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="arrival_date" class="form-label">{{ __('Arrival Date') }}</label>
                <input id="arrival_date" type="date" name="arrival_date" value="{{ old('arrival_date') }}" class="form-control @error('arrival_date') is-invalid @enderror">
                @error('arrival_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            @if($register === 'airport-pickup')
                <div class="col-12"><div class="alert alert-info mb-0">{{ __('This record will mark airport pickup as required for the selected candidate.') }}</div></div>
            @elseif($register === 'employer-information')
                <div class="col-12">
                    <label for="employer_contact" class="form-label">{{ __('Employer Contact Information') }} *</label>
                    <input id="employer_contact" name="employer_contact" value="{{ old('employer_contact') }}" maxlength="255" required class="form-control @error('employer_contact') is-invalid @enderror">
                    @error('employer_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @elseif($register === 'accommodation')
                <div class="col-12">
                    <label for="accommodation" class="form-label">{{ __('Accommodation Details') }} *</label>
                    <textarea id="accommodation" name="accommodation" rows="4" maxlength="5000" required class="form-control @error('accommodation') is-invalid @enderror">{{ old('accommodation') }}</textarea>
                    @error('accommodation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @else
                <div class="col-12">
                    <label for="feedback" class="form-label">{{ __('Candidate Feedback') }} *</label>
                    <textarea id="feedback" name="feedback" rows="4" maxlength="5000" required class="form-control @error('feedback') is-invalid @enderror">{{ old('feedback') }}</textarea>
                    @error('feedback')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @endif

            <div class="col-md-6">
                <label for="status" class="form-label">{{ __('Post-arrival Status') }} *</label>
                <select id="status" name="status" required class="form-select @error('status') is-invalid @enderror">
                    @foreach(\App\Services\PostArrivalService::STATUSES as $status)
                        <option value="{{ $status }}" @selected(old('status', 'pending') === $status)>{{ __(ucwords(str_replace('_', ' ', $status))) }}</option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            <a class="btn btn-outline-secondary" href="{{ route($register.'.index') }}">{{ __('Cancel') }}</a>
        </div>
    </form>
</div></div>
@endsection
