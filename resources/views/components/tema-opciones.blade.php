@props(['tema', 'parametros'])

<div class="opciones-tema mt-3" style="display: none;">
    <div class="alert alert-light border">
        <h6 class="alert-heading mb-2">
            <i class="fas fa-list-ul me-1"></i>Opciones Disponibles
        </h6>

        @if ($parametros->count() > 0)
            <div class="row">
                @foreach ($parametros as $parametro)
                    <div class="col-md-6 mb-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-circle text-primary me-2" style="font-size: 0.5rem;"></i>
                            <span class="small">{{ $parametro->name }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Estas opciones están predefinidas para este tema.
            </small>
        @else
            <p class="text-muted small mb-0">
                <i class="fas fa-exclamation-triangle me-1"></i>
                No hay opciones predefinidas para este tema.
            </p>
        @endif
    </div>
</div>
