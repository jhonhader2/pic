@props([
    'name',
    'label',
    'required' => false,
    'icon' => null,
    'errorClass' => 'is-invalid',
    'labelClass' => 'form-label fw-semibold',
    'containerClass' => 'mb-3',
])

<div class="{{ $containerClass }}">
    @if ($label)
        <label for="{{ $name }}" class="{{ $labelClass }}">
            @if ($icon)
                <i class="fas fa-{{ $icon }} me-1"></i>
            @endif
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="input-group">
        @if ($icon && !$label)
            <span class="input-group-text">
                <i class="fas fa-{{ $icon }}"></i>
            </span>
        @endif

        {{ $slot }}

        @error($name)
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
