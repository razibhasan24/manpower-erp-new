@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="delegation_id">{{ __('Delegation Record') }} <span class="text-danger">*</span></label>
        <select class="form-select @error('delegation_id') is-invalid @enderror" name="delegation_id" id="delegation_id" required>
            <option value="">{{ __('Select delegation') }}</option>
            @foreach ($delegations as $delegation)
                <option value="{{ $delegation->id }}" @selected(old('delegation_id', $document->delegation_id ?? request('delegation_id')) == $delegation->id)>
                    {{ $delegation->recruitment?->tracking_number ?? ('#'.$delegation->id) }} — {{ $delegation->recruitment?->passportHolder?->full_name }}
                </option>
            @endforeach
        </select>
        @error('delegation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="type">{{ __('Document Type') }} <span class="text-danger">*</span></label>
        <select class="form-select @error('type') is-invalid @enderror" name="type" id="type" required>
            <option value="document" @selected(old('type', $document->type ?? '') === 'document')>{{ __('Delegation Document') }}</option>
            <option value="e_duty" @selected(old('type', $document->type ?? '') === 'e_duty')>{{ __('E-Duty Document') }}</option>
        </select>
        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label" for="file">{{ __('File') }} @empty($document)<span class="text-danger">*</span>@endempty</label>
        <input class="form-control @error('file') is-invalid @enderror" type="file" name="file" id="file" accept=".pdf,.jpg,.jpeg,.png" @empty($document) required @endempty>
        @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @isset($document)<div class="form-text">{{ __('Current file: :name', ['name' => $document->original_name ?? basename($document->file_path)]) }}</div>@endisset
    </div>
</div>
<div class="d-flex gap-2 mt-4">
    <x-button type="submit" variant="success">{{ __('Save') }}</x-button>
    <x-button href="{{ isset($document) ? route('delegation-documents.show', $document) : route('delegation-documents.index') }}" variant="secondary">{{ __('Cancel') }}</x-button>
</div>
