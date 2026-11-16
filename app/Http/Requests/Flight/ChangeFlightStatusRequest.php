<?php

namespace App\Http\Requests\Flight;

use App\Services\FlightService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeFlightStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $record = $this->route('flight');
        return $record !== null && $this->user() !== null && $this->user()->can('flight.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(FlightService::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
