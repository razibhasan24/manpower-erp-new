<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReverseVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorisation is enforced by the Policy in the controller
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:500'],
        ];
    }
}
