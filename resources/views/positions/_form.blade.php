            <div class="mb-3">
                <x-input type="text" name="title" label="Job Title" :value="old('title', $position->title ?? '')" required />
                @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="company_id" class="form-label">Company</label>
                <select id="company_id" name="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
                    <option value="">-- Select --</option>
                    @foreach ($companies as $option)
                        <option value="{{ $option->id }}" @selected((string) old('company_id', (string) ($position->company_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
                    @endforeach
                </select>
                @error('company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="country_id" class="form-label">Country</label>
                <select id="country_id" name="country_id" class="form-select @error('country_id') is-invalid @enderror" required>
                    <option value="">-- Select --</option>
                    @foreach ($countries as $option)
                        <option value="{{ $option->id }}" @selected((string) old('country_id', (string) ($position->country_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
                    @endforeach
                </select>
                @error('country_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <x-input type="number" name="salary_min" label="Minimum Salary" :value="old('salary_min', $position->salary_min ?? '')" />
                @error('salary_min')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <x-input type="number" name="salary_max" label="Maximum Salary" :value="old('salary_max', $position->salary_max ?? '')" />
                @error('salary_max')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <x-input type="text" name="currency" label="Currency" :value="old('currency', $position->currency ?? '')" />
                @error('currency')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" @selected(old('status', $position->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $position->status ?? 'active') === 'inactive')>Inactive</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
