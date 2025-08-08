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
                                <option value="{{ $p->id }}" @selected(old('jefe_persona_id') == $p->id)>
                                    {{ $p->primer_nombre }} {{ $p->primer_apellido }} -
                                    {{ $p->numero_documento }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i>
                            El jefe de familia no puede ser jefe de otra familia.
                        </div>
                    </div>

                    @push('styles')
                        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
                            rel="stylesheet" />
                        <link
                            href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
                            rel="stylesheet" />
                    @endpush

                    @push('scripts')
                        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                        <script>
                            $(function() {
                                $('#jefe_persona_id').select2({
                                    theme: 'bootstrap-5',
                                    placeholder: '-- Seleccione --',
                                    allowClear: true,
                                    width: '100%'
                                });
                            });
                        </script>
                    @endpush

                    <div class="mt-3">
                        <label class="form-label fw-semibold">Miembros de la familia</label>
                        <x-persona-selector :personas="$personas" :selected="old('personas', [])" />
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i>
                            Selecciona los miembros de la familia. El jefe de familia se agregará automáticamente.
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-form-actions cancel-route="familias.index" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
