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
                <x-card title="0" subtitle="Pacientes" icon="users" color="primary" />
            </div>

            <div class="col-md-6 col-lg-3">
                <x-card title="0" subtitle="Citas Hoy" icon="calendar-check" color="success" />
            </div>

            <div class="col-md-6 col-lg-3">
                <x-card title="0" subtitle="Reportes" icon="clipboard-list" color="warning" />
            </div>

            <div class="col-md-6 col-lg-3">
                <x-card title="0" subtitle="Configuraciones" icon="cogs" color="info" />
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
                                            <strong>Información:</strong> Este es un panel de control básico. Las
                                            funcionalidades completas estarán disponibles próximamente.
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
