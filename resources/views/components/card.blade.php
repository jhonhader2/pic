@props(['title' => '', 'subtitle' => '', 'icon' => null, 'color' => 'primary'])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-4">
        <div class="d-flex align-items-center">
            @if ($icon)
                <div class="bg-{{ $color }} bg-opacity-10 p-3 rounded me-3">
                    <i class="fas fa-{{ $icon }} text-{{ $color }}"></i>
                </div>
            @endif
            <div>
                @if ($title)
                    <h3 class="fw-bold mb-1">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="text-muted mb-0">{{ $subtitle }}</p>
                @endif
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
