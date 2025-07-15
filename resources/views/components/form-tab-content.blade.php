@props(['id', 'active' => false])

<div class="tab-pane fade {{ $active ? 'show active' : '' }}" id="{{ $id }}" role="tabpanel"
    aria-labelledby="{{ $id }}-tab">
    <div class="row g-3">
        {{ $slot }}
    </div>
</div>
