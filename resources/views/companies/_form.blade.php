            <div class="mb-3">
                <x-input type="text" name="name" label="Company Name" :value="old('name', $company->name ?? '')" required />
                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="country_id" class="form-label">Country</label>
                <select id="country_id" name="country_id" class="form-select @error('country_id') is-invalid @enderror" required>
                    <option value="">-- Select --</option>
                    @foreach ($countries as $option)
                        <option value="{{ $option->id }}" @selected((string) old('country_id', (string) ($company->country_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
                    @endforeach
                </select>
                @error('country_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" @selected(old('status', $company->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $company->status ?? 'active') === 'inactive')>Inactive</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
