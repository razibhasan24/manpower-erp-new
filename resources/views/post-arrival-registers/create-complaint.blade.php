@extends('layouts.app')
@section('title', __('Create Post Arrival Complaint'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">{{ __('Create Post Arrival Complaint') }}</h1>
    <a class="btn btn-outline-secondary" href="{{ route('post-arrival-complaints.index') }}">{{ __('Back to complaints') }}</a>
</div>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('post-arrival-complaints.store') }}">@csrf
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
                <label for="subject" class="form-label">{{ __('Subject') }} *</label>
                <input id="subject" name="subject" value="{{ old('subject') }}" maxlength="255" required class="form-control @error('subject') is-invalid @enderror">
                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="description" class="form-label">{{ __('Description') }} *</label>
                <textarea id="description" name="description" rows="4" maxlength="5000" required class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label">{{ __('Status') }} *</label>
                <select id="status" name="status" required class="form-select @error('status') is-invalid @enderror">
                    @foreach(['open', 'in_progress', 'resolved', 'closed'] as $status)
                        <option value="{{ $status }}" @selected(old('status', 'open') === $status)>{{ __(ucwords(str_replace('_', ' ', $status))) }}</option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-flex gap-2 mt-4"><button class="btn btn-primary">{{ __('Save Complaint') }}</button><a class="btn btn-outline-secondary" href="{{ route('post-arrival-complaints.index') }}">{{ __('Cancel') }}</a></div>
    </form>
</div></div>
@endsection
