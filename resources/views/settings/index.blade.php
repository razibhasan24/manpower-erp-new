@extends('layouts.app')

@section('title', __('Settings') . ' — ' . \App\Services\BrandingService::appName())

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Settings') }}</li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">{{ __('Settings') }}</h1>
        <a class="btn btn-outline-secondary" href="{{ route('company-profile.edit') }}">{{ __('Company Profile') }}</a>
    </div>

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                @php
                    $flat = $settings->flatten(1)->keyBy('key');
                    $val = fn ($key, $default = '') => old($key, $flat->get($key)?->value ?? $default);
                @endphp
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="software_name" class="form-label">{{ __('Software display name') }}</label>
                        <input type="text" name="software_name" id="software_name" class="form-control" value="{{ $val('software_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="software_short_name" class="form-label">{{ __('Short name') }}</label>
                        <input type="text" name="software_short_name" id="software_short_name" class="form-control" value="{{ $val('software_short_name') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="currency" class="form-label">{{ __('Currency') }}</label>
                        <input type="text" name="currency" id="currency" class="form-control" value="{{ $val('currency', 'BDT') }}" maxlength="3" required>
                    </div>
                    <div class="col-md-4">
                        <label for="timezone" class="form-label">{{ __('Timezone') }}</label>
                        <input type="text" name="timezone" id="timezone" class="form-control" value="{{ $val('timezone', 'Asia/Dhaka') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="language" class="form-label">{{ __('Language') }}</label>
                        <select name="language" id="language" class="form-select">
                            <option value="en" {{ $val('language', 'en') === 'en' ? 'selected' : '' }}>English</option>
                            <option value="bn" {{ $val('language') === 'bn' ? 'selected' : '' }}>Bangla</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="date_format" class="form-label">{{ __('Date format') }}</label>
                        <input type="text" name="date_format" id="date_format" class="form-control" value="{{ $val('date_format', 'd-m-Y') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="theme" class="form-label">{{ __('Theme') }}</label>
                        <select name="theme" id="theme" class="form-select">
                            <option value="light" {{ $val('theme', 'light') === 'light' ? 'selected' : '' }}>{{ __('Light') }}</option>
                            <option value="dark" {{ $val('theme') === 'dark' ? 'selected' : '' }}>{{ __('Dark') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="primary_color" class="form-label">{{ __('Primary colour') }}</label>
                        <input type="color" name="primary_color" id="primary_color" class="form-control form-control-color" value="{{ $val('primary_color', '#0d6efd') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="secondary_color" class="form-label">{{ __('Secondary colour') }}</label>
                        <input type="color" name="secondary_color" id="secondary_color" class="form-control form-control-color" value="{{ $val('secondary_color', '#6c757d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="invoice_prefix" class="form-label">{{ __('Invoice prefix') }}</label>
                        <input type="text" name="invoice_prefix" id="invoice_prefix" class="form-control" value="{{ $val('invoice_prefix', 'INV') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="voucher_prefix" class="form-label">{{ __('Voucher prefix') }}</label>
                        <input type="text" name="voucher_prefix" id="voucher_prefix" class="form-control" value="{{ $val('voucher_prefix', 'VCH') }}" required>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{ __('Save settings') }}</button>
            </div>
        </div>
    </form>
@endsection
