            <div class="mb-3">
                <x-input type="text" name="name" label="Country Name" :value="old('name', $country->name ?? '')" required />
                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <x-input type="text" name="code" label="ISO Code" :value="old('code', $country->code ?? '')" required />
                @error('code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" @selected(old('status', $country->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $country->status ?? 'active') === 'inactive')>Inactive</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
