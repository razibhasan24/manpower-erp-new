@csrf

<div class="row g-4">
    {{-- Recruitment --}}
    <div class="col-md-6">
        <label for="recruitment_id" class="form-label fw-semibold">Recruitment <span class="text-danger">*</span></label>
        <select name="recruitment_id" id="recruitment_id" class="form-select @error('recruitment_id') is-invalid @enderror"
            required>
            <option value="">-- {{ __('Select recruitment') }} --</option>
            @foreach ($recruitments as $recruitment)
                <option value="{{ $recruitment->id }}" data-service-type="{{ $recruitment->service_type }}"
                    @selected(old('recruitment_id', $record->recruitment_id ?? request('recruitment_id')) == $recruitment->id)>
                    {{ $recruitment->tracking_number }} — {{ $recruitment->passportHolder?->full_name }}
                </option>
            @endforeach
        </select>
        @error('recruitment_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Airline --}}
    <div class="col-md-6">
        <label for="airline_id" class="form-label fw-semibold">Airline <span class="text-danger">*</span></label>
        <select name="airline_id" id="airline_id" class="form-select @error('airline_id') is-invalid @enderror"
            required>
            <option value="">-- {{ __('Select') }} --</option>
            @foreach ($airlines ?? [] as $option)
                <option value="{{ $option->id }}" @selected(old('airline_id', $record->airline_id ?? null) == $option->id)>
                    {{ $option->name ?? ($option->title ?? $option->lot_number) }}
                </option>
            @endforeach
        </select>
        @error('airline_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Flight Number --}}
    <div class="col-md-4">
        <label for="flight_number" class="form-label fw-semibold">Flight Number <span
                class="text-danger">*</span></label>
        <input type="text" name="flight_number" id="flight_number"
            class="form-control @error('flight_number') is-invalid @enderror" required
            value="{{ old('flight_number', $record->flight_number ?? '') }}" placeholder="e.g. EK-585">
        @error('flight_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Departure Date --}}
    <div class="col-md-4">
        <label for="departure_date" class="form-label fw-semibold">Departure Date</label>
        <input type="date" name="departure_date" id="departure_date"
            class="form-control @error('departure_date') is-invalid @enderror"
            value="{{ old('departure_date', $record->departure_date ?? '') }}">
        @error('departure_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Status --}}
    <div class="col-md-4">
        <label for="status" class="form-label fw-semibold">Status</label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
            <option value="pending" @selected(old('status', $record->status ?? '') == 'pending')>Pending</option>
            <option value="ticket_issued" @selected(old('status', $record->status ?? '') == 'ticket_issued')>Ticket Issued</option>
            <option value="cancelled" @selected(old('status', $record->status ?? '') == 'cancelled')>Cancelled</option>
            <option value="rescheduled" @selected(old('status', $record->status ?? '') == 'rescheduled')>Rescheduled</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Travel Agency --}}
    <div class="col-md-6">
        <label for="travel_agency_id" class="form-label fw-semibold">Travel Agency <span
                class="text-danger">*</span></label>
        <select name="travel_agency_id" id="travel_agency_id"
            class="form-select @error('travel_agency_id') is-invalid @enderror" required>
            <option value="">-- {{ __('Select') }} --</option>
            @foreach ($travelAgencys ?? [] as $option)
                <option value="{{ $option->id }}" @selected(old('travel_agency_id', $record->travel_agency_id ?? null) == $option->id)>
                    {{ $option->name ?? ($option->title ?? $option->lot_number) }}
                </option>
            @endforeach
        </select>
        @error('travel_agency_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Ticket Number --}}
    <div class="col-md-6">
        <label for="ticket_number" class="form-label fw-semibold">Ticket Number</label>
        <input type="text" name="ticket_number" id="ticket_number"
            class="form-control @error('ticket_number') is-invalid @enderror"
            value="{{ old('ticket_number', $record->ticket_number ?? '') }}" placeholder="e.g. 176-1234567890">
        @error('ticket_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Departure Airport --}}
    <div class="col-md-6">
        <label for="departure_airport" class="form-label fw-semibold">Departure Airport</label>
        <input type="text" name="departure_airport" id="departure_airport"
            class="form-control @error('departure_airport') is-invalid @enderror"
            value="{{ old('departure_airport', $record->departure_airport ?? '') }}" placeholder="e.g. DAC">
        @error('departure_airport')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Arrival Airport --}}
    <div class="col-md-6">
        <label for="arrival_airport" class="form-label fw-semibold">Arrival Airport</label>
        <input type="text" name="arrival_airport" id="arrival_airport"
            class="form-control @error('arrival_airport') is-invalid @enderror"
            value="{{ old('arrival_airport', $record->arrival_airport ?? '') }}" placeholder="e.g. DXB">
        @error('arrival_airport')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ✅ Hajj / Umrah Hotel Fields (Conditional) --}}
    <div class="col-12 hajj-hotel-fields d-none">
        <div class="bg-light border rounded p-4">
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-building me-2"></i> Hajj / Umrah Hotel Details</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Hotel Name</label>
                    <input name="hotel_name" class="form-control"
                        value="{{ old('hotel_name', $record->hotel_name ?? '') }}" placeholder="e.g. Makkah Hilton">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Check In</label>
                    <input type="date" name="hotel_check_in" class="form-control"
                        value="{{ old('hotel_check_in', isset($record->hotel_check_in) ? $record->hotel_check_in?->format('Y-m-d') : '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Check Out</label>
                    <input type="date" name="hotel_check_out" class="form-control"
                        value="{{ old('hotel_check_out', isset($record->hotel_check_out) ? $record->hotel_check_out?->format('Y-m-d') : '') }}">
                </div>
            </div>
        </div>
    </div>

    {{-- Ticket Upload --}}
    <div class="col-md-6">
        <label for="ticket_path" class="form-label fw-semibold">Ticket Document</label>
        <input type="file" name="ticket_path" id="ticket_path"
            class="form-control @error('ticket_path') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
        @error('ticket_path')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @isset($record->ticket_path)
            <div class="mt-2">
                <a href="{{ route('flights.download', [$record, 'ticket_path']) }}"
                    class="text-decoration-none small fw-medium">
                    <i class="bi bi-download me-1"></i> {{ __('Download current ticket') }}
                </a>
            </div>
        @endisset
    </div>

    {{-- NOC Upload --}}
    <div class="col-md-6">
        <label for="noc_path" class="form-label fw-semibold">NOC Document</label>
        <input type="file" name="noc_path" id="noc_path"
            class="form-control @error('noc_path') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
        @error('noc_path')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @isset($record->noc_path)
            <div class="mt-2">
                <a href="{{ route('flights.download', [$record, 'noc_path']) }}"
                    class="text-decoration-none small fw-medium">
                    <i class="bi bi-download me-1"></i> {{ __('Download current NOC') }}
                </a>
            </div>
        @endisset
    </div>
</div>

{{-- Action Buttons --}}
<div class="d-flex gap-2 mt-4 pt-3 border-top">
    <x-button type="submit" variant="success" class="px-4">{{ __('Save') }}</x-button>
    <x-button href="{{ route('flights.index') }}" variant="danger">{{ __('Cancel') }}</x-button>
</div>

{{-- ✅ JavaScript for Hajj/Umrah Toggle --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const recruitment = document.getElementById('recruitment_id');
        const hotelFields = document.querySelector('.hajj-hotel-fields');

        function toggleHotelFields() {
            if (!recruitment || !hotelFields) return;

            // Get the selected option and check its data-service-type
            const selectedOption = recruitment.options[recruitment.selectedIndex];
            const serviceType = selectedOption ? selectedOption.dataset.serviceType : null;

            if (serviceType === 'hajj_umrah') {
                hotelFields.classList.remove('d-none');
            } else {
                hotelFields.classList.add('d-none');
            }
        }

        if (recruitment) {
            recruitment.addEventListener('change', toggleHotelFields);
            toggleHotelFields(); // Run on page load to set initial state
        }
    });
</script>
