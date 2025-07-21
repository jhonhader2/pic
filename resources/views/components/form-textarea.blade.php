@props(['name', 'label', 'placeholder' => '', 'rows' => 3, 'required' => false, 'icon' => null])

<div class="mb-3">
    @if ($label)
        <label for="{{ $name }}" class="form-label fw-semibold">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="input-group">
        @if ($icon)
            <span class="input-group-text">
                <i class="{{ $icon }}"></i>
            </span>
        @endif

        <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}"
            class="form-control @error($name) is-invalid @enderror" placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }} {{ $attributes }}>{{ old($name) }}</textarea>
    </div>

    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
