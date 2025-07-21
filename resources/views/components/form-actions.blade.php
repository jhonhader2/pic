@props(['cancelRoute', 'cancelText' => 'Cancelar', 'submitText' => 'Guardar', 'submitIcon' => 'save'])

<div class="row mt-4">
    <div class="col-12">
        <hr>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ $cancelRoute }}" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>{{ $cancelText }}
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-{{ $submitIcon }} me-2"></i>{{ $submitText }}
            </button>
        </div>
    </div>
</div>
