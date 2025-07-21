@extends('layouts.app')

@section('title', 'Panel de Control - PIC')

@section('content')
    <div class="container py-5">
        <!-- Mensajes de éxito/error -->
        @if (session('success'))
            <x-alert type="success">
                {{ session('success') }}
            </x-alert>
        @endif

        @if (session('error'))
            <x-alert type="error">
                {{ session('error') }}
            </x-alert>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="fas fa-tachometer-alt text-primary"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-1" style="color: #0066CC;">Panel de Control</h1>
                        <p class="text-muted mb-0">Bienvenido, {{ Auth::user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <x-card title="{{ $stats['total_usuarios'] }}" subtitle="Usuarios" icon="users" color="primary" />
            </div>

            <div class="col-md-6 col-lg-3">
                <x-card title="{{ $stats['total_personas'] }}" subtitle="Personas" icon="user-friends" color="info" />
            </div>

            <div class="col-md-6 col-lg-3">
                <x-card title="{{ $stats['total_encuestas'] }}" subtitle="Encuestas" icon="clipboard-list"
                    color="warning" />
            </div>

            <div class="col-md-6 col-lg-3">
                <x-card title="{{ $stats['total_respuestas'] }}" subtitle="Respuestas" icon="comments" color="success" />
            </div>
        </div>

        <!-- Estadísticas Adicionales -->
        <div class="row g-4 mt-2">
            <div class="col-md-6 col-lg-3">
                <x-card title="{{ $stats['encuestas_activas'] }}" subtitle="Encuestas Activas" icon="check-circle"
                    color="success" />
            </div>

            <div class="col-md-6 col-lg-3">
                <x-card title="{{ $stats['respuestas_hoy'] }}" subtitle="Respuestas Hoy" icon="calendar-day"
                    color="info" />
            </div>
        </div>

        <!-- Gráficos y Análisis -->
        <div class="row g-4 mt-4">
            <!-- Gráfico de Respuestas por Día -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Respuestas por Día (Últimos 7 días)
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="respuestasChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Estado de Encuestas -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie text-primary me-2"></i>
                            Estado de Encuestas
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="estadoEncuestasChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas y Notificaciones -->
        <div class="row g-4 mt-4">
            <!-- Acciones Rápidas -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt text-primary me-2"></i>
                            Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ route('encuestas.create') }}" class="btn btn-primary w-100">
                                    <i class="fas fa-plus me-2"></i>Nueva Encuesta
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('personas.create') }}" class="btn btn-info w-100">
                                    <i class="fas fa-user-plus me-2"></i>Nueva Persona
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('encuestas.index') }}" class="btn btn-warning w-100">
                                    <i class="fas fa-list me-2"></i>Ver Encuestas
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('notifications.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-bell me-2"></i>Notificaciones
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notificaciones Recientes -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bell text-primary me-2"></i>
                            Notificaciones Recientes
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @php
                            $recentNotifications = Auth::user()->notifications()->limit(5)->get();
                        @endphp

                        @if ($recentNotifications->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($recentNotifications as $notification)
                                    <div
                                        class="list-group-item border-0 py-3 {{ $notification->read_at ? '' : 'bg-light' }}">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                @if (!$notification->read_at)
                                                    <i class="fas fa-circle text-primary" style="font-size: 8px;"></i>
                                                @else
                                                    <i class="fas fa-circle text-muted" style="font-size: 8px;"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <div class="fw-bold small">
                                                    {{ $notification->data['mensaje'] ?? 'Nueva notificación' }}</div>
                                                <div class="text-muted small">
                                                    {{ $notification->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle text-muted mb-2" style="font-size: 2rem;"></i>
                                <div class="text-muted">No hay notificaciones nuevas</div>
                            </div>
                        @endif

                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary btn-sm w-100">
                                Ver todas las notificaciones
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Message -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-block mb-4">
                                <i class="fas fa-heartbeat text-primary"></i>
                            </div>
                            <h2 class="fw-bold mb-3" style="color: #0066CC;">¡Bienvenido al Sistema PIC!</h2>
                            <p class="lead text-muted mb-4">
                                Has accedido exitosamente al Panel de Control del Plan de Intervenciones Colectivas.
                                Desde aquí podrás gestionar toda la información relacionada con los servicios de salud.
                            </p>
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="alert alert-info" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Información:</strong> Sistema completamente funcional con
                                            notificaciones, cache y estadísticas en tiempo real.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de Respuestas por Día
            const respuestasCtx = document.getElementById('respuestasChart').getContext('2d');
            const respuestasChart = new Chart(respuestasCtx, {
                type: 'line',
                data: {
                    labels: ['Hace 6 días', 'Hace 5 días', 'Hace 4 días', 'Hace 3 días', 'Hace 2 días',
                        'Ayer', 'Hoy'
                    ],
                    datasets: [{
                        label: 'Respuestas',
                        data: {!! json_encode($stats['respuestas_ultimos_7_dias'] ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                        borderColor: '#0066CC',
                        backgroundColor: 'rgba(0, 102, 204, 0.1)',
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
                                stepSize: 5
                            }
                        }
                    }
                }
            });

            // Gráfico de Estado de Encuestas
            const estadoCtx = document.getElementById('estadoEncuestasChart').getContext('2d');
            const estadoChart = new Chart(estadoCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Activas', 'Pendientes', 'Expiradas'],
                    datasets: [{
                        data: [
                            {{ $stats['encuestas_por_estado']['activas'] ?? 0 }},
                            {{ $stats['encuestas_por_estado']['pendientes'] ?? 0 }},
                            {{ $stats['encuestas_por_estado']['expiradas'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#28a745',
                            '#ffc107',
                            '#dc3545'
                        ],
                        borderWidth: 0
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
        });
    </script>
@endpush
