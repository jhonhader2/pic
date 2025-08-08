@props(['name', 'label', 'type' => 'text', 'placeholder' => '', 'required' => false, 'icon' => null])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-semibold">{{ $label }}</label>
    <div class="input-group">
        @if ($icon)
            <span class="input-group-text">
                <i class="fas fa-{{ $icon }}"></i>
            </span>
        @endif
        <input type="{{ $type }}" class="form-control @error($name) is-invalid @enderror" id="{{ $name }}"
            name="{{ $name }}" value="{{ old($name, $attributes->get('value', '')) }}" placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }} {{ $attributes->except('value') }}>
    </div>
    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
