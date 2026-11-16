<?php

namespace App\Http\Requests\PostArrival;

use Illuminate\Foundation\Http\FormRequest;

class StorePostArrivalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('post-arrival.create');
    }

    public function rules(): array
    {
        return [
            'recruitment_id' => ['required', 'exists:recruitments,id'],
            'arrival_date' => ['nullable', 'date'],
            'airport_pickup' => ['nullable', 'boolean'],
            'employer_contact' => ['nullable', 'string', 'max:255'],
            'accommodation' => ['nullable', 'string'],
            'feedback' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,arrived,settled,issue_reported,completed'],
        ];
    }
}
