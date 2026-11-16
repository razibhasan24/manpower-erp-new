            <div class="mb-3">
                <x-input type="text" name="name" label="Agent Name" :value="old('name', $agent->name ?? '')" required />
                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <x-input type="text" name="phone" label="Phone" :value="old('phone', $agent->phone ?? '')" required />
                @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <x-input type="email" name="email" label="Email" :value="old('email', $agent->email ?? '')" />
                @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror">old('address', $agent->address ?? '')</textarea>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <x-input type="text" name="nid" label="NID Number" :value="old('nid', $agent->nid ?? '')" />
                @error('nid')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <x-input type="text" name="trade_license" label="Trade License" :value="old('trade_license', $agent->trade_license ?? '')" />
                @error('trade_license')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="branch_id" class="form-label">Branch</label>
                <select id="branch_id" name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required>
                    <option value="">-- Select --</option>
                    @foreach ($branches as $option)
                        <option value="{{ $option->id }}" @selected((string) old('branch_id', (string) ($agent->branch_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
                    @endforeach
                </select>
                @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="user_id" class="form-label">Login User (optional)</label>
                <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                    <option value="">-- Select --</option>
                    @foreach ($users as $option)
                        <option value="{{ $option->id }}" @selected((string) old('user_id', (string) ($agent->user_id ?? '')) === (string) $option->id)>{{ $option->name }}</option>
                    @endforeach
                </select>
                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" @selected(old('status', $agent->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $agent->status ?? 'active') === 'inactive')>Inactive</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Authorised Countries</label>
                <div class="row">
                    @foreach ($countries as $country)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="country_ids[]" value="{{ $country->id }}" id="country-{{ $country->id }}"
                                    @checked(in_array($country->id, old('country_ids', isset($agent) ? $agent->countries->pluck('id')->all() : [])))>
                                <label class="form-check-label" for="country-{{ $country->id }}">{{ $country->name }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
