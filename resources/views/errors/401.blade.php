@extends('layouts.auth')

@section('title', 'Acceso No Autorizado - PIC')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5 text-center">
                        <!-- Icono de error -->
                        <div class="bg-danger bg-opacity-10 p-4 rounded-circle d-inline-block mb-4">
                            <i class="fas fa-shield-alt text-danger" style="font-size: 3rem;"></i>
                        </div>

                        <!-- Título y descripción -->
                        <h2 class="fw-bold mb-3" style="color: #0066CC;">Acceso No Autorizado</h2>
                        <p class="text-muted mb-4">
                            No tienes permisos para acceder a esta página.
                            Debes iniciar sesión para continuar.
                        </p>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-3">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Iniciar Sesión
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
                                Si crees que esto es un error, contacta al administrador del sistema.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
