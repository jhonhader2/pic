@extends('layouts.app')

@section('title', 'Configurar Preguntas - ' . $encuesta->titulo)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0">
                            <i class="fas fa-cog me-2 text-primary"></i>Configurar Preguntas
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

                <!-- Formulario -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list-ul me-2"></i>Preguntas de la Encuesta
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="preguntasForm" action="{{ route('encuestas.preguntas.store', $encuesta) }}"
                            method="POST">
                            @csrf

                            <!-- Área de Preguntas -->
                            <div id="preguntas-container">
                                <!-- Las preguntas se agregarán aquí dinámicamente -->
                            </div>

                            <!-- Botón para agregar nueva pregunta -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-pregunta">
                                        <i class="fas fa-plus me-2"></i>Agregar Pregunta
                                    </button>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('encuestas.show', $encuesta) }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times me-2"></i>Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-sm" id="btn-guardar">
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

    <!-- Modal para configurar opciones -->
    <div class="modal fade" id="modalOpciones" tabindex="-1" aria-labelledby="modalOpcionesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalOpcionesLabel">
                        <i class="fas fa-list-ul me-2"></i>Configurar Opciones
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Opciones de respuesta:</label>
                        <div id="opciones-container">
                            <!-- Las opciones se agregarán aquí -->
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="btn-agregar-opcion">
                            <i class="fas fa-plus me-1"></i>Agregar Opción
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-sm" id="btn-guardar-opciones">Guardar
                        Opciones</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    $preguntasExistentes = $encuesta->temas->map(function ($tema) {
        return [
            'titulo' => $tema->name,
            'tipo' => $tema->pivot->tipo_pregunta,
            'descripcion' => $tema->pivot->descripcion_pregunta,
            'requerida' => $tema->pivot->requerida,
            'opciones' => $tema->parametros->pluck('name')->toArray(),
        ];
    });
@endphp

@push('scripts')
    <script>
        // Datos necesarios para el JavaScript
        window.tiposPregunta = @json($tiposPregunta);
        window.temas = @json($temas);
        window.preguntasExistentes = @json($preguntasExistentes);
    </script>
@endpush
