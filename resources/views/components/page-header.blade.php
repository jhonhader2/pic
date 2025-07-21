@props(['title', 'subtitle' => '', 'actions' => null])

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 text-dark">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="text-muted mb-0">{{ $subtitle }}</p>
                @endif
            </div>
            @if ($actions)
                <div class="d-flex gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>
</div>
