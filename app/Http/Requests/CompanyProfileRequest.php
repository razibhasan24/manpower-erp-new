<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'software_name' => ['required', 'string', 'max:255'],
            'software_short_name' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'primary_color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'secondary_color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'currency' => ['required', 'string', 'size:3'],
            'language' => ['required', 'string', 'in:en,bn'],
            'timezone' => ['required', 'string', 'max:64'],
            'date_format' => ['required', 'string', 'max:20'],
            'invoice_prefix' => ['required', 'string', 'max:10'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,ico,webp', 'max:512'],
        ];
    }
}
