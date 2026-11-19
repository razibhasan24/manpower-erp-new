<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="name" class="form-label">{{ __('Employee name') }} <span class="text-danger">*</span></label>
        <input id="name" name="name" value="{{ old('name', $item->name ?? $item->user?->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" maxlength="255" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="employee_code" class="form-label">{{ __('Employee code') }} <span class="text-danger">*</span></label>
        <input id="employee_code" name="employee_code" value="{{ old('employee_code', $item->employee_code ?? '') }}" class="form-control @error('employee_code') is-invalid @enderror" maxlength="50" required>
        @error('employee_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="phone" class="form-label">{{ __('Phone') }}</label>
        <input id="phone" name="phone" value="{{ old('phone', $item->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror" maxlength="30">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="branch_id" class="form-label">{{ __('Branch') }}</label>
        <select id="branch_id" name="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
            <option value="">{{ __('No branch') }}</option>
            @foreach($branches as $branch)<option value="{{ $branch->id }}" @selected((string) old('branch_id', $item->branch_id ?? '') === (string) $branch->id)>{{ $branch->name }}</option>@endforeach
        </select>
        @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="department_id" class="form-label">{{ __('Department') }}</label>
        <select id="department_id" name="department_id" class="form-select @error('department_id') is-invalid @enderror">
            <option value="">{{ __('Select department') }}</option>
            @foreach($departments as $department)<option value="{{ $department->id }}" @selected((string) old('department_id', $item->department_id ?? '') === (string) $department->id)>{{ $department->name }}</option>@endforeach
        </select>
        @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="designation_id" class="form-label">{{ __('Designation') }}</label>
        <select id="designation_id" name="designation_id" class="form-select @error('designation_id') is-invalid @enderror">
            <option value="">{{ __('Select designation') }}</option>
            @foreach($designations as $designation)<option value="{{ $designation->id }}" @selected((string) old('designation_id', $item->designation_id ?? '') === (string) $designation->id)>{{ $designation->name }}</option>@endforeach
        </select>
        @error('designation_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="joining_date" class="form-label">{{ __('Joining date') }}</label>
        <input id="joining_date" type="date" name="joining_date" value="{{ old('joining_date', isset($item) && $item->joining_date ? $item->joining_date->format('Y-m-d') : '') }}" class="form-control @error('joining_date') is-invalid @enderror">
        @error('joining_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="salary" class="form-label">{{ __('Salary') }}</label>
        <input id="salary" type="number" step="0.01" min="0" name="salary" value="{{ old('salary', $item->salary ?? '') }}" class="form-control @error('salary') is-invalid @enderror">
        @error('salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach(['active','inactive','terminated'] as $status)<option value="{{ $status }}" @selected(old('status', $item->status ?? 'active') === $status)>{{ __(ucfirst($status)) }}</option>@endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="address" class="form-label">{{ __('Address') }}</label>
        <textarea id="address" name="address" rows="3" maxlength="500" class="form-control @error('address') is-invalid @enderror">{{ old('address', $item->address ?? '') }}</textarea>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
