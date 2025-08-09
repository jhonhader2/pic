@extends('layouts.app')

@section('title', 'Familias')

@section('content')
    <div class="container py-4">
        <x-page-header title="Familias">
            <a href="{{ route('familias.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nueva Familia
            </a>
        </x-page-header>

        <!-- Estadísticas Generales -->
        @php
            $totalFamilias = \App\Models\Familia::count();
            $conJefe = \App\Models\Familia::whereNotNull('jefe_persona_id')->count();
            $conUbicacion = \App\Models\Familia::whereNotNull('latitud')->whereNotNull('longitud')->count();
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-home fa-2x mb-2"></i>
                        <h4 class="mb-0">{{ $totalFamilias }}</h4>
                        <small>Total Familias</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h4 class="mb-0">{{ $conJefe }}</h4>
                        <small>Con Jefe Asignado</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                        <h4 class="mb-0">{{ $conUbicacion }}</h4>
                        <small>Con Ubicación</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-female fa-2x mb-2"></i>
                        <h4 class="mb-0">{{ $mujeresCabezaFamilia }}</h4>
                        <small>Mujeres Cabeza de Familia</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Lista de Familias
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-secondary">{{ $familias->total() }} familias</span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">
                                    <i class="fas fa-hashtag me-1"></i>Código
                                </th>
                                <th>
                                    <i class="fas fa-crown me-1"></i>Jefe de Familia
                                </th>
                                <th>
                                    <i class="fas fa-map-marker-alt me-1"></i>Dirección
                                </th>
                                <th>
                                    <i class="fas fa-building me-1"></i>Barrio
                                </th>
                                <th>
                                    <i class="fas fa-users me-1"></i>Miembros
                                </th>
                                <th>
                                    <i class="fas fa-clipboard-list me-1"></i>Encuestas
                                </th>
                                <th class="text-end pe-3">
                                    <i class="fas fa-cogs me-1"></i>Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($familias as $familia)
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-secondary me-2">{{ $familia->codigo }}</span>
                                            @if ($familia->latitud && $familia->longitud)
                                                <i class="fas fa-map-marker-alt text-info ms-1" title="Tiene ubicación"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if ($familia->jefe)
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle text-primary me-2"></i>
                                                <div>
                                                    <strong>{{ $familia->jefe->primer_nombre }}
                                                        {{ $familia->jefe->primer_apellido }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $familia->jefe->numero_documento }} •
                                                        {{ $familia->jefe->edad ?? 'N/A' }} años
                                                        @if ($familia->jefe->sexo)
                                                            @if (in_array($familia->jefe->sexo_id, [13, 15]))
                                                                <span class="badge bg-success ms-1">M</span>
                                                            @elseif (in_array($familia->jefe->sexo_id, [14, 16]))
                                                                <span class="badge bg-info ms-1">F</span>
                                                            @endif
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-user-slash me-1"></i>Sin jefe asignado
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($familia->direccion, 30) }}</strong>
                                    </td>
                                    <td>
                                        @if ($familia->barrio)
                                            <span class="badge bg-light text-dark">{{ $familia->barrio->name }}</span>
                                        @else
                                            <span class="text-muted">No especificado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge bg-primary fs-6">{{ $familia->personas->count() }}</span>
                                            <br>
                                            <small class="text-muted">miembros</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge bg-info fs-6">{{ $familia->encuestas->count() }}</span>
                                            <br>
                                            <small class="text-muted">asignadas</small>
                                        </div>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('familias.show', $familia) }}"
                                                class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('familias.edit', $familia) }}"
                                                class="btn btn-sm btn-outline-secondary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                onclick="confirmarEliminacion('{{ $familia->id }}', '{{ $familia->codigo }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fas fa-home fa-3x mb-3"></i>
                                        <h5>No hay familias registradas</h5>
                                        <p class="mb-0">Comienza creando la primera familia del sistema.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Mostrando {{ $familias->firstItem() ?? 0 }} a {{ $familias->lastItem() ?? 0 }} de
                        {{ $familias->total() }} familias
                    </small>
                    {{ $familias->links() }}
                </div>
            </div>
        </div>

        <!-- Gráfico de Distribución por Edad -->
        <div class="row g-3 mt-4">
            <div class="col-12">
                <x-distribucion-edad-chart :distribucionEdad="$distribucionEdad" canvasId="distribucionEdadChart"
                    title="Distribución por Edad - Todas las Familias" />
            </div>
        </div>
    </div>

    <!-- Formulario oculto para eliminación -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            function confirmarEliminacion(familiaId, codigo) {
                if (confirm(
                        `¿Estás seguro de que quieres eliminar la familia "${codigo}"?\n\nEsta acción no se puede deshacer.`)) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/familias/${familiaId}`;
                    form.submit();
                }
            }
        </script>
    @endpush
@endsection
