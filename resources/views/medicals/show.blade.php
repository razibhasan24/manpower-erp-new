@extends('layouts.app')

@section('title', __('Medical Assessment'))

@section('content')
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ __('Medical Assessment') }}</h1>
            <p class="text-muted small mb-0">View and manage medical results and status.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @can('medical.edit')
                <x-button href="{{ route('medicals.edit', $record) }}" variant="primary" class="px-3">
                    {{ __('Edit') }}
                </x-button>
            @endcan
            <x-button href="{{ route('medicals.index') }}" variant="secondary">
                {{ __('Back to List') }}
            </x-button>
            {{-- ✅ Cancel Record বাটন (মডাল ওপেন করার জন্য) --}}
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                {{ __('Cancel Record') }}
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ✅ Details Card --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                {{-- Status --}}
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">{{ __('Status') }}</div>
                    <div><x-badge :status="$record->result" /></div>
                </div>

                {{-- Recruitment --}}
                <div class="col-md-6 col-lg-8">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Recruitment</div>
                    <div class="fw-medium text-dark">
                        {{ $record->recruitment?->tracking_number ?? '—' }}
                        <span class="text-muted">— {{ $record->recruitment?->passportHolder?->full_name ?? '' }}</span>
                    </div>
                </div>

                {{-- Medical Centre --}}
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Medical Centre</div>
                    <div>{{ $record->medical_centre_id ?? '—' }}</div>
                </div>

                {{-- Medical Date --}}
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Medical Date</div>
                    <div>{{ $record->medical_date ?? '—' }}</div>
                </div>

                {{-- Result --}}
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Result</div>
                    <div class="fw-semibold text-capitalize">{{ $record->result ?? '—' }}</div>
                </div>

                {{-- Remarks --}}
                <div class="col-12">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Remarks</div>
                    <div class="bg-light p-3 rounded border">{{ $record->remarks ?? __('No remarks provided.') }}</div>
                </div>

                {{-- Medical Report --}}
                @isset($record->report_path)
                    <div class="col-12">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Medical Report</div>
                        <a href="{{ route('medicals.download', [$record, 'report_path']) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download me-1"></i> {{ __('Download Attachment') }}
                        </a>
                    </div>
                @endisset
            </div>
        </div>
    </div>

    {{-- ✅ Workflow History --}}
    @if ($recruitment)
        <div class="mb-4">
            <h5 class="border-bottom pb-2 mb-3 text-secondary">{{ __('Workflow History') }}</h5>
            <x-workflow-timeline :recruitment="$recruitment" />
        </div>
    @endif

    {{-- ✅ Change Status Form Card --}}
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h5 class="mb-0">{{ __('Change Status') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('medicals.status', $record) }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">New Status</label>
                        <select name="status" class="form-select">
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($record->result == $status)>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Remarks</label>
                        <input type="text" name="remarks" class="form-control"
                            placeholder="{{ __('Add a short remark (optional)') }}">
                    </div>
                    <div class="col-md-3">
                        <x-button type="submit" variant="primary" class="w-100">{{ __('Update Status') }}</x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ✅ Cancel Modal --}}
    <x-modal id="cancel-modal" title="{{ __('Cancel Record') }}">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mb-3"
                style="width: 60px; height: 60px;">
                <i class="bi bi-exclamation-triangle fs-2"></i>
            </div>
            <h5 class="mb-1">Are you sure?</h5>
            <p class="text-muted small">{{ __('This cancels (archives) the record. It is not permanently deleted.') }}</p>
        </div>
        <form action="{{ route('medicals.destroy', $record) }}" method="POST" class="d-flex justify-content-center gap-2">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">{{ __('Keep it') }}</button>
            <x-button type="submit" variant="danger">{{ __('Confirm Cancel') }}</x-button>
        </form>
    </x-modal>
@endsection
