@props(['name', 'label', 'type' => 'text', 'placeholder' => '', 'required' => false, 'icon' => null])

<x-form-base :name="$name" :label="$label" :required="$required" :icon="$icon">
    <input type="{{ $type }}" class="form-control @error($name) is-invalid @enderror" id="{{ $name }}"
        name="{{ $name }}" value="{{ old($name, $attributes->get('value', '')) }}"
        placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} {{ $attributes->except('value') }}>
</x-form-base>
