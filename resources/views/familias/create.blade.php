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
                        <div class="col-md-4">
                            <x-form-input id="direccion" name="direccion" label="Dirección" required />
                        </div>
                        <div class="col-md-4">
                            <label for="barrio_id" class="form-label fw-semibold">Barrio <span
                                    class="text-danger">*</span></label>
                            <select id="barrio_id" name="barrio_id" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach (\App\Helpers\BarrioHelper::getOpciones() as $value => $label)
                                    @if ($value !== '')
                                        <option value="{{ $value }}" @selected(old('barrio_id') == $value)>
                                            {{ $label }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <x-form-input id="latitud" name="latitud" label="Latitud" type="number" step="any" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input id="longitud" name="longitud" label="Longitud" type="number" step="any" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="jefe_persona_id" class="form-label fw-semibold mb-0">Jefe de familia</label>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#crearPersonaModal">
                                <i class="fas fa-user-plus me-1"></i>
                                Crear Nueva Persona
                            </button>
                        </div>
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
                            El jefe de familia no puede ser jefe de otra familia. Si no encuentra la persona, puede crearla
                            usando el botón "Crear Nueva Persona".
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
                                // Inicializar Select2 para jefe de familia
                                $('#jefe_persona_id').select2({
                                    theme: 'bootstrap-5',
                                    placeholder: '-- Seleccione --',
                                    allowClear: true,
                                    width: '100%'
                                });

                                // Inicializar Select2 para barrio
                                $('#barrio_id').select2({
                                    theme: 'bootstrap-5',
                                    placeholder: '-- Seleccione --',
                                    allowClear: true,
                                    width: '100%'
                                });
                            });
                        </script>

                        <script>
                            // Manejo del evento de persona creada
                            document.addEventListener('DOMContentLoaded', function() {
                                const jefeSelect = document.getElementById('jefe_persona_id');

                                // Escuchar el evento personalizado de persona creada
                                document.addEventListener('personaCreated', function(event) {
                                    const persona = event.detail.persona;

                                    // Agregar nueva persona al select
                                    const newOption = new Option(
                                        `${persona.primer_nombre} ${persona.primer_apellido} - ${persona.numero_documento}`,
                                        persona.id,
                                        true,
                                        true
                                    );
                                    jefeSelect.appendChild(newOption);
                                    jefeSelect.value = persona.id;
                                    $(jefeSelect).trigger('change');
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

    <!-- Usar el componente reutilizable -->
    <x-persona-form-modal />
@endsection
