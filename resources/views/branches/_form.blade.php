<div class="row g-3">

    {{-- Row 1 --}}
    <div class="col-md-4">
        <x-input type="text" name="name" label="Branch Name" :value="old('name', $branch->name ?? '')" required />
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <x-input type="text" name="code" label="Branch Code" :value="old('code', $branch->code ?? '')" required />
        @error('code')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <x-input type="text" name="phone" label="Phone" :value="old('phone', $branch->phone ?? '')" />
        @error('phone')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Row 2 --}}
    <div class="col-md-4">
        <x-input type="email" name="email" label="Email" :value="old('email', $branch->email ?? '')" />
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="manager_id" class="form-label">Branch Manager</label>
        <select id="manager_id" name="manager_id" class="form-select @error('manager_id') is-invalid @enderror">
            <option value="">-- Select Manager --</option>
            @foreach ($users as $option)
                <option value="{{ $option->id }}" @selected((string) old('manager_id', (string) ($branch->manager_id ?? '')) === (string) $option->id)>
                    {{ $option->name }}
                </option>
            @endforeach
        </select>
        @error('manager_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" @selected(old('status', $branch->status ?? 'active') === 'active')>
                Active
            </option>
            <option value="inactive" @selected(old('status', $branch->status ?? 'active') === 'inactive')>
                Inactive
            </option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Row 3 --}}
    <div class="col-md-12">
        <label for="address" class="form-label">Address</label>
        <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $branch->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>
