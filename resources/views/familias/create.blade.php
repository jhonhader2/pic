@extends('layouts.app')

@section('title', 'Crear Familia')

@section('content')
    <div class="container py-4">
        <x-page-header title="Nueva Familia" />

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('familias.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-form-input id="codigo" name="codigo" label="Código" required />
                        </div>
                        <div class="col-md-8">
                            <x-form-input id="direccion" name="direccion" label="Dirección" required />
                        </div>
                        <div class="col-md-4">
                            <x-form-input id="telefono" name="telefono" label="Teléfono" />
                        </div>
                        <div class="col-md-4">
                            <x-form-input id="latitud" name="latitud" label="Latitud" type="number" step="any" />
                        </div>
                        <div class="col-md-4">
                            <x-form-input id="longitud" name="longitud" label="Longitud" type="number" step="any" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="jefe_persona_id" class="form-label fw-semibold">Jefe de familia</label>
                        <select id="jefe_persona_id" name="jefe_persona_id" class="form-select">
                            <option value="">-- Seleccione --</option>
                            @foreach ($personas as $p)
                                <option value="{{ $p->id }}">{{ $p->primer_nombre }} {{ $p->primer_apellido }} -
                                    {{ $p->numero_documento }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3">
                        <x-persona-selector :personas="$personas" :selected="[]" />
                    </div>

                    <div class="mt-4">
                        <x-form-actions cancel-route="familias.index" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
