@extends('layouts.app')

@section('title', 'Detalle de Familia')

@section('content')
    <div class="container py-4">
        <x-page-header title="Familia {{ $familia->codigo }}">
            <a href="{{ route('familias.edit', $familia) }}" class="btn btn-secondary">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
        </x-page-header>

        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Información General</h5>
                        <div class="mb-2"><strong>Código:</strong> {{ $familia->codigo }}</div>
                        <div class="mb-2"><strong>Dirección:</strong> {{ $familia->direccion }}</div>
                        <div class="mb-2"><strong>Barrio:</strong> {{ $familia->barrio?->name ?? '-' }}</div>
                        <div class="mb-2"><strong>Teléfono:</strong> {{ $familia->telefono ?? '-' }}</div>
                        <div class="mb-2"><strong>Jefe de familia:</strong> {{ $familia->jefe?->primer_nombre }}
                            {{ $familia->jefe?->primer_apellido }}</div>
                        <div class="mb-2"><strong>Coordenadas:</strong> {{ $familia->latitud }}, {{ $familia->longitud }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Integrantes</h5>
                        <ul class="list-group list-group-flush">
                            @forelse($familia->personas as $p)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-user me-1"></i> {{ $p->primer_nombre }} {{ $p->primer_apellido }}
                                        <small class="text-muted">({{ $p->numero_documento }})</small>
                                    </span>
                                    @if ($p->pivot->es_jefe)
                                        <span class="badge bg-primary">Jefe</span>
                                    @endif
                                </li>
                            @empty
                                <li class="list-group-item text-muted">Sin personas asociadas</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
