@props(['type' => 'info', 'dismissible' => true, 'title' => null])

@php
    $alertClasses = [
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ];

    $icons = [
        'success' => 'fas fa-check-circle',
        'error' => 'fas fa-exclamation-circle',
        'warning' => 'fas fa-exclamation-triangle',
        'info' => 'fas fa-info-circle',
    ];

    $alertClass = $alertClasses[$type] ?? 'alert-info';
    $icon = $icons[$type] ?? 'fas fa-info-circle';
@endphp

<div class="alert {{ $alertClass }} {{ $dismissible ? 'alert-dismissible fade show' : '' }} mb-4" role="alert">
    <div class="d-flex align-items-start">
        <i class="{{ $icon }} me-2 mt-1"></i>
        <div class="flex-grow-1">
            @if($title)
                <strong>{{ $title }}</strong><br>
            @endif
            {{ $slot }}
        </div>
    </div>

    @if ($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
