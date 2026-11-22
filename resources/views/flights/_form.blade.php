@csrf
        <div class="mb-3">
            <label for="recruitment_id" class="form-label">Recruitment</label>
            <select name="recruitment_id" id="recruitment_id" class="form-select" required>
                <option value="">-- { __('Select recruitment') } --</option>
                @foreach($recruitments as $recruitment)
                    <option value="{{ $recruitment->id }}" data-service-type="{{ $recruitment->service_type }}" @selected(old('recruitment_id', $record->recruitment_id ?? request('recruitment_id')) == $recruitment->id)>{{ $recruitment->tracking_number }} — {{ $recruitment->passportHolder?->full_name }}</option>
                @endforeach
            </select>
            @error('recruitment_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="airline_id" class="form-label">Airline</label>
            <select name="airline_id" id="airline_id" class="form-select" required>
                <option value="">-- { __('Select') } --</option>
                @foreach($airlines ?? [] as $option)
                    <option value="{{ $option->id }}" @selected(old('airline_id', $record->airline_id ?? null) == $option->id)>{{ $option->name ?? $option->title ?? $option->lot_number }}</option>
                @endforeach
            </select>
            @error('airline_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3"><label for="flight_number" class="form-label">Flight Number</label><input type="text" name="flight_number" id="flight_number" class="form-control" required value="{{ old('flight_number', $record->flight_number ?? '') }}">@error('flight_number') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3">
            <label for="travel_agency_id" class="form-label">Travel Agency</label>
            <select name="travel_agency_id" id="travel_agency_id" class="form-select" required>
                <option value="">-- { __('Select') } --</option>
                @foreach($travelAgencys ?? [] as $option)
                    <option value="{{ $option->id }}" @selected(old('travel_agency_id', $record->travel_agency_id ?? null) == $option->id)>{{ $option->name ?? $option->title ?? $option->lot_number }}</option>
                @endforeach
            </select>
            @error('travel_agency_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3"><label for="departure_date" class="form-label">Departure Date</label><input type="date" name="departure_date" id="departure_date" class="form-control" value="{{ old('departure_date', isset($record) ? $record->departure_date : '') }}">@error('departure_date') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="departure_airport" class="form-label">Departure Airport</label><input type="text" name="departure_airport" id="departure_airport" class="form-control"  value="{{ old('departure_airport', $record->departure_airport ?? '') }}">@error('departure_airport') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="arrival_airport" class="form-label">Arrival Airport</label><input type="text" name="arrival_airport" id="arrival_airport" class="form-control"  value="{{ old('arrival_airport', $record->arrival_airport ?? '') }}">@error('arrival_airport') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="hajj-hotel-fields d-none border rounded p-3 mb-3"><h6>Hajj / Umrah Hotel</h6><div class="mb-2"><label class="form-label">Hotel Name</label><input name="hotel_name" class="form-control" value="{{ old('hotel_name', $record->hotel_name ?? '') }}"></div><div class="row"><div class="col"><label class="form-label">Check In</label><input type="date" name="hotel_check_in" class="form-control" value="{{ old('hotel_check_in', isset($record->hotel_check_in) ? $record->hotel_check_in?->format('Y-m-d') : '') }}"></div><div class="col"><label class="form-label">Check Out</label><input type="date" name="hotel_check_out" class="form-control" value="{{ old('hotel_check_out', isset($record->hotel_check_out) ? $record->hotel_check_out?->format('Y-m-d') : '') }}"></div></div></div>
        <div class="mb-3"><label for="ticket_number" class="form-label">Ticket Number</label><input type="text" name="ticket_number" id="ticket_number" class="form-control"  value="{{ old('ticket_number', $record->ticket_number ?? '') }}">@error('ticket_number') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="status" class="form-label">Status</label><select name="status" id="status" class="form-select">
                    <option value="pending" @selected(old('status', $record->status ?? '') == 'pending')>Pending</option>
                    <option value="ticket_issued" @selected(old('status', $record->status ?? '') == 'ticket_issued')>Ticket Issued</option>
                    <option value="cancelled" @selected(old('status', $record->status ?? '') == 'cancelled')>Cancelled</option>
                    <option value="rescheduled" @selected(old('status', $record->status ?? '') == 'rescheduled')>Rescheduled</option>
                </select>@error('status') <div class="text-danger small">{{ $message }}</div> @enderror</div>
        <div class="mb-3"><label for="ticket_path" class="form-label">Ticket</label><input type="file" name="ticket_path" id="ticket_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">@error('ticket_path') <div class="text-danger small">{{ $message }}</div> @enderror @isset($record->ticket_path)<div class="mt-1"><a href="{{ route('flights.download', [$record, 'ticket_path']) }}">{{ __('Download current file') }}</a></div>@endisset</div>
        <div class="mb-3"><label for="noc_path" class="form-label">NOC</label><input type="file" name="noc_path" id="noc_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png">@error('noc_path') <div class="text-danger small">{{ $message }}</div> @enderror @isset($record->noc_path)<div class="mt-1"><a href="{{ route('flights.download', [$record, 'noc_path']) }}">{{ __('Download current file') }}</a></div>@endisset</div>
<div class="d-flex gap-2">
    <x-button type="submit">{{ __('Save') }}</x-button>
    <x-button href="{{ route('flights.index') }}" variant="secondary">{{ __('Cancel') }}</x-button>
</div>
<script>document.addEventListener('DOMContentLoaded',()=>{const recruitment=document.getElementById('recruitment_id');const fields=document.querySelector('.hajj-hotel-fields');function toggle(){fields.classList.toggle('d-none',recruitment.selectedOptions[0]?.dataset.serviceType!=='hajj_umrah')}recruitment.addEventListener('change',toggle);toggle()});</script>
