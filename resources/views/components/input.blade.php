{{--
    x-input
    Props:
      - name: field name (required)
      - label: field label (required)
      - type: input type (default 'text')
      - value: current value (default old(name))
      - required: bool (default false)
      - placeholder: string
      - hint: small help text shown under the field
    Shows the first validation error for the field automatically.
--}}
@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'placeholder' => null, 'hint' => null])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}@if($required) <span class="text-danger">*</span>@endif</label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
           value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}"
           @if($required) required @endif
           {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if($hint)
        <div class="form-text">{{ $hint }}</div>
    @endif
</div>
