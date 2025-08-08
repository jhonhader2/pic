@extends('layouts.app')

@section('title', 'Detalle de Persona')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <x-page-header title="Detalle de Persona" icon="user">
                    <div class="d-flex gap-2">
                        <a href="{{ route('personas.edit', $persona) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a>
                        <a href="{{ route('personas.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver al listado
                        </a>
                    </div>
                </x-page-header>

                <x-session-alerts />

                <div class="row">
                    <!-- Información Personal -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-user me-2"></i>
                                    Información Personal
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Tipo de Documento</label>
                                            <div class="form-control-plaintext">
                                                {{ $persona->tipoDocumento?->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Número de Documento</label>
                                            <div class="form-control-plaintext fw-bold">{{ $persona->numero_documento }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Primer Nombre</label>
                                            <div class="form-control-plaintext">{{ $persona->primer_nombre }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Segundo Nombre</label>
                                            <div class="form-control-plaintext">{{ $persona->segundo_nombre ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Primer Apellido</label>
                                            <div class="form-control-plaintext">{{ $persona->primer_apellido }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Segundo Apellido</label>
                                            <div class="form-control-plaintext">{{ $persona->segundo_apellido ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Fecha de Nacimiento</label>
                                            <div class="form-control-plaintext">
                                                {{ $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Edad</label>
                                            <div class="form-control-plaintext">
                                                @if ($persona->edad)
                                                    <span class="badge bg-info fs-6">{{ $persona->edad }} años</span>
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Sexo</label>
                                            <div class="form-control-plaintext">
                                                <span
                                                    class="badge {{ $persona->sexo?->name === 'MASCULINO' ? 'bg-primary' : 'bg-pink' }} fs-6">
                                                    {{ $persona->sexo?->name ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Identidad de Género</label>
                                            <div class="form-control-plaintext">
                                                {{ $persona->identidadGenero?->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Estado Civil</label>
                                            <div class="form-control-plaintext">{{ $persona->estadoCivil?->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">Ocupación</label>
                                    <div class="form-control-plaintext">{{ $persona->ocupacion?->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-phone me-2"></i>
                                    Información de Contacto
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Teléfono Fijo</label>
                                            <div class="form-control-plaintext">
                                                @if ($persona->telefono)
                                                    <i class="fas fa-phone me-2 text-muted"></i>{{ $persona->telefono }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Número de Celular</label>
                                            <div class="form-control-plaintext">
                                                <i class="fas fa-mobile-alt me-2 text-muted"></i>{{ $persona->celular }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Barrio</label>
                                            <div class="form-control-plaintext">
                                                <i
                                                    class="fas fa-map-marker-alt me-2 text-muted"></i>{{ $persona->barrio?->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Dirección</label>
                                            <div class="form-control-plaintext">
                                                <i class="fas fa-home me-2 text-muted"></i>{{ $persona->direccion }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Salud -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-heartbeat me-2"></i>
                                    Información de Salud
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Tipo de Sangre</label>
                                            <div class="form-control-plaintext">
                                                <span
                                                    class="badge bg-danger fs-6">{{ $persona->tipoSangre?->name ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Factor RH</label>
                                            <div class="form-control-plaintext">
                                                <span
                                                    class="badge bg-dark fs-6">{{ $persona->factorRh?->name ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Afiliación a Salud</label>
                                            <div class="form-control-plaintext">
                                                @if ($persona->afiliacion_salud)
                                                    <span class="badge bg-success fs-6">
                                                        <i class="fas fa-check me-1"></i>Sí tiene afiliación
                                                    </span>
                                                    @if ($persona->tipoAfiliacionSalud)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Tipo:</small>
                                                            {{ $persona->tipoAfiliacionSalud->name }}
                                                        </div>
                                                    @endif
                                                    @if ($persona->eps)
                                                        <div class="mt-1">
                                                            <small class="text-muted">EPS:</small>
                                                            {{ $persona->eps->name }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary fs-6">
                                                        <i class="fas fa-times me-1"></i>No tiene afiliación
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Discapacidad</label>
                                            <div class="form-control-plaintext">
                                                @if ($persona->discapacidad)
                                                    <span class="badge bg-warning fs-6">
                                                        <i class="fas fa-wheelchair me-1"></i>Presenta discapacidad
                                                    </span>
                                                    @if ($persona->tipoDiscapacidad)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Tipo:</small>
                                                            {{ $persona->tipoDiscapacidad->name }}
                                                        </div>
                                                    @endif
                                                    <div class="mt-1">
                                                        <small class="text-muted">Atención integral:</small>
                                                        @if ($persona->atencion_integral_discapacidad)
                                                            <span class="badge bg-success">Sí</span>
                                                        @else
                                                            <span class="badge bg-secondary">No</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary fs-6">
                                                        <i class="fas fa-check me-1"></i>No presenta discapacidad
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Étnica -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">
                                    <i class="fas fa-globe me-2"></i>
                                    Información Étnica
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Pertenencia Étnica</label>
                                            <div class="form-control-plaintext">
                                                {{ $persona->pertenenciaEtnica?->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Nombre de la Etnia</label>
                                            <div class="form-control-plaintext">{{ $persona->nombre_etnia ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar con información adicional -->
                    <div class="col-lg-4">
                        <!-- Información del Usuario -->
                        @if ($persona->user)
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user-cog me-2"></i>
                                        Usuario del Sistema
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Nombre:</strong> {{ $persona->user->name }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Email:</strong> {{ $persona->user->email }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Registrado:</strong> {{ $persona->user->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    @if ($persona->user->is_admin)
                                        <span class="badge bg-danger">Administrador</span>
                                    @else
                                        <span class="badge bg-primary">Usuario</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Información de registro -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Información de Registro
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong>Fecha de creación:</strong><br>
                                    <small>{{ $persona->created_at->format('d/m/Y H:i:s') }}</small>
                                </div>
                                <div class="mb-2">
                                    <strong>Última actualización:</strong><br>
                                    <small>{{ $persona->updated_at->format('d/m/Y H:i:s') }}</small>
                                </div>
                                <div class="mb-2">
                                    <strong>ID del registro:</strong><br>
                                    <small class="text-muted font-monospace">{{ $persona->id }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones rápidas -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-bolt me-2"></i>
                                    Acciones Rápidas
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('personas.edit', $persona) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit me-2"></i>Editar Información
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        onclick="confirmarEliminacion('{{ $persona->id }}', '{{ trim($persona->primer_nombre . ' ' . $persona->primer_apellido) }}')">
                                        <i class="fas fa-trash me-2"></i>Eliminar Persona
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-sm"
                                        onclick="imprimirDetalle()">
                                        <i class="fas fa-print me-2"></i>Imprimir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalEliminarLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar a la persona <strong id="nombrePersona"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta acción no se puede deshacer.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <form id="formEliminar" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger" onclick="eliminarPersona()">
                            <i class="fas fa-trash me-2"></i>Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmarEliminacion(personaId, nombrePersona) {
            document.getElementById('nombrePersona').textContent = nombrePersona;
            document.getElementById('formEliminar').action = `/personas/${personaId}`;

            const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
            modal.show();
        }

        function eliminarPersona() {
            const form = document.getElementById('formEliminar');
            const btn = form.querySelector('button[type="button"]');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Eliminando...';

            fetch(form.action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route('personas.index') }}';
                    } else {
                        alert('Error al eliminar la persona: ' + (data.message || 'Error desconocido'));
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-trash me-2"></i>Eliminar';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar la persona');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash me-2"></i>Eliminar';
                });
        }

        function imprimirDetalle() {
            window.print();
        }
    </script>

    <style>
        .bg-pink {
            background-color: #e83e8c !important;
        }

        .form-control-plaintext {
            padding-left: 0;
            padding-right: 0;
            border: none;
            background-color: transparent;
        }

        @media print {

            .btn,
            .card-header,
            .modal {
                display: none !important;
            }

            .card {
                border: 1px solid #dee2e6 !important;
                box-shadow: none !important;
            }
        }
    </style>
@endsection
