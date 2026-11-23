@push('styles')
    <style>
        /* Custom Country Checkbox Card Design */
        .country-checkbox-card {
            position: relative;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #fff;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            user-select: none;
        }

        /* Hover করলে হালকা সবুজ বর্ডার হবে */
        .country-checkbox-card:hover {
            border-color: #86efac;
            background-color: #f8fafc;
        }

        .country-checkbox-card .form-check-input {
            cursor: pointer;
            width: 1.2em;
            height: 1.2em;
            margin-top: 0;
        }

        .animated-red-border {
            border: 2px solid rgb(14, 209, 7);
            animation: redBorderAnimation 1.2s infinite;
        }

        @keyframes redBorderAnimation {
            0% {
                border-color: rgb(234, 39, 212);
                box-shadow: 0 0 3px red;
            }

            50% {
                border-color: transparent;
                box-shadow: 0 0 12px rgb(22, 3, 194);
            }

            100% {
                border-color: red;
                box-shadow: 0 0 3px red;
            }
        }

        /* ✅ Checked অবস্থায় সবুজ আউটলাইন ও ব্যাকগ্রাউন্ড */
        .country-checkbox-card:has(.form-check-input:checked) {
            border-color: #198754;
            /* Success Green */
            background-color: #f0fdf4;
            /* হালকা সবুজ ব্যাকগ্রাউন্ড */
            box-shadow: 0 0 0 1px #198754;
            /* সবুজ আউটলাইন */
        }

        /* ✅ চেকবক্সের ভেতরের টিক চিহ্নও সবুজ করা হলো */
        .country-checkbox-card .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }
    </style>
@endpush

<div class="row g-3">

    <div class="col-md-4">
        <x-input type="text" name="name" label="Agent Name" :value="old('name', $agent->name ?? '')" required />
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <x-input type="text" name="phone" label="Phone" :value="old('phone', $agent->phone ?? '')" required />
        @error('phone')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <x-input type="email" name="email" label="Email" :value="old('email', $agent->email ?? '')" />
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <x-input type="text" name="nid" label="NID Number" :value="old('nid', $agent->nid ?? '')" />
        @error('nid')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <x-input type="text" name="trade_license" label="Trade License" :value="old('trade_license', $agent->trade_license ?? '')" />
        @error('trade_license')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
        <select id="branch_id" name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required>
            <option value="">-- Select --</option>
            @foreach ($branches as $option)
                <option value="{{ $option->id }}" @selected((string) old('branch_id', (string) ($agent->branch_id ?? '')) === (string) $option->id)>
                    {{ $option->name }}
                </option>
            @endforeach
        </select>
        @error('branch_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="address" class="form-label">Address</label>
        <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $agent->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" @selected(old('status', $agent->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $agent->status ?? 'active') === 'inactive')>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if (!isset($agent))
        <div class="col-md-4">
            <x-input type="password" name="password" label="Login Password (min. 8 characters)" required
                autocomplete="new-password" />
        </div>
    @endif

    {{-- ✅ Authorised Countries --}}
    <div class="col-12 mt-4">
        <label
            class="form-label fw-bold mb-2 text-white bg-success px-3 py-2 rounded-2 d-inline-block fs-5 animated-red-border">
            <i class="fas fa-globe me-2"></i>
            Authorised Countries
        </label>
        <div class="row g-3">
            @foreach ($countries as $country)
                <div class="col-md-4">
                    <label class="country-checkbox-card p-3 d-flex align-items-center gap-3 h-100"
                        for="country-{{ $country->id }}">
                        <input class="form-check-input flex-shrink-0" type="checkbox" name="country_ids[]"
                            value="{{ $country->id }}" id="country-{{ $country->id }}" @checked(in_array($country->id, old('country_ids', isset($agent) ? $agent->countries->pluck('id')->all() : [])))>

                        <span class="fw-medium text-dark text-truncate" title="{{ $country->name }}">
                            {{ $country->name }}
                        </span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

</div>
