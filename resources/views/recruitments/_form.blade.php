
<div class="row g-3">

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="passport_holder_id" class="form-label">Passport Holder</label>
        <select id="passport_holder_id" name="passport_holder_id" class="form-select @error('passport_holder_id') is-invalid @enderror" required>
            <option value="">-- Select --</option>
            @foreach ($passportHolders as $option)
                <option value="{{ $option->id }}" @selected((string) old('passport_holder_id', (string) ($recruitment->passport_holder_id ?? '')) === (string) $option->id)>{{ $option->full_name }}</option>
            @endforeach
        </select>
        @error('passport_holder_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="agent_id" class="form-label">Agent</label>
        <select id="agent_id" name="agent_id" class="form-select @error('agent_id') is-invalid @enderror">
            <option value="">-- Select --</option>
            @foreach ($agents as $option)
                <option value="{{ $option->id }}" @selected((string) old('agent_id', (string) ($recruitment->agent_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
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
                <option value="{{ $option->id }}" @selected((string) old('branch_id', (string) ($recruitment->branch_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
            @endforeach
        </select>
        @error('branch_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="country_id" class="form-label">Country</label>
        <select id="country_id" name="country_id" class="form-select @error('country_id') is-invalid @enderror" required>
            <option value="">-- Select --</option>
            @foreach ($countries as $option)
                <option value="{{ $option->id }}" @selected((string) old('country_id', (string) ($recruitment->country_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
            @endforeach
        </select>
        @error('country_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="company_id" class="form-label">Company</label>
        <select id="company_id" name="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
            <option value="">-- Select --</option>
            @foreach ($companies as $option)
                <option value="{{ $option->id }}" @selected((string) old('company_id', (string) ($recruitment->company_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
            @endforeach
        </select>
        @error('company_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="position_id" class="form-label">Position</label>
        <select id="position_id" name="position_id" class="form-select @error('position_id') is-invalid @enderror" required>
            <option value="">-- Select --</option>
            @foreach ($positions as $option)
                <option value="{{ $option->id }}" @selected((string) old('position_id', (string) ($recruitment->position_id ?? '')) === (string) $option->id)>{{ $option->title }}</option>
            @endforeach
        </select>
        @error('position_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <x-input type="number" name="expected_salary" label="Expected Salary" :value="old('expected_salary', $recruitment->expected_salary ?? '')" />
        @error('expected_salary')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6">
        <label for="remarks" class="form-label">Remarks</label>
        <textarea id="remarks" name="remarks" rows="3" class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', $recruitment->remarks ?? '') }}</textarea>
        @error('remarks')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Documents (private storage - PDF/JPG/PNG, max 5 MB each)</label>

        <div class="row g-3">

            <div class="col-xl-3 col-lg-3 col-md-6">
                <label for="documents_passport_scan" class="form-label">Passport Scan</label>
                <input type="file" id="documents_passport_scan" name="documents[passport_scan]" accept=".pdf,image/jpeg,image/png" class="form-control @error('documents.passport_scan') is-invalid @enderror">
                @error('documents.passport_scan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6">
                <label for="documents_nid_scan" class="form-label">NID Scan</label>
                <input type="file" id="documents_nid_scan" name="documents[nid_scan]" accept=".pdf,image/jpeg,image/png" class="form-control @error('documents.nid_scan') is-invalid @enderror">
                @error('documents.nid_scan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6">
                <label for="documents_photo" class="form-label">Photo</label>
                <input type="file" id="documents_photo" name="documents[photo]" accept="image/jpeg,image/png" class="form-control @error('documents.photo') is-invalid @enderror">
                @error('documents.photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6">
                <label for="documents_other" class="form-label">Other Document</label>
                <input type="file" id="documents_other" name="documents[other]" accept=".pdf,image/jpeg,image/png" class="form-control @error('documents.other') is-invalid @enderror">
                @error('documents.other')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>

</div>
