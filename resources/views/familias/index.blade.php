@extends('layouts.app')

@section('title', 'Familias')

@section('content')
    <div class="container py-4">
        <x-page-header title="Familias">
            <a href="{{ route('familias.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nueva Familia
            </a>
        </x-page-header>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Jefe</th>
                                <th>Dirección</th>
                                <th>Barrio</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($familias as $familia)
                                <tr>
                                    <td>{{ $familia->codigo }}</td>
                                    <td>{{ $familia->jefe?->primer_nombre }} {{ $familia->jefe?->primer_apellido }}</td>
                                    <td>{{ $familia->direccion }}</td>
                                    <td>{{ $familia->barrio?->name }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('familias.show', $familia) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('familias.edit', $familia) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay familias registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $familias->links() }}
            </div>
        </div>
    </div>
@endsection
