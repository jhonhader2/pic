@extends('layouts.app')

@section('title', 'Editar Familia')

@section('content')
    <div class="container py-4">
        <x-page-header title="Editar Familia: {{ $familia->codigo }}">
            <div class="btn-group" role="group">
                <a href="{{ route('familias.show', $familia) }}" class="btn btn-info">
                    <i class="fas fa-eye me-1"></i> Ver Detalles
                </a>
                <button type="button" class="btn btn-warning" onclick="document.getElementById('editForm').submit()">
                    <i class="fas fa-save me-1"></i> Guardar Cambios
                </button>
            </div>
        </x-page-header>

        <!-- Información Actual de la Familia -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-home fa-2x mb-2"></i>
                        <h5 class="mb-0">{{ $familia->codigo }}</h5>
                        <small>Código Actual</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h5 class="mb-0">{{ $familia->personas->count() }}</h5>
                        <small>Miembros Actuales</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-crown fa-2x mb-2"></i>
                        <h5 class="mb-0">{{ $familia->jefe ? $familia->jefe->primer_nombre : 'Sin Jefe' }}</h5>
                        <small>Jefe Actual</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                        <h5 class="mb-0">{{ $familia->barrio ? $familia->barrio->name : 'Sin Barrio' }}</h5>
                        <small>Barrio Actual</small>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('familias.update', $familia) }}" id="editForm">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Información Básica -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-edit me-2"></i>Información Básica
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('codigo') is-invalid @enderror"
                                            id="codigo" name="codigo" value="{{ old('codigo', $familia->codigo) }}"
                                            placeholder="Código de la familia" required>
                                        <label for="codigo">
                                            <i class="fas fa-hashtag me-1"></i>Código
                                        </label>
                                        @error('codigo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                            id="telefono" name="telefono" value="{{ old('telefono', $familia->telefono) }}"
                                            placeholder="Teléfono">
                                        <label for="telefono">
                                            <i class="fas fa-phone me-1"></i>Teléfono
                                        </label>
                                        @error('telefono')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                            id="direccion" name="direccion"
                                            value="{{ old('direccion', $familia->direccion) }}"
                                            placeholder="Dirección completa" required>
                                        <label for="direccion">
                                            <i class="fas fa-map-marker-alt me-1"></i>Dirección Completa
                                        </label>
                                        @error('direccion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ubicación Geográfica -->
                    <div class="card mt-3">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-globe me-2"></i>Ubicación Geográfica
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" step="any"
                                            class="form-control @error('latitud') is-invalid @enderror" id="latitud"
                                            name="latitud" value="{{ old('latitud', $familia->latitud) }}"
                                            placeholder="Latitud">
                                        <label for="latitud">
                                            <i class="fas fa-latitude me-1"></i>Latitud
                                        </label>
                                        @error('latitud')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" step="any"
                                            class="form-control @error('longitud') is-invalid @enderror" id="longitud"
                                            name="longitud" value="{{ old('longitud', $familia->longitud) }}"
                                            placeholder="Longitud">
                                        <label for="longitud">
                                            <i class="fas fa-longitude me-1"></i>Longitud
                                        </label>
                                        @error('longitud')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Consejo:</strong> Puedes obtener las coordenadas desde Google Maps haciendo clic
                                    derecho en la ubicación y seleccionando "¿Qué hay aquí?"
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Lateral -->
                <div class="col-lg-4">
                    <!-- Jefe de Familia -->
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-crown me-2"></i>Jefe de Familia
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="form-floating">
                                <select id="jefe_persona_id" name="jefe_persona_id"
                                    class="form-select @error('jefe_persona_id') is-invalid @enderror">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($personas as $p)
                                        <option value="{{ $p->id }}" @selected(old('jefe_persona_id', $familia->jefe_persona_id) == $p->id)>
                                            {{ $p->primer_nombre }} {{ $p->primer_apellido }} -
                                            {{ $p->numero_documento }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="jefe_persona_id">
                                    <i class="fas fa-user me-1"></i>Seleccionar Jefe
                                </label>
                                @error('jefe_persona_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Importante:</strong> El jefe de familia no puede ser jefe de otra familia.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="card mt-3">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cogs me-2"></i>Acciones Rápidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                </button>
                                <a href="{{ route('familias.show', $familia) }}" class="btn btn-info">
                                    <i class="fas fa-eye me-2"></i>Ver Detalles
                                </a>
                                <a href="{{ route('familias.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Miembros de la Familia -->
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Miembros de la Familia
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <x-persona-selector :personas="$personas" :selected="old('personas', $familia->personas->pluck('id')->toArray())" />
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="alert alert-success">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Nota:</strong> Selecciona los miembros de la familia. El jefe de familia se agregará
                            automáticamente si no está incluido.
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
            rel="stylesheet" />
        <style>
            .form-floating>.form-control:focus~label,
            .form-floating>.form-control:not(:placeholder-shown)~label {
                color: #0d6efd;
            }

            .card-header {
                border-bottom: none;
            }

            .alert {
                border-radius: 0.5rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(function() {
                // Inicializar Select2
                $('#jefe_persona_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: '-- Seleccione --',
                    allowClear: true,
                    width: '100%'
                });

                // Validación en tiempo real
                $('input[required]').on('blur', function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid').addClass('is-valid');
                    }
                });

                // Confirmación antes de enviar
                $('#editForm').on('submit', function(e) {
                    const codigo = $('#codigo').val();
                    const direccion = $('#direccion').val();

                    if (!codigo || !direccion) {
                        e.preventDefault();
                        alert('Por favor, completa los campos obligatorios (Código y Dirección)');
                        return false;
                    }
                });

                // Auto-guardado de coordenadas (opcional)
                if (navigator.geolocation) {
                    $('#getLocation').on('click', function() {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            $('#latitud').val(position.coords.latitude);
                            $('#longitud').val(position.coords.longitude);
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
