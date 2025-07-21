@extends('layouts.app')

@section('title', 'Crear Encuesta')

@section('content')
    <div class="container-fluid py-4">
        <!-- Alertas de Sesión -->
        <x-session-alerts />

        <!-- Header -->
        <x-page-header title="Crear Nueva Encuesta" subtitle="Complete el formulario para crear una nueva encuesta">
            <x-slot name="actions">
                <a href="{{ route('encuestas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </x-slot>
        </x-page-header>

        <!-- Formulario -->
        <x-card title="Información de la Encuesta" subtitle="Datos básicos de la nueva encuesta" icon="info-circle">
            <form method="POST" action="{{ route('encuestas.store') }}" id="encuestaForm">
                @csrf

                <!-- Información Básica -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Información Básica
                        </h6>

                        <!-- Título -->
                        <x-form-input name="titulo" label="Título" placeholder="Ingrese el título de la encuesta"
                            required="true" icon="edit" />

                        <!-- Descripción -->
                        <x-form-textarea name="descripcion" label="Descripción"
                            placeholder="Ingrese una descripción opcional de la encuesta" icon="align-left" />

                        <!-- Fechas -->
                        <div class="row">
                            <div class="col-md-6">
                                <x-form-input name="fecha_inicio" label="Fecha de Inicio" type="date" required="true"
                                    icon="calendar" min="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="col-md-6">
                                <x-form-input name="fecha_fin" label="Fecha de Fin" type="date" required="true"
                                    icon="calendar-check" />
                            </div>
                        </div>

                        <!-- Estado -->
                        <x-form-checkbox name="activa" label="Encuesta activa" icon="toggle-on" :checked="old('activa', true)" />
                    </div>
                </div>

                <!-- Personas Asignadas -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3">
                            <i class="fas fa-users me-2 text-primary"></i>Personas Asignadas
                        </h6>

                        <x-persona-selector :personas="$personas" :selected="old('personas', [])" />
                    </div>
                </div>

                <!-- Botones de Acción -->
                <x-form-actions :cancelRoute="route('encuestas.index')" submitText="Siguiente: Configurar Preguntas" submitIcon="arrow-right" />
            </form>
        </x-card>
    </div>
@endsection

<x-date-validation-script />
