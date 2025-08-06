@extends('layouts.app')

@section('title', 'Detalles de Encuesta')

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
                    <div class="d-flex gap-2">
                        @if ($encuesta->estaDisponible())
                            <a href="{{ route('encuestas.responder', $encuesta) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-edit me-2"></i>Responder Encuesta
                            </a>
                        @endif
                        @if ($encuesta->total_respuestas > 0)
                            <a href="{{ route('encuestas.resultados', $encuesta) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-chart-bar me-2"></i>Ver Resultados
                            </a>
                        @endif
                        <a href="{{ route('encuestas.preguntas.create', $encuesta) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-cogs me-2"></i>Configurar Preguntas
                        </a>
                        <a href="{{ route('encuestas.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información General -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Información General
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Fecha de Inicio:</strong> {{ $encuesta->fecha_inicio->format('d/m/Y') }}</p>
                                <p><strong>Fecha de Fin:</strong> {{ $encuesta->fecha_fin->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Estado:</strong>
                                    @if ($encuesta->activa)
                                        <span class="badge bg-success">Activa</span>
                                    @else
                                        <span class="badge bg-secondary">Inactiva</span>
                                    @endif
                                </p>
                                <p><strong>Disponibilidad:</strong>
                                    @if ($encuesta->estaDisponible())
                                        <span class="badge bg-success">Disponible</span>
                                    @elseif($encuesta->noHaIniciado())
                                        <span class="badge bg-warning">Pendiente</span>
                                    @else
                                        <span class="badge bg-danger">Expirada</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <p><strong>Creada por:</strong> {{ $encuesta->creador->name }}</p>
                        <p><strong>Total de respuestas:</strong> {{ $encuesta->total_respuestas }}</p>
                        <p><strong>Aplicadores asignados:</strong> {{ $encuesta->total_personas_asignadas }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-tie me-2"></i>Aplicadores Asignados
                            <span class="badge bg-primary ms-2">{{ $encuesta->personas->count() }}</span>
                        </h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modalPersonas">
                            <i class="fas fa-edit me-1"></i>Gestionar
                        </button>
                    </div>
                    <div class="card-body">
                        @if ($encuesta->personas->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($encuesta->personas as $persona)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle me-2 text-primary"></i>
                                                <div>
                                                    <strong>{{ $persona->primer_nombre }}
                                                        {{ $persona->primer_apellido }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $persona->numero_documento }}</small>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="quitarPersona('{{ $persona->id }}', '{{ $persona->primer_nombre }} {{ $persona->primer_apellido }}')"
                                                title="Quitar aplicador">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-users text-muted mb-2" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-2">No hay aplicadores asignados</p>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalPersonas">
                                    <i class="fas fa-plus me-1"></i>Asignar Aplicadores
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Preguntas de la Encuesta -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-question-circle me-2"></i>Preguntas de la Encuesta
                            @if ($encuesta->temas->count() > 0)
                                <span class="badge bg-success ms-2">{{ $encuesta->temas->count() }} configuradas</span>
                            @else
                                <span class="badge bg-warning ms-2">Sin configurar</span>
                            @endif
                        </h5>
                        <a href="{{ route('encuestas.preguntas.create', $encuesta) }}"
                            class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-2"></i>Editar Preguntas
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($encuesta->temas->count() > 0)
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
                                            <div class="d-flex gap-2">
                                                @if ($tema->pivot->requerida)
                                                    <span class="badge bg-danger">Requerida</span>
                                                @endif
                                                <span class="badge bg-info">
                                                    <i
                                                        class="{{ \App\Helpers\TipoPreguntaHelper::getIcono($tema->pivot->tipo_pregunta) }} me-1"></i>
                                                    {{ \App\Helpers\TipoPreguntaHelper::getNombre($tema->pivot->tipo_pregunta) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mostrar opciones si las tiene -->
                                    @if ($tema->parametros->count() > 0)
                                        <div class="opciones-pregunta">
                                            <h6 class="mb-2">
                                                <i class="fas fa-list-ul me-1"></i>Opciones:
                                            </h6>
                                            <div class="row">
                                                @foreach ($tema->parametros as $parametro)
                                                    <div class="col-md-6 col-lg-4 mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-circle text-primary me-2"
                                                                style="font-size: 0.5rem;"></i>
                                                            <span class="small">{{ $parametro->name }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                                <h5>No hay preguntas configuradas</h5>
                                <p class="text-muted mb-3">Esta encuesta no tiene preguntas asignadas.</p>
                                <a href="{{ route('encuestas.preguntas.create', $encuesta) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Configurar Preguntas
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para gestionar personas asignadas -->
    <div class="modal fade" id="modalPersonas" tabindex="-1" aria-labelledby="modalPersonasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPersonasLabel">
                        <i class="fas fa-user-tie me-2"></i>Gestionar Aplicadores Asignados
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formPersonas" method="POST" action="{{ route('encuestas.personas.update', $encuesta) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Seleccionar aplicadores para asignar a la
                                encuesta:</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="buscarPersonas"
                                    placeholder="Buscar aplicadores...">
                            </div>
                        </div>

                        <div class="personas-container" style="max-height: 400px; overflow-y: auto;">
                            @foreach ($todasLasPersonas as $persona)
                                <div class="persona-item mb-2 p-2 border rounded" data-persona-id="{{ $persona->id }}">
                                    <div class="form-check">
                                        <input type="checkbox" id="persona_{{ $persona->id }}" name="personas[]"
                                            value="{{ $persona->id }}" class="form-check-input persona-checkbox"
                                            {{ $encuesta->personas->contains($persona->id) ? 'checked' : '' }}>
                                        <label for="persona_{{ $persona->id }}" class="form-check-label">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle me-2 text-primary"></i>
                                                <div>
                                                    <strong>{{ $persona->primer_nombre }}
                                                        {{ $persona->primer_apellido }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $persona->numero_documento }}</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <span id="personasSeleccionadas">0</span> aplicadores seleccionados
                                </small>
                                <div>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        id="btnSeleccionarTodos">
                                        <i class="fas fa-check-double me-1"></i>Seleccionar Todos
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        id="btnDeseleccionarTodos">
                                        <i class="fas fa-times me-1"></i>Deseleccionar Todos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Variables específicas de esta página
        window.routes = {
            encuestasPersonasDetach: '{{ route('encuestas.personas.detach', $encuesta) }}'
        };
    </script>
@endpush
