@extends('layouts.app')

@section('title', __('Flight Booking'))

@section('content')
    {{-- ✅ Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ __('Flight Booking') }}</h1>
            <p class="text-muted small mb-0">View flight details, download tickets, and manage booking status.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @can('flight.edit')
                <x-button href="{{ route('flights.edit', $record) }}" variant="warning" class="px-3">
                    {{ __('Edit') }}
                </x-button>
            @endcan
            <x-button href="{{ route('flights.index') }}" variant="dark">
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
                    <div><x-badge :status="$record->status" /></div>
                </div>

                {{-- Recruitment --}}
                <div class="col-md-6 col-lg-8">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Recruitment</div>
                    <div class="fw-medium text-dark">
                        {{ $record->recruitment?->tracking_number ?? '—' }}
                        <span class="text-muted">— {{ $record->recruitment?->passportHolder?->full_name ?? '' }}</span>
                    </div>
                </div>

                {{-- Flight Info --}}
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Airline</div>
                    <div>{{ $record->airline_id ?? '—' }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Flight Number</div>
                    <div class="fw-semibold text-dark">{{ $record->flight_number ?? '—' }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Travel Agency</div>
                    <div>{{ $record->travel_agency_id ?? '—' }}</div>
                </div>

                {{-- Route & Date --}}
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Departure Date</div>
                    <div>
                        {{ $record->departure_date ? \Carbon\Carbon::parse($record->departure_date)->format('d M, Y') : '—' }}
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Departure Airport</div>
                    <div>{{ $record->departure_airport ?? '—' }}</div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Arrival Airport</div>
                    <div>{{ $record->arrival_airport ?? '—' }}</div>
                </div>

                {{-- Hotel (Conditional) --}}
                @if ($record->hotel_name)
                    <div class="col-12">
                        <div class="bg-light border rounded p-3 mt-2">
                            <div class="text-muted small text-uppercase fw-semibold mb-1"><i
                                    class="bi bi-building me-1"></i> Hotel Details</div>
                            <div class="fw-medium text-dark">
                                {{ $record->hotel_name }}
                                <span class="text-muted fw-normal ms-2">
                                    ({{ $record->hotel_check_in?->format('d M Y') }} –
                                    {{ $record->hotel_check_out?->format('d M Y') }})
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Ticket Number & Documents --}}
                <div class="col-md-6 col-lg-4">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Ticket Number</div>
                    <div>{{ $record->ticket_number ?? '—' }}</div>
                </div>

                <div class="col-12 mt-3">
                    <div class="d-flex gap-3 flex-wrap">
                        @isset($record->ticket_path)
                            <a href="{{ route('flights.download', [$record, 'ticket_path']) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download me-1"></i> {{ __('Download Ticket') }}
                            </a>
                        @endisset
                        @isset($record->noc_path)
                            <a href="{{ route('flights.download', [$record, 'noc_path']) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download me-1"></i> {{ __('Download NOC') }}
                            </a>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Workflow History --}}
    @if ($recruitment)
        <div class="mb-4">
            <h5 class="border-bottom pb-2 mb-3 text-secondary">{{ __('Workflow History') }}</h5>
            <x-workflow-timeline :recruitment="$recruitment" :stages="\App\Services\WorkflowService::stagesFor($recruitment)" />
        </div>
    @endif

    {{-- ✅ Change Status Form Card --}}
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h5 class="mb-0">{{ __('Change Status') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('flights.status', $record) }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">New Status</label>
                        <select name="status" class="form-select">
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($record->status == $status)>
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
        <form action="{{ route('flights.destroy', $record) }}" method="POST" class="d-flex justify-content-center gap-2">
            @csrf
            @method('DELETE')
            <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Keep it') }}</x-button>
            <x-button type="submit" variant="danger">{{ __('Confirm Cancel') }}</x-button>
        </form>
    </x-modal>
@endsection
