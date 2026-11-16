<?php

namespace App\Http\Requests\Flight;

use Illuminate\Foundation\Http\FormRequest;

class StoreFlightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('flight.create');
    }

    public function rules(): array
    {
        return [
            'recruitment_id' => ['required', 'exists:recruitments,id'],
            'airline_id' => ['required', 'exists:airlines,id'],
            'flight_number' => ['required', 'string', 'max:255'],
            'travel_agency_id' => ['required', 'exists:travel_agencies,id'],
            'departure_date' => ['nullable', 'date'],
            'departure_airport' => ['nullable', 'string', 'max:255'],
            'arrival_airport' => ['nullable', 'string', 'max:255'],
            'ticket_number' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:pending,ticket_issued,cancelled,rescheduled'],
            'ticket_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'noc_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
