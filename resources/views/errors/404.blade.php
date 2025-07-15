@extends('layouts.auth')

@section('title', 'Página No Encontrada - PIC')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5 text-center">
                        <!-- Icono de error -->
                        <div class="bg-info bg-opacity-10 p-4 rounded-circle d-inline-block mb-4">
                            <i class="fas fa-search text-info" style="font-size: 3rem;"></i>
                        </div>

                        <!-- Título y descripción -->
                        <h2 class="fw-bold mb-3" style="color: #0066CC;">Página No Encontrada</h2>
                        <p class="text-muted mb-4">
                            La página que buscas no existe o ha sido movida.
                            Verifica la URL e intenta nuevamente.
                        </p>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-3">
                            <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-home me-2"></i>
                                Volver al Inicio
                            </a>

                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    Ir al Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Iniciar Sesión
                                </a>
                            @endauth
                        </div>

                        <!-- Información adicional -->
                        <div class="mt-4 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Error 404 - Página No Encontrada
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
