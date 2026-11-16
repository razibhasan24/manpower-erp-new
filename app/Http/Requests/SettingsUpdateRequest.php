<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->can('setting.edit');
    }

    public function rules(): array
    {
        return [
            'currency' => ['required', 'string', 'size:3'],
            'timezone' => ['required', 'string', 'max:64'],
            'language' => ['required', 'string', 'in:en,bn'],
            'date_format' => ['required', 'string', 'max:20'],
            'theme' => ['required', 'string', 'in:light,dark'],
            'software_name' => ['required', 'string', 'max:255'],
            'software_short_name' => ['required', 'string', 'max:50'],
            'primary_color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'secondary_color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'invoice_prefix' => ['required', 'string', 'max:10'],
            'voucher_prefix' => ['required', 'string', 'max:10'],
        ];
    }
}
