@extends('layouts.app')

@section('title', 'Gestión de Personas')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <x-page-header title="Gestión de Personas" icon="users">
                    <a href="{{ route('personas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nueva Persona
                    </a>
                </x-page-header>

                <x-session-alerts />

                <!-- Filtros de búsqueda -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('personas.index') }}" id="filtros-form">
                            <div class="row">
                                <div class="col-md-3">
                                    <x-form-input name="buscar" label="Buscar"
                                        placeholder="Nombre, apellido o documento..." icon="search" :value="request('buscar')"
                                        id="buscar_input" />
                                </div>
                                <div class="col-md-2">
                                    <x-form-select name="sexo" label="Sexo" :options="array_merge(['' => 'Todos'], App\Helpers\SexoHelper::getOpciones())" icon="venus-mars"
                                        id="sexo_filtro_select" />
                                </div>
                                <div class="col-md-2">
                                    <x-form-select name="estado_civil" label="Estado Civil" :options="array_merge(
                                        ['' => 'Todos'],
                                        App\Helpers\EstadoCivilHelper::getOpciones(),
                                    )" icon="heart"
                                        id="estado_civil_filtro_select" />
                                </div>
                                <div class="col-md-2">
                                    <x-form-select name="barrio" label="Barrio" :options="array_merge(['' => 'Todos'], App\Helpers\BarrioHelper::getOpciones())" icon="map-marker-alt"
                                        id="barrio_filtro_select" />
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-2"></i>Buscar
                                        </button>
                                        <a href="{{ route('personas.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-eraser me-2"></i>Limpiar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Listado de personas -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Listado de Personas ({{ $personas->total() }} registros)
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-light btn-sm" onclick="exportarDatos()">
                                <i class="fas fa-download me-1"></i>Exportar
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($personas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="15%">Documento</th>
                                            <th width="25%">Nombre Completo</th>
                                            <th width="10%">Edad</th>
                                            <th width="8%">Sexo</th>
                                            <th width="12%">Estado Civil</th>
                                            <th width="15%">Barrio</th>
                                            <th width="10%" class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($personas as $index => $persona)
                                            <tr>
                                                <td>{{ $personas->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="fw-bold">{{ $persona->tipoDocumento?->name ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $persona->numero_documento }}</small>
                                                </td>
                                                <td>
                                                    <div class="fw-bold">
                                                        {{ trim($persona->primer_nombre . ' ' . $persona->segundo_nombre) }}
                                                    </div>
                                                    <div class="text-muted">
                                                        {{ trim($persona->primer_apellido . ' ' . $persona->segundo_apellido) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($persona->edad)
                                                        <span class="badge bg-info">{{ $persona->edad }} años</span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $persona->sexo?->name === 'MASCULINO' ? 'bg-primary' : 'bg-pink' }}">
                                                        {{ $persona->sexo?->name ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>{{ $persona->estadoCivil?->name ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $persona->barrio?->name ?? 'N/A' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('personas.show', $persona) }}"
                                                            class="btn btn-outline-info btn-sm" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('personas.edit', $persona) }}"
                                                            class="btn btn-outline-primary btn-sm" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            title="Eliminar"
                                                            onclick="confirmarEliminacion('{{ $persona->id }}', '{{ trim($persona->primer_nombre . ' ' . $persona->primer_apellido) }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginación -->
                            <div class="card-footer bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            Mostrando {{ $personas->firstItem() }} a {{ $personas->lastItem() }}
                                            de {{ $personas->total() }} registros
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        {{ $personas->links() }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No se encontraron personas</h5>
                                <p class="text-muted">
                                    @if (request()->hasAny(['buscar', 'sexo', 'estado_civil', 'barrio']))
                                        No hay resultados que coincidan con los filtros aplicados.
                                    @else
                                        Aún no hay personas registradas en el sistema.
                                    @endif
                                </p>
                                @if (!request()->hasAny(['buscar', 'sexo', 'estado_civil', 'barrio']))
                                    <a href="{{ route('personas.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Crear Primera Persona
                                    </a>
                                @endif
                            </div>
                        @endif
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

    <!-- jQuery (requerido para Select2) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Select2 solo en filtros (sin placeholder para permitir "Todos")
            $('#filtros-form .select2').select2({
                theme: 'bootstrap-5',
                allowClear: false,
                width: '100%'
            });

            // Preservar valores de filtros en los selects
            const filtros = @json(request()->only(['sexo', 'estado_civil', 'barrio']));

            Object.keys(filtros).forEach(key => {
                const select = $('#' + key + '_filtro_select');
                if (select.length && filtros[key]) {
                    select.val(filtros[key]).trigger('change');
                }
            });

            // Auto-submit del formulario al cambiar filtros
            $('#filtros-form .select2').on('change', function() {
                document.getElementById('filtros-form').submit();
            });
        });

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
                        location.reload();
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

        function exportarDatos() {
            // Obtener parámetros de filtro actuales
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'excel');

            // Crear URL para descarga
            const exportUrl = `{{ route('personas.index') }}?${params.toString()}`;

            // Abrir en nueva ventana/tab para descarga
            window.open(exportUrl, '_blank');
        }
    </script>

    <style>
        .bg-pink {
            background-color: #e83e8c !important;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }
    </style>
@endsection
