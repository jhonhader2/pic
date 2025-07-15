@extends('layouts.auth')

@section('title', 'Acceso Prohibido - PIC')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5 text-center">
                        <!-- Icono de error -->
                        <div class="bg-warning bg-opacity-10 p-4 rounded-circle d-inline-block mb-4">
                            <i class="fas fa-ban text-warning" style="font-size: 3rem;"></i>
                        </div>

                        <!-- Título y descripción -->
                        <h2 class="fw-bold mb-3" style="color: #0066CC;">Acceso Prohibido</h2>
                        <p class="text-muted mb-4">
                            No tienes permisos suficientes para acceder a este recurso.
                            Contacta al administrador si necesitas acceso.
                        </p>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-3">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Ir al Dashboard
                            </a>

                            <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i>
                                Volver al Inicio
                            </a>
                        </div>

                        <!-- Información adicional -->
                        <div class="mt-4 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Error 403 - Acceso Prohibido
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
