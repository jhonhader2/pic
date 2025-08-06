@extends('layouts.app')

@section('title', 'Configurar Preguntas - ' . $encuesta->titulo)

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-dark">
                            <i class="fas fa-question-circle me-2 text-primary"></i>Configurar Preguntas
                        </h1>
                        <p class="text-muted mb-0">{{ $encuesta->titulo }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('encuestas.show', $encuesta) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-eye me-2"></i>Ver Encuesta
                        </a>
                        <a href="{{ route('encuestas.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if (session('success'))
            <x-alert type="success" title="¡Éxito!">
                {{ session('success') }}
            </x-alert>
        @endif

        @if (session('error'))
            <x-alert type="error" title="¡Error!">
                {{ session('error') }}
            </x-alert>
        @endif

        <!-- Formulario de Configuración de Preguntas -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cogs me-2"></i>Configurar Preguntas de la Encuesta
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('encuestas.preguntas.store', $encuesta) }}"
                            id="preguntasForm">
                            @csrf

                            <!-- Paso 1: Seleccionar Tipo de Pregunta -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="fw-semibold mb-3">
                                        <i class="fas fa-1 me-2 text-primary"></i>Paso 1: Seleccionar Tipo de Pregunta
                                    </h6>

                                    <div class="row g-3">
                                        @foreach ($tiposPregunta as $tipo => $info)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="card h-100 tipo-pregunta-card" data-tipo="{{ $tipo }}">
                                                    <div class="card-body text-center p-3">
                                                        <div class="mb-3">
                                                            <i class="{{ $info['icono'] }} fa-2x text-primary"></i>
                                                        </div>
                                                        <h6 class="card-title mb-2">{{ $info['nombre'] }}</h6>
                                                        <p class="card-text small text-muted mb-3">
                                                            {{ $info['descripcion'] }}</p>

                                                        <div class="form-check">
                                                            <input type="radio" id="tipo_{{ $tipo }}"
                                                                name="tipo_pregunta" value="{{ $tipo }}"
                                                                class="form-check-input tipo-pregunta-radio"
                                                                data-requiere-opciones="{{ $info['requiere_opciones'] ? 'true' : 'false' }}"
                                                                data-requiere-parametros="{{ $info['requiere_parametros'] ? 'true' : 'false' }}">
                                                            <label for="tipo_{{ $tipo }}"
                                                                class="form-check-label fw-semibold">
                                                                Seleccionar este tipo
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Paso 2: Seleccionar Temas (se muestra solo para tipos que requieren opciones) -->
                            <div class="row mb-4" id="seccion-temas" style="display: none;">
                                <div class="col-12">
                                    <h6 class="fw-semibold mb-3">
                                        <i class="fas fa-2 me-2 text-primary"></i>Paso 2: Seleccionar Temas con Opciones
                                    </h6>

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Tipos de pregunta seleccionados:</strong>
                                        <span id="tipos-seleccionados"></span>
                                    </div>

                                    <div class="temas-container">
                                        @if ($temas->count() > 0)
                                            @foreach ($temas as $tema)
                                                <div class="tema-item mb-3 p-3 border rounded"
                                                    data-tema-id="{{ $tema->id }}">
                                                    <div class="form-check">
                                                        <input type="checkbox" id="tema_{{ $tema->id }}" name="temas[]"
                                                            value="{{ $tema->id }}"
                                                            class="form-check-input tema-checkbox">
                                                        <label for="tema_{{ $tema->id }}"
                                                            class="form-check-label fw-semibold">
                                                            <i class="fas fa-tag me-1"></i>{{ $tema->name }}
                                                        </label>
                                                    </div>

                                                    <!-- Configuración adicional del tema -->
                                                    <div class="configuracion-tema mt-3" style="display: none;">
                                                        <hr class="my-2">

                                                        <!-- Descripción de la Pregunta -->
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">
                                                                Descripción (Opcional)
                                                            </label>
                                                            <textarea name="descripciones_pregunta[{{ $tema->id }}]" class="form-control form-control-sm" rows="2"
                                                                placeholder="Descripción adicional de la pregunta..."></textarea>
                                                        </div>

                                                        <!-- Requerida -->
                                                        <div class="mb-3">
                                                            <div class="form-check">
                                                                <input type="checkbox" id="requerida_{{ $tema->id }}"
                                                                    name="requeridas[{{ $tema->id }}]" value="1"
                                                                    class="form-check-input" checked>
                                                                <label for="requerida_{{ $tema->id }}"
                                                                    class="form-check-label small">
                                                                    <i class="fas fa-asterisk me-1"></i>Pregunta requerida
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Opciones del tema -->
                                                        <x-tema-opciones :tema="$tema" :parametros="$tema->parametros" />
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-muted small">
                                                <i class="fas fa-exclamation-triangle me-1"></i>No hay temas disponibles
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Paso 3: Configuración para tipos sin opciones -->
                            <div class="row mb-4" id="seccion-configuracion" style="display: none;">
                                <div class="col-12">
                                    <h6 class="fw-semibold mb-3">
                                        <i class="fas fa-3 me-2 text-primary"></i>Paso 3: Configuración de Preguntas
                                    </h6>

                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Tipos seleccionados:</strong>
                                        <span id="tipos-sin-opciones"></span>
                                        <br>
                                        <small>Estos tipos de pregunta no requieren temas predefinidos. Se crearán preguntas
                                            genéricas.</small>
                                    </div>

                                    <div id="preguntas-genericas">
                                        <!-- Se llenará dinámicamente -->
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('encuestas.show', $encuesta) }}"
                                            class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times me-2"></i>Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-sm" id="btn-guardar" disabled>
                                            <i class="fas fa-save me-2"></i>Guardar Preguntas
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
        // Pasar datos de tipos de pregunta al JavaScript
        window.tiposPregunta = @json($tiposPregunta);
    </script>
@endpush
