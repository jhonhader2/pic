@extends('layouts.app')

@section('title', 'Responder Encuesta')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-dark">{{ $encuesta->titulo }}</h1>
                        <p class="text-muted mb-0">{{ $encuesta->descripcion }}</p>
                    </div>
                    <a href="{{ route('encuestas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Formulario de Respuesta -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST" action="{{ route('encuestas.respuesta.store', $encuesta) }}" id="respuestaForm"
                            enctype="multipart/form-data">
                            @csrf

                            @foreach ($encuesta->temas as $index => $tema)
                                <div class="pregunta-item mb-4 p-4 border rounded">
                                    <div class="d-flex align-items-start mb-3">
                                        <span class="badge bg-primary me-3">{{ $index + 1 }}</span>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1">{{ $tema->name }}</h5>
                                            @if ($tema->pivot->descripcion_pregunta)
                                                <p class="text-muted small mb-2">{{ $tema->pivot->descripcion_pregunta }}
                                                </p>
                                            @endif
                                            @if ($tema->pivot->requerida)
                                                <span class="badge bg-danger">Requerida</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Renderizar según el tipo de pregunta -->
                                    @switch($tema->pivot->tipo_pregunta)
                                        @case('seleccion_unica')
                                            <div class="opciones-respuesta">
                                                @foreach ($tema->parametros as $parametro)
                                                    <div class="form-check mb-2">
                                                        <input type="radio"
                                                            id="respuesta_{{ $tema->id }}_{{ $parametro->id }}"
                                                            name="respuestas[{{ $tema->id }}]" value="{{ $parametro->id }}"
                                                            class="form-check-input"
                                                            {{ $tema->pivot->requerida ? 'required' : '' }}>
                                                        <label for="respuesta_{{ $tema->id }}_{{ $parametro->id }}"
                                                            class="form-check-label">
                                                            {{ $parametro->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @break

                                        @case('seleccion_multiple')
                                            <div class="opciones-respuesta">
                                                @foreach ($tema->parametros as $parametro)
                                                    <div class="form-check mb-2">
                                                        <input type="checkbox"
                                                            id="respuesta_{{ $tema->id }}_{{ $parametro->id }}"
                                                            name="respuestas[{{ $tema->id }}][]" value="{{ $parametro->id }}"
                                                            class="form-check-input"
                                                            {{ $tema->pivot->requerida ? 'required' : '' }}>
                                                        <label for="respuesta_{{ $tema->id }}_{{ $parametro->id }}"
                                                            class="form-check-label">
                                                            {{ $parametro->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @break

                                        @case('texto_corto')
                                            <div class="form-group">
                                                <input type="text" name="respuestas[{{ $tema->id }}]" class="form-control"
                                                    placeholder="Escriba su respuesta..." maxlength="255"
                                                    {{ $tema->pivot->requerida ? 'required' : '' }}>
                                            </div>
                                        @break

                                        @case('texto_largo')
                                            <div class="form-group">
                                                <textarea name="respuestas[{{ $tema->id }}]" class="form-control" rows="4"
                                                    placeholder="Escriba su respuesta..." maxlength="1000" {{ $tema->pivot->requerida ? 'required' : '' }}></textarea>
                                            </div>
                                        @break

                                        @case('numero')
                                            <div class="form-group">
                                                <input type="number" name="respuestas[{{ $tema->id }}]" class="form-control"
                                                    placeholder="Ingrese un número..."
                                                    {{ $tema->pivot->requerida ? 'required' : '' }}>
                                            </div>
                                        @break

                                        @case('fecha')
                                            <div class="form-group">
                                                <input type="date" name="respuestas[{{ $tema->id }}]" class="form-control"
                                                    {{ $tema->pivot->requerida ? 'required' : '' }}>
                                            </div>
                                        @break

                                        @case('escala')
                                            <div class="form-group">
                                                <label class="form-label">Seleccione una opción:</label>
                                                <div class="d-flex justify-content-between">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <div class="form-check">
                                                            <input type="radio"
                                                                id="escala_{{ $tema->id }}_{{ $i }}"
                                                                name="respuestas[{{ $tema->id }}]" value="{{ $i }}"
                                                                class="form-check-input"
                                                                {{ $tema->pivot->requerida ? 'required' : '' }}>
                                                            <label for="escala_{{ $tema->id }}_{{ $i }}"
                                                                class="form-check-label text-center d-block">
                                                                <i class="fas fa-star text-warning"></i><br>
                                                                <small>{{ $i }}</small>
                                                            </label>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        @break

                                        @case('archivo')
                                            <div class="form-group">
                                                <input type="file" name="respuestas[{{ $tema->id }}]" class="form-control"
                                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                    {{ $tema->pivot->requerida ? 'required' : '' }}>
                                                <small class="text-muted">
                                                    Formatos permitidos: PDF, DOC, DOCX, JPG, PNG (máx. 5MB)
                                                </small>
                                            </div>
                                        @break

                                        @default
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Tipo de pregunta no soportado: {{ $tema->pivot->tipo_pregunta }}
                                            </div>
                                    @endswitch
                                </div>
                            @endforeach

                            <!-- Botones -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('encuestas.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane me-2"></i>Enviar Respuesta
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('respuestaForm');

            // Validación del formulario
            form.addEventListener('submit', function(e) {
                const preguntasRequeridas = document.querySelectorAll('.pregunta-item .badge.bg-danger');
                let errores = 0;

                preguntasRequeridas.forEach(function(badge) {
                    const preguntaItem = badge.closest('.pregunta-item');
                    const inputs = preguntaItem.querySelectorAll(
                        'input[required], textarea[required]');
                    let preguntaRespondida = false;

                    inputs.forEach(function(input) {
                        if (input.type === 'radio' || input.type === 'checkbox') {
                            if (input.checked) preguntaRespondida = true;
                        } else {
                            if (input.value.trim() !== '') preguntaRespondida = true;
                        }
                    });

                    if (!preguntaRespondida) {
                        preguntaItem.classList.add('border-danger');
                        errores++;
                    } else {
                        preguntaItem.classList.remove('border-danger');
                    }
                });

                if (errores > 0) {
                    e.preventDefault();
                    alert('Por favor complete todas las preguntas requeridas.');
                    return false;
                }
            });
        });
    </script>
@endpush
