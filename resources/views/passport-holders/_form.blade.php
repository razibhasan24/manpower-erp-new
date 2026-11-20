<div class="row g-3">

    <div class="col-xl-3 col-lg-3 col-md-6">
        <x-input type="text" name="full_name" label="Full Name" :value="old('full_name', $passport_holder->full_name ?? '')" required />
        @error('full_name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <x-input type="text" name="father_name" label="Father's Name" :value="old('father_name', $passport_holder->father_name ?? '')" />
        @error('father_name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <x-input type="text" name="mother_name" label="Mother's Name" :value="old('mother_name', $passport_holder->mother_name ?? '')" />
        @error('mother_name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <x-input type="date" name="dob" label="Date of Birth" :value="old('dob', $passport_holder->dob ?? '')" />
        @error('dob')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="gender" class="form-label">Gender</label>
        <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
            <option value="">-- Select --</option>
            <option value="male" @selected(old('gender', $passport_holder->gender ?? '') === 'male')>Male</option>
            <option value="female" @selected(old('gender', $passport_holder->gender ?? '') === 'female')>Female</option>
            <option value="other" @selected(old('gender', $passport_holder->gender ?? '') === 'other')>Other</option>
        </select>
        @error('gender')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <x-input type="text" name="nationality" label="Nationality" :value="old('nationality', $passport_holder->nationality ?? '')" />
        @error('nationality')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <x-input type="text" name="phone" label="Phone" :value="old('phone', $passport_holder->phone ?? '')" required />
        @error('phone')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <x-input type="email" name="email" label="Email" :value="old('email', $passport_holder->email ?? '')" />
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    @if (!isset($passport_holder))
        <div class="col-xl-3 col-lg-3 col-md-6"><x-input type="password" name="password" label="Login Password (min. 8 characters)" required autocomplete="new-password" /></div>
    @endif

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="present_address" class="form-label">Present Address</label>
        <textarea id="present_address" name="present_address" rows="3"
            class="form-control @error('present_address') is-invalid @enderror">{{ old('present_address', $passport_holder->present_address ?? '') }}</textarea>
        @error('present_address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="permanent_address" class="form-label">Permanent Address</label>
        <textarea id="permanent_address" name="permanent_address" rows="3"
            class="form-control @error('permanent_address') is-invalid @enderror">{{ old('permanent_address', $passport_holder->permanent_address ?? '') }}</textarea>
        @error('permanent_address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <x-input type="text" name="nid" label="NID Number" :value="old('nid', $passport_holder->nid ?? '')" />
        @error('nid')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <x-input type="text" name="passport_number" label="Passport Number" :value="old('passport_number', $passport_holder->passport_number ?? '')" required />
        @error('passport_number')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <x-input type="date" name="passport_issue_date" label="Passport Issue Date" :value="old('passport_issue_date', $passport_holder->passport_issue_date ?? '')" />
        @error('passport_issue_date')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <x-input type="date" name="passport_expiry_date" label="Passport Expiry Date" :value="old('passport_expiry_date', $passport_holder->passport_expiry_date ?? '')" />
        @error('passport_expiry_date')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="agent_id" class="form-label">Agent</label>
        <select id="agent_id" name="agent_id" class="form-select @error('agent_id') is-invalid @enderror">
            <option value="">-- Select --</option>
            @foreach ($agents as $option)
                <option value="{{ $option->id }}" @selected((string) old('agent_id', (string) ($passport_holder->agent_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
            @endforeach
        </select>
        @error('agent_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="branch_id" class="form-label">Branch</label>
        <select id="branch_id" name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required>
            <option value="">-- Select --</option>
            @foreach ($branches as $option)
                <option value="{{ $option->id }}" @selected((string) old('branch_id', (string) ($passport_holder->branch_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
            @endforeach
        </select>
        @error('branch_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="country_id" class="form-label">Country</label>
        <select id="country_id" name="country_id" class="form-select @error('country_id') is-invalid @enderror">
            <option value="">-- Select --</option>
            @foreach ($countries as $option)
                <option value="{{ $option->id }}" @selected((string) old('country_id', (string) ($passport_holder->country_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
            @endforeach
        </select>
        @error('country_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="company_id" class="form-label">Company</label>
        <select id="company_id" name="company_id" class="form-select @error('company_id') is-invalid @enderror">
            <option value="">-- Select --</option>
            @foreach ($companies as $option)
                <option value="{{ $option->id }}" @selected((string) old('company_id', (string) ($passport_holder->company_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
            @endforeach
        </select>
        @error('company_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="position_id" class="form-label">Position</label>
        <select id="position_id" name="position_id" class="form-select @error('position_id') is-invalid @enderror">
            <option value="">-- Select --</option>
            @foreach ($positions as $option)
                <option value="{{ $option->id }}" @selected((string) old('position_id', (string) ($passport_holder->position_id ?? '')) === (string) $option->id)>{{ $option->title }}</option>
            @endforeach
        </select>
        @error('position_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" @selected(old('status', $passport_holder->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $passport_holder->status ?? 'active') === 'inactive')>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="photo" class="form-label">Photo (jpg/png, max 2 MB)</label>
        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png"
            class="form-control @error('photo') is-invalid @enderror">
        @error('photo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>
