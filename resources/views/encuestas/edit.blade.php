@extends('layouts.app')

@section('title', 'Editar Encuesta')

@section('content')
    <div class="container-fluid py-4">
        <!-- Alertas de Sesión -->
        <x-session-alerts />

        <!-- Header -->
        <x-page-header title="Editar Encuesta" subtitle="Modifique la información de la encuesta">
            <x-slot name="actions">
                <a href="{{ route('encuestas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </x-slot>
        </x-page-header>

        <!-- Formulario -->
        <x-card title="Información de la Encuesta" subtitle="Modifique los datos de la encuesta" icon="edit">
            <form method="POST" action="{{ route('encuestas.update', $encuesta) }}" id="encuestaForm">
                @csrf
                @method('PUT')

                <!-- Información Básica -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Información Básica
                        </h6>

                        <!-- Título -->
                        <div class="mb-3">
                            <label for="titulo" class="form-label fw-semibold">Título <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror"
                                    id="titulo" name="titulo" value="{{ old('titulo', $encuesta->titulo) }}"
                                    placeholder="Ingrese el título de la encuesta" required>
                            </div>
                            @error('titulo')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-align-left"></i>
                                </span>
                                <textarea id="descripcion" name="descripcion" rows="3"
                                    class="form-control @error('descripcion') is-invalid @enderror"
                                    placeholder="Ingrese una descripción opcional de la encuesta">{{ old('descripcion', $encuesta->descripcion) }}</textarea>
                            </div>
                            @error('descripcion')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Fechas -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_inicio" class="form-label fw-semibold">Fecha de Inicio <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        <input type="date"
                                            class="form-control @error('fecha_inicio') is-invalid @enderror"
                                            id="fecha_inicio" name="fecha_inicio"
                                            value="{{ old('fecha_inicio', $encuesta->fecha_inicio->format('Y-m-d')) }}"
                                            required>
                                    </div>
                                    @error('fecha_inicio')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_fin" class="form-label fw-semibold">Fecha de Fin <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar-check"></i>
                                        </span>
                                        <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                                            id="fecha_fin" name="fecha_fin"
                                            value="{{ old('fecha_fin', $encuesta->fecha_fin->format('Y-m-d')) }}" required>
                                    </div>
                                    @error('fecha_fin')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input @error('activa') is-invalid @enderror"
                                    id="activa" name="activa" value="1"
                                    {{ old('activa', $encuesta->activa) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="activa">
                                    <i class="fas fa-toggle-on me-2 text-primary"></i>Encuesta activa
                                </label>
                            </div>
                            @error('activa')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Personas Asignadas -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3">
                            <i class="fas fa-users me-2 text-primary"></i>Personas Asignadas
                        </h6>

                        <x-persona-selector :personas="$personas" :selected="old('personas', $encuesta->personas->pluck('id')->toArray())" />
                    </div>
                </div>

                <!-- Información de Preguntas -->
                @if ($encuesta->temas->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-question-circle me-2 text-primary"></i>Preguntas Configuradas
                            </h6>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Esta encuesta tiene {{ $encuesta->temas->count() }} pregunta(s) configurada(s).
                                Para modificar las preguntas, vaya a la vista de detalles de la encuesta.
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Botones de Acción -->
                <x-form-actions :cancelRoute="route('encuestas.show', $encuesta)" submitText="Actualizar Encuesta" submitIcon="save" />
            </form>
        </x-card>
    </div>
@endsection

<x-date-validation-script />
