@props([
    'name',
    'label',
    'options' => [],
    'required' => false,
    'icon' => null,
    'selected' => null,
    'useSelect2' => true,
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-semibold">{{ $label }}</label>
    <div class="input-group">
        @if ($icon)
            <span class="input-group-text">
                <i class="fas fa-{{ $icon }}"></i>
            </span>
        @endif
        <select class="form-control {{ $useSelect2 ? 'select2' : '' }} @error($name) is-invalid @enderror"
            id="{{ $name }}" name="{{ $name }}" {{ $required ? 'required' : '' }} {{ $attributes }}>
            <option value="">Seleccione...</option>
            @foreach ($options as $value => $optionLabel)
                @php
                    $currentValue = old($name, $selected);
                    $isSelected = (string) $currentValue === (string) $value;
                @endphp
                <option value="{{ $value }}" {{ $isSelected ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    </div>
    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
