@extends('layouts.app')

@section('title', 'Encuestas')

@section('content')
    <div class="container-fluid py-4">
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

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-dark">
                            <i class="fas fa-clipboard-list me-2 text-primary"></i>Encuestas
                        </h1>
                        <p class="text-muted mb-0">Gestiona las encuestas del sistema</p>
                    </div>
                    <a href="{{ route('encuestas.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Nueva Encuesta
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="row mb-4">
            <div class="col-12">
                <x-card title="Filtros de Búsqueda" subtitle="Personaliza la búsqueda de encuestas" icon="search">
                    <form method="GET" action="{{ route('encuestas.index') }}" class="row g-3">
                        <!-- Búsqueda -->
                        <div class="col-md-3">
                            <x-form-input name="buscar" label="Buscar" placeholder="Buscar por título..." icon="search"
                                value="{{ request('buscar') }}" />
                        </div>

                        <!-- Filtro por Estado -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="estado" class="form-label fw-semibold">Estado</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-filter"></i>
                                    </span>
                                    <select id="estado" name="estado" class="form-control">
                                        <option value="">Todos los estados</option>
                                        <option value="activas" {{ request('estado') == 'activas' ? 'selected' : '' }}>
                                            Activas</option>
                                        <option value="disponibles"
                                            {{ request('estado') == 'disponibles' ? 'selected' : '' }}>Disponibles</option>
                                        <option value="expiradas" {{ request('estado') == 'expiradas' ? 'selected' : '' }}>
                                            Expiradas</option>
                                        <option value="pendientes"
                                            {{ request('estado') == 'pendientes' ? 'selected' : '' }}>Pendientes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Ordenamiento -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="orden" class="form-label fw-semibold">Ordenar por</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-sort"></i>
                                    </span>
                                    <select id="orden" name="orden" class="form-control">
                                        <option value="created_at"
                                            {{ request('orden', 'created_at') == 'created_at' ? 'selected' : '' }}>Fecha
                                            de creación</option>
                                        <option value="fecha_inicio"
                                            {{ request('orden', 'created_at') == 'fecha_inicio' ? 'selected' : '' }}>Fecha
                                            de inicio</option>
                                        <option value="fecha_fin"
                                            {{ request('orden', 'created_at') == 'fecha_fin' ? 'selected' : '' }}>Fecha
                                            de fin</option>
                                        <option value="titulo"
                                            {{ request('orden', 'created_at') == 'titulo' ? 'selected' : '' }}>Título
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="direccion" class="form-label fw-semibold">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-sort-amount-down"></i>
                                    </span>
                                    <select id="direccion" name="direccion" class="form-control">
                                        <option value="desc"
                                            {{ request('direccion', 'desc') == 'desc' ? 'selected' : '' }}>Descendente
                                        </option>
                                        <option value="asc"
                                            {{ request('direccion', 'desc') == 'asc' ? 'selected' : '' }}>Ascendente
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Filtrar
                                </button>
                                <a href="{{ route('encuestas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </x-card>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        @if ($encuestas->count() > 0)
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 bg-primary bg-gradient text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clipboard-list fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0">{{ $encuestas->total() }}</h4>
                                    <p class="mb-0 small">Total Encuestas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-success bg-gradient text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-play-circle fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0">{{ $encuestas->where('estado_texto', 'ACTIVA')->count() }}</h4>
                                    <p class="mb-0 small">Activas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-warning bg-gradient text-dark">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0">{{ $encuestas->where('estado_texto', 'PENDIENTE')->count() }}</h4>
                                    <p class="mb-0 small">Pendientes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-secondary bg-gradient text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-stop-circle fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0">{{ $encuestas->where('estado_texto', 'EXPIRADA')->count() }}</h4>
                                    <p class="mb-0 small">Expiradas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Grid de Encuestas -->
        <div class="row">
            <div class="col-12">
                @if ($encuestas->count() > 0)
                    <div class="row g-4">
                        @foreach ($encuestas as $encuesta)
                            <div class="col-lg-6 col-xl-4">
                                <div class="card h-100 shadow-sm border-0 hover-shadow">
                                    <!-- Header de la Card -->
                                    <div class="card-header bg-transparent border-0 pb-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1" style="min-width: 0;">
                                                <h5 class="card-title mb-1"
                                                    style="word-wrap: break-word; overflow-wrap: break-word; line-height: 1.3;">
                                                    <i class="fas fa-clipboard me-2 text-primary"></i>
                                                    {{ $encuesta->titulo }}
                                                </h5>
                                                @if ($encuesta->descripcion)
                                                    <p class="text-muted small mb-0"
                                                        style="word-wrap: break-word; overflow-wrap: break-word; line-height: 1.4;">
                                                        {{ $encuesta->descripcion }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="dropdown" style="position: relative;">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1060;">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('encuestas.show', $encuesta) }}">
                                                            <i class="fas fa-eye me-2"></i>Ver detalles
                                                        </a></li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('encuestas.edit', $encuesta) }}">
                                                            <i class="fas fa-edit me-2"></i>Editar
                                                        </a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('encuestas.destroy', $encuesta) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('¿Estás seguro de que quieres eliminar esta encuesta?')">
                                                                <i class="fas fa-trash me-2"></i>Eliminar
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Body de la Card -->
                                    <div class="card-body pt-2">
                                        <!-- Estado -->
                                        <div class="mb-3">
                                            @php
                                                $estadoClass = match ($encuesta->estado_texto) {
                                                    'ACTIVA' => 'badge bg-success',
                                                    'INACTIVA' => 'badge bg-danger',
                                                    'PENDIENTE' => 'badge bg-warning text-dark',
                                                    'EXPIRADA' => 'badge bg-secondary',
                                                    default => 'badge bg-secondary',
                                                };
                                            @endphp
                                            <span class="{{ $estadoClass }} fs-6">
                                                <i class="fas fa-circle me-1"></i>{{ $encuesta->estado_texto }}
                                            </span>
                                            @if ($encuesta->estaDisponible())
                                                <div class="small text-success mt-1">
                                                    <i class="fas fa-clock me-1"></i>{{ $encuesta->dias_restantes }} días
                                                    restantes
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Fechas -->
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="small text-muted">Inicio</div>
                                                    <div class="fw-bold">{{ $encuesta->fecha_inicio->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="small text-muted">Fin</div>
                                                    <div class="fw-bold">{{ $encuesta->fecha_fin->format('d/m/Y') }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Progreso -->
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="small fw-semibold">Progreso</span>
                                                <span
                                                    class="small text-muted">{{ $encuesta->porcentaje_completado }}%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-primary"
                                                    style="width: {{ $encuesta->porcentaje_completado }}%"></div>
                                            </div>
                                            <div class="small text-muted mt-1">
                                                <i class="fas fa-users me-1"></i>{{ $encuesta->total_respuestas }} /
                                                {{ $encuesta->total_personas_asignadas }} respuestas
                                            </div>
                                        </div>

                                        <!-- Creador -->
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <div class="small fw-semibold">{{ $encuesta->creador->name }}</div>
                                                <div class="small text-muted">Creado
                                                    {{ $encuesta->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer de la Card -->
                                    <div class="card-footer bg-transparent border-0 pt-0">
                                        <div class="d-grid">
                                            <a href="{{ route('encuestas.show', $encuesta) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-2"></i>Ver detalles
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                {{ $encuestas->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-clipboard-list fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">No hay encuestas</h4>
                        <p class="text-muted mb-4">No se encontraron encuestas con los filtros aplicados.</p>
                        <a href="{{ route('encuestas.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Crear primera encuesta
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .hover-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            transition: all 0.3s ease;
        }

        .card {
            transition: all 0.3s ease;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .card-header {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .card-title {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .progress-bar {
            transition: width 0.6s ease;
        }

        .badge {
            font-size: 0.75rem;
        }

        /* Asegurar que el contenido no se desborde */
        .card-body {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Mejorar la legibilidad del texto */
        .card-title {
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.3;
        }

        .card-body p {
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.4;
        }

        /* Asegurar que los elementos flex no se desborden */
        .d-flex {
            min-width: 0;
        }

        .flex-grow-1 {
            min-width: 0;
        }

        /* Solucionar problema del dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            z-index: 1050 !important;
            position: absolute !important;
            margin-top: 0.125rem;
        }

        .card {
            position: relative;
            z-index: 1;
        }

        .card:hover {
            z-index: 2;
        }

        /* Asegurar que el dropdown se muestre por encima de todo */
        .dropdown.show .dropdown-menu {
            z-index: 1060 !important;
        }

        /* Mejorar el posicionamiento del dropdown */
        .dropdown-menu-end {
            right: 0;
            left: auto;
        }

        /* Asegurar que las cards no interfieran con el dropdown */
        .col-lg-6,
        .col-xl-4 {
            position: relative;
        }

        /* Mejorar la visibilidad del dropdown */
        .dropdown-menu {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
    </style>
@endsection
