@props(['name', 'label', 'value' => '1', 'checked' => false, 'required' => false, 'icon' => null])

<div class="form-check">
    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}"
        {{ $checked ? 'checked' : '' }} {{ $required ? 'required' : '' }}
        class="form-check-input @error($name) is-invalid @enderror" {{ $attributes }}>

    <label for="{{ $name }}" class="form-check-label fw-semibold">
        @if ($icon)
            <i class="{{ $icon }} me-2"></i>
        @endif
        {{ $label }}
    </label>

    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
