<?php

namespace App\Http\Requests\PostArrival;

use App\Services\PostArrivalService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangePostArrivalStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $record = $this->route('postArrival');
        return $record !== null && $this->user() !== null && $this->user()->can('post-arrival.edit');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(PostArrivalService::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
