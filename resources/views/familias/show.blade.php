@extends('layouts.app')

@section('title', 'Detalle de Familia')

@section('content')
    <div class="container py-4">
        <x-page-header title="Familia {{ $familia->codigo }}">
            <div class="btn-group" role="group">
                <a href="{{ route('familias.edit', $familia) }}" class="btn btn-secondary">
                    <i class="fas fa-edit me-1"></i> Editar
                </a>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#mapModal">
                    <i class="fas fa-map-marker-alt me-1"></i> Ver Mapa
                </button>
            </div>
        </x-page-header>

        <!-- Estadísticas Rápidas -->
        <x-familia-stats :familia="$familia" />

        <div class="row g-3">
            <!-- Información General -->
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-home me-2"></i>Información General
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <strong>Código:</strong>
                                    <span class="badge bg-secondary">{{ $familia->codigo }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <strong>Dirección:</strong>
                                    <span class="text-end">{{ $familia->direccion }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <strong>Barrio:</strong>
                                    <span class="text-end">{{ $familia->barrio?->name ?? 'No especificado' }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <strong>Teléfono:</strong>
                                    <span class="text-end">
                                        @if ($familia->telefono)
                                            <a href="tel:{{ $familia->telefono }}" class="text-decoration-none">
                                                <i class="fas fa-phone me-1"></i>{{ $familia->telefono }}
                                            </a>
                                        @else
                                            No especificado
                                        @endif
                                    </span>
                                </div>
                            </div>
                            @if ($familia->latitud && $familia->longitud)
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <strong>Coordenadas:</strong>
                                        <span class="text-end">
                                            <small>{{ number_format($familia->latitud, 6) }},
                                                {{ number_format($familia->longitud, 6) }}</small>
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jefe de Familia -->
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-crown me-2"></i>Jefe de Familia
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        @if ($familia->jefe)
                            <div class="mb-3">
                                <i class="fas fa-user-circle fa-4x text-primary mb-2"></i>
                                <h5>{{ $familia->jefe->primer_nombre }} {{ $familia->jefe->primer_apellido }}</h5>
                                <p class="text-muted mb-1">{{ $familia->jefe->numero_documento }}</p>
                                <p class="text-muted mb-2">{{ $familia->jefe->edad ?? 'N/A' }} años</p>
                            </div>
                            <div class="row g-2 text-start">
                                <div class="col-6">
                                    <small><strong>Documento:</strong></small><br>
                                    <span>{{ $familia->jefe->tipoDocumento?->name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-6">
                                    <small><strong>Sexo:</strong></small><br>
                                    <span>{{ $familia->jefe->sexo?->name ?? 'N/A' }}</span>
                                </div>
                                @if ($familia->jefe->telefono)
                                    <div class="col-12">
                                        <small><strong>Teléfono:</strong></small><br>
                                        <a href="tel:{{ $familia->jefe->telefono }}" class="text-decoration-none">
                                            <i class="fas fa-phone me-1"></i>{{ $familia->jefe->telefono }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-muted">
                                <i class="fas fa-user-slash fa-3x mb-3"></i>
                                <p>No hay jefe de familia asignado</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Integrantes -->
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users me-2"></i>Miembros de la Familia ({{ $familia->personas->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($familia->personas as $p)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user me-2 text-muted"></i>
                                                <div>
                                                    <h6 class="mb-0">{{ $p->primer_nombre }} {{ $p->primer_apellido }}
                                                    </h6>
                                                    <small class="text-muted">{{ $p->numero_documento }}</small>
                                                </div>
                                            </div>
                                            <div class="mt-1">
                                                <small class="text-muted">
                                                    {{ $p->edad ?? 'N/A' }} años •
                                                    {{ $p->sexo?->name ?? 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            @if ($p->pivot->es_jefe)
                                                <span class="badge bg-primary">Jefe</span>
                                            @endif
                                            @if ($p->edad && $p->edad < 18)
                                                <span class="badge bg-warning text-dark">Menor</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted">
                                    <i class="fas fa-users-slash fa-2x mb-2"></i>
                                    <p>Sin personas asociadas</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="row g-3 mt-3">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie me-2"></i>Distribución por Edad
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="edadChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Información Adicional
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-center p-2 border rounded">
                                    <h6 class="text-primary">{{ $familia->personas->where('edad', '>=', 18)->count() }}
                                    </h6>
                                    <small>Adultos</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 border rounded">
                                    <h6 class="text-success">{{ $familia->personas->where('edad', '>=', 60)->count() }}
                                    </h6>
                                    <small>Adultos Mayores</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 border rounded">
                                    <h6 class="text-warning">
                                        {{ $familia->personas->where('edad', '>=', 12)->where('edad', '<', 18)->count() }}
                                    </h6>
                                    <small>Adolescentes</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 border rounded">
                                    <h6 class="text-info">{{ $familia->personas->where('edad', '<', 12)->count() }}</h6>
                                    <small>Niños</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Encuestas Asignadas -->
        <div class="row g-3 mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>Encuestas Asignadas
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($familia->encuestas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Estado</th>
                                            <th>Fecha Creación</th>
                                            <th>Fecha Expiración</th>
                                            <th>Respuestas</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($familia->encuestas as $encuesta)
                                            <tr>
                                                <td>
                                                    <strong>{{ $encuesta->titulo }}</strong>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ Str::limit($encuesta->descripcion, 50) }}</small>
                                                </td>
                                                <td>
                                                    @if ($encuesta->fecha_expiracion && now()->gt($encuesta->fecha_expiracion))
                                                        <span class="badge bg-danger">Expirada</span>
                                                    @elseif($encuesta->fecha_expiracion && now()->diffInDays($encuesta->fecha_expiracion) <= 3)
                                                        <span class="badge bg-warning text-dark">Por Expirar</span>
                                                    @else
                                                        <span class="badge bg-success">Activa</span>
                                                    @endif
                                                </td>
                                                <td>{{ $encuesta->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    @if ($encuesta->fecha_expiracion)
                                                        {{ $encuesta->fecha_expiracion->format('d/m/Y') }}
                                                    @else
                                                        <span class="text-muted">Sin fecha</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-info">{{ $encuesta->respuestas->count() }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('encuestas.show', $encuesta) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                                <h6>No hay encuestas asignadas</h6>
                                <p class="mb-0">Esta familia no tiene encuestas asignadas actualmente.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal del Mapa -->
    <div class="modal fade" id="mapModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubicación de la Familia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($familia->latitud && $familia->longitud)
                        <div id="map" style="height: 400px;"></div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                            <p>No hay coordenadas disponibles para mostrar el mapa</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @if ($familia->latitud && $familia->longitud)
            <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
        @endif
        <script>
            // Gráfico de distribución por edad
            const ctx = document.getElementById('edadChart').getContext('2d');
            const edadData = {
                labels: ['Niños (0-11)', 'Adolescentes (12-17)', 'Adultos (18-59)', 'Adultos Mayores (60+)'],
                datasets: [{
                    data: [
                        {{ $familia->personas->where('edad', '<', 12)->count() }},
                        {{ $familia->personas->where('edad', '>=', 12)->where('edad', '<', 18)->count() }},
                        {{ $familia->personas->where('edad', '>=', 18)->where('edad', '<', 60)->count() }},
                        {{ $familia->personas->where('edad', '>=', 60)->count() }}
                    ],
                    backgroundColor: ['#17a2b8', '#ffc107', '#28a745', '#dc3545'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            };

            new Chart(ctx, {
                type: 'doughnut',
                data: edadData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            @if ($familia->latitud && $familia->longitud)
                // Mapa de Google
                function initMap() {
                    const location = {
                        lat: {{ $familia->latitud }},
                        lng: {{ $familia->longitud }}
                    };
                    const map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 15,
                        center: location,
                    });

                    new google.maps.Marker({
                        position: location,
                        map: map,
                        title: 'Familia {{ $familia->codigo }}'
                    });
                }

                // Inicializar mapa cuando se abra el modal
                document.getElementById('mapModal').addEventListener('shown.bs.modal', function() {
                    if (typeof google !== 'undefined') {
                        initMap();
                    }
                });
            @endif
        </script>
    @endpush
@endsection
