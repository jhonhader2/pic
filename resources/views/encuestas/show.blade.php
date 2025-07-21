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
                            <a href="{{ route('encuestas.responder', $encuesta) }}" class="btn btn-success">
                                <i class="fas fa-edit me-2"></i>Responder Encuesta
                            </a>
                        @endif
                        <a href="{{ route('encuestas.index') }}" class="btn btn-secondary">
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
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users me-2"></i>Personas Asignadas
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($encuesta->personas->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($encuesta->personas as $persona)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle me-2 text-primary"></i>
                                            <div>
                                                <strong>{{ $persona->primer_nombre }}
                                                    {{ $persona->primer_apellido }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $persona->numero_documento }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                No hay personas asignadas específicamente a esta encuesta.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Preguntas de la Encuesta -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-question-circle me-2"></i>Preguntas de la Encuesta
                        </h5>
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
                                <p class="text-muted">Esta encuesta no tiene preguntas asignadas.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
