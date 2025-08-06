@extends('layouts.app')

@section('title', 'Resultados - ' . $encuesta->titulo)

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-dark">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>Resultados de la Encuesta
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

        <!-- Estadísticas Generales -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 bg-primary bg-gradient text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0">{{ $totalRespuestas }}</h4>
                                <p class="mb-0 small">Total Respuestas</p>
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
                                <i class="fas fa-percentage fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0">{{ $porcentajeParticipacion }}%</h4>
                                <p class="mb-0 small">Participación</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-info bg-gradient text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-question-circle fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0">{{ $totalPreguntas }}</h4>
                                <p class="mb-0 small">Total Preguntas</p>
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
                                <i class="fas fa-calendar-alt fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0">{{ $diasActiva }}</h4>
                                <p class="mb-0 small">Días Activa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Participación por Día -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Participación por Día
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="participacionChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultados por Pregunta -->
        <div class="row">
            @foreach ($resultadosPreguntas as $index => $resultado)
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">
                                    <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                    {{ $resultado['pregunta'] }}
                                </h6>
                                <span class="badge bg-secondary">{{ $resultado['tipo'] }}</span>
                            </div>
                            @if ($resultado['descripcion'])
                                <small class="text-muted">{{ $resultado['descripcion'] }}</small>
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($resultado['tipo'] === 'seleccion_unica' || $resultado['tipo'] === 'seleccion_multiple')
                                <!-- Gráfico de barras para opciones -->
                                <div class="mb-3">
                                    <canvas id="chart_{{ $index }}" width="400" height="200"></canvas>
                                </div>
                                <!-- Tabla de resultados -->
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opción</th>
                                                <th>Respuestas</th>
                                                <th>Porcentaje</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($resultado['opciones'] as $opcion)
                                                <tr>
                                                    <td>{{ $opcion['texto'] }}</td>
                                                    <td>{{ $opcion['cantidad'] }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                                <div class="progress-bar bg-primary"
                                                                    style="width: {{ $opcion['porcentaje'] }}%"></div>
                                                            </div>
                                                            <small class="text-muted">{{ $opcion['porcentaje'] }}%</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif ($resultado['tipo'] === 'escala')
                                <!-- Gráfico de estrellas para escala -->
                                <div class="mb-3">
                                    <canvas id="chart_{{ $index }}" width="400" height="200"></canvas>
                                </div>
                                <div class="text-center">
                                    <div class="display-6 text-warning mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i <= $resultado['promedio'] ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <h4 class="text-primary">{{ number_format($resultado['promedio'], 1) }}/5</h4>
                                    <p class="text-muted">Promedio de calificación</p>
                                </div>
                            @elseif ($resultado['tipo'] === 'numero')
                                <!-- Estadísticas para números -->
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="bg-light rounded p-3">
                                            <h5 class="text-primary mb-0">{{ $resultado['promedio'] }}</h5>
                                            <small class="text-muted">Promedio</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-light rounded p-3">
                                            <h5 class="text-success mb-0">{{ $resultado['maximo'] }}</h5>
                                            <small class="text-muted">Máximo</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-light rounded p-3">
                                            <h5 class="text-info mb-0">{{ $resultado['minimo'] }}</h5>
                                            <small class="text-muted">Mínimo</small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Respuestas de texto -->
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    {{ count($resultado['respuestas']) }} respuestas de texto recibidas
                                </div>
                                @if (count($resultado['respuestas']) > 0)
                                    <div class="max-height-300 overflow-auto">
                                        @foreach (array_slice($resultado['respuestas'], 0, 5) as $respuesta)
                                            <div class="border-bottom pb-2 mb-2">
                                                <small class="text-muted">{{ $respuesta['fecha'] }}</small>
                                                <p class="mb-0">{{ $respuesta['respuesta'] }}</p>
                                            </div>
                                        @endforeach
                                        @if (count($resultado['respuestas']) > 5)
                                            <div class="text-center">
                                                <small class="text-muted">
                                                    Y {{ count($resultado['respuestas']) - 5 }} respuestas más...
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Exportar Resultados -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-download text-primary me-2"></i>
                            Exportar Resultados
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <a href="{{ route('encuestas.resultados.export', ['encuesta' => $encuesta, 'formato' => 'pdf']) }}"
                                class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                            </a>
                            <a href="{{ route('encuestas.resultados.export', ['encuesta' => $encuesta, 'formato' => 'excel']) }}"
                                class="btn btn-outline-success btn-sm">
                                <i class="fas fa-file-excel me-2"></i>Exportar Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Datos para los gráficos
        window.resultadosData = @json($resultadosPreguntas);
        window.participacionData = @json($participacionPorDia);

        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de participación por día
            const participacionCtx = document.getElementById('participacionChart').getContext('2d');
            new Chart(participacionCtx, {
                type: 'line',
                data: {
                    labels: window.participacionData.labels,
                    datasets: [{
                        label: 'Respuestas por día',
                        data: window.participacionData.data,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Gráficos para cada pregunta
            window.resultadosData.forEach((resultado, index) => {
                if (resultado.tipo === 'seleccion_unica' || resultado.tipo === 'seleccion_multiple') {
                    const ctx = document.getElementById(`chart_${index}`).getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: resultado.opciones.map(op => op.texto),
                            datasets: [{
                                label: 'Respuestas',
                                data: resultado.opciones.map(op => op.cantidad),
                                backgroundColor: [
                                    '#0d6efd', '#198754', '#ffc107', '#dc3545',
                                    '#6f42c1', '#fd7e14', '#20c997', '#e83e8c'
                                ],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                } else if (resultado.tipo === 'escala') {
                    const ctx = document.getElementById(`chart_${index}`).getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: resultado.distribucion.map(item => `${item.valor} estrellas`),
                            datasets: [{
                                data: resultado.distribucion.map(item => item.cantidad),
                                backgroundColor: [
                                    '#dc3545', '#fd7e14', '#ffc107', '#198754',
                                    '#0d6efd'
                                ]
                            }]
                        },
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
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .max-height-300 {
            max-height: 300px;
        }

        .overflow-auto {
            overflow-y: auto;
        }
    </style>
@endpush
