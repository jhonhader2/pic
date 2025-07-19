@extends('layouts.app')

@section('title', 'Crear Encuesta')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-dark">Crear Nueva Encuesta</h1>
                        <p class="text-muted mb-0">Complete el formulario para crear una nueva encuesta</p>
                    </div>
                    <a href="{{ route('encuestas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST" action="{{ route('encuestas.store') }}" id="encuestaForm">
                            @csrf

                            <div class="row">
                                <!-- Información Básica -->
                                <div class="col-md-8">
                                    <h5 class="card-title mb-4">
                                        <i class="fas fa-info-circle me-2"></i>Información Básica
                                    </h5>

                                    <!-- Título -->
                                    <x-form-input name="titulo" label="Título"
                                        placeholder="Ingrese el título de la encuesta" required="true" icon="edit" />

                                    <!-- Descripción -->
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-align-left"></i>
                                            </span>
                                            <textarea id="descripcion" name="descripcion" rows="3"
                                                class="form-control @error('descripcion') is-invalid @enderror"
                                                placeholder="Ingrese una descripción opcional de la encuesta">{{ old('descripcion') }}</textarea>
                                        </div>
                                        @error('descripcion')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Fechas -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <x-form-input name="fecha_inicio" label="Fecha de Inicio" type="date"
                                                required="true" icon="calendar" min="{{ date('Y-m-d') }}" />
                                        </div>
                                        <div class="col-md-6">
                                            <x-form-input name="fecha_fin" label="Fecha de Fin" type="date"
                                                required="true" icon="calendar-check" />
                                        </div>
                                    </div>

                                    <!-- Estado -->
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" id="activa" name="activa" value="1"
                                                {{ old('activa', true) ? 'checked' : '' }} class="form-check-input">
                                            <label for="activa" class="form-check-label fw-semibold">
                                                <i class="fas fa-toggle-on me-2"></i>Encuesta activa
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Temas -->
                                <div class="col-md-4">
                                    <h5 class="card-title mb-4">
                                        <i class="fas fa-tags me-2"></i>Temas/Preguntas
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Seleccionar Temas <span class="text-danger">*</span>
                                        </label>
                                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                            @if ($temas->count() > 0)
                                                @foreach ($temas as $tema)
                                                    <div class="form-check">
                                                        <input type="checkbox" id="tema_{{ $tema->id }}" name="temas[]"
                                                            value="{{ $tema->id }}"
                                                            {{ in_array($tema->id, old('temas', [])) ? 'checked' : '' }}
                                                            class="form-check-input @error('temas') is-invalid @enderror">
                                                        <label for="tema_{{ $tema->id }}" class="form-check-label">
                                                            <i class="fas fa-tag me-1"></i>{{ $tema->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted small">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>No hay temas disponibles
                                                </p>
                                            @endif
                                        </div>
                                        @error('temas')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Personas Asignadas -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="card-title mb-4">
                                        <i class="fas fa-users me-2"></i>Personas Asignadas (Opcional)
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Seleccionar Personas</label>
                                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                            @if ($personas->count() > 0)
                                                <div class="row">
                                                    @foreach ($personas as $persona)
                                                        <div class="col-md-6 col-lg-4 mb-2">
                                                            <div class="form-check">
                                                                <input type="checkbox" id="persona_{{ $persona->id }}"
                                                                    name="personas[]" value="{{ $persona->id }}"
                                                                    {{ in_array($persona->id, old('personas', [])) ? 'checked' : '' }}
                                                                    class="form-check-input">
                                                                <label for="persona_{{ $persona->id }}"
                                                                    class="form-check-label">
                                                                    <i class="fas fa-user me-1"></i>
                                                                    <strong>{{ $persona->primer_nombre }}
                                                                        {{ $persona->primer_apellido }}</strong>
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        <i
                                                                            class="fas fa-id-card me-1"></i>{{ $persona->numero_documento }}
                                                                    </small>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-muted small">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>No hay personas
                                                    registradas
                                                </p>
                                            @endif
                                        </div>
                                        @error('personas')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('encuestas.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Crear Encuesta
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFin = document.getElementById('fecha_fin');
            const form = document.getElementById('encuestaForm');

            // Validar que la fecha de fin sea posterior a la de inicio
            fechaInicio.addEventListener('change', function() {
                fechaFin.min = this.value;
                if (fechaFin.value && fechaFin.value <= this.value) {
                    fechaFin.value = '';
                }
            });

            // Validación del formulario
            form.addEventListener('submit', function(e) {
                const temas = document.querySelectorAll('input[name="temas[]"]:checked');

                if (temas.length === 0) {
                    e.preventDefault();
                    alert('Debe seleccionar al menos un tema.');
                    return false;
                }

                if (fechaInicio.value && fechaFin.value && fechaFin.value <= fechaInicio.value) {
                    e.preventDefault();
                    alert('La fecha de fin debe ser posterior a la fecha de inicio.');
                    return false;
                }
            });

            // Establecer fecha mínima para fecha_inicio
            fechaInicio.min = new Date().toISOString().split('T')[0];
        });
    </script>
@endpush
