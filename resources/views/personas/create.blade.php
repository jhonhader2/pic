@extends('layouts.app')

@section('title', 'Crear Persona')

@push('styles')
    <style>
        .form-wizard {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .wizard-step {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .wizard-step:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .step-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            color: white;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
        }

        .step-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
        }

        /* Estilos para inputs normales - igual que Select2 */
        .form-control:not(.select2) {
            border-radius: 12px !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px) !important;
            min-height: 58px !important;
            transition: all 0.3s ease !important;
            padding: 15px !important;
            font-size: 1rem !important;
            font-weight: 500 !important;
            color: #495057 !important;
        }

        .form-control:not(.select2):focus {
            border-color: #ff6b6b !important;
            box-shadow: 0 0 20px rgba(255, 107, 107, 0.3) !important;
            background: rgba(255, 255, 255, 1) !important;
            outline: none !important;
        }

        .form-control:not(.select2)::placeholder {
            color: #6c757d !important;
            font-style: italic !important;
        }

        /* Labels consistentes */
        .form-label {
            color: white !important;
            font-weight: 600 !important;
            margin-bottom: 0.75rem !important;
            font-size: 0.95rem !important;
        }

        /* Ocultar floating labels para que todos los campos se vean iguales */
        .form-floating>label {
            display: none !important;
        }

        .form-floating {
            margin-bottom: 1rem;
        }

        .form-floating>.form-control {
            border-radius: 12px !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px) !important;
            min-height: 58px !important;
            transition: all 0.3s ease !important;
            padding: 15px !important;
            font-size: 1rem !important;
            font-weight: 500 !important;
            color: #495057 !important;
        }

        .form-floating>.form-control:focus {
            border-color: #ff6b6b !important;
            box-shadow: 0 0 20px rgba(255, 107, 107, 0.3) !important;
            background: rgba(255, 255, 255, 1) !important;
            outline: none !important;
        }

        /* Estilos para Select2 */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single {
            border-radius: 12px !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px) !important;
            min-height: 58px !important;
            transition: all 0.3s ease !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 15px !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: #495057 !important;
            font-size: 1rem !important;
            font-weight: 500 !important;
            line-height: 1.5 !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder {
            color: #6c757d !important;
            font-style: italic !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: 54px !important;
            right: 15px !important;
            top: 2px !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow b {
            border-color: #6c757d transparent transparent transparent !important;
            border-width: 6px 6px 0 6px !important;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection--single,
        .select2-container--bootstrap-5.select2-container--open .select2-selection--single {
            border-color: #ff6b6b !important;
            box-shadow: 0 0 20px rgba(255, 107, 107, 0.3) !important;
            background: rgba(255, 255, 255, 1) !important;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection--single .select2-selection__arrow b,
        .select2-container--bootstrap-5.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: #ff6b6b transparent transparent transparent !important;
        }

        /* Dropdown del Select2 */
        .select2-dropdown {
            border-radius: 12px !important;
            border: 2px solid rgba(255, 107, 107, 0.3) !important;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(15px) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
            margin-top: 5px !important;
        }

        .select2-dropdown .select2-search {
            padding: 10px !important;
        }

        .select2-dropdown .select2-search .select2-search__field {
            border-radius: 8px !important;
            border: 1px solid rgba(255, 107, 107, 0.3) !important;
            background: rgba(255, 255, 255, 0.9) !important;
            padding: 8px 12px !important;
        }

        .select2-dropdown .select2-results>.select2-results__options {
            max-height: 200px !important;
            padding: 5px !important;
        }

        .select2-dropdown .select2-results__option {
            padding: 10px 15px !important;
            border-radius: 8px !important;
            margin: 2px 0 !important;
            transition: all 0.2s ease !important;
        }

        .select2-dropdown .select2-results__option--highlighted {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24) !important;
            color: white !important;
        }

        .select2-dropdown .select2-results__option[aria-selected="true"] {
            background: rgba(255, 107, 107, 0.1) !important;
            color: #ff6b6b !important;
            font-weight: 600 !important;
        }

        .radio-group {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .radio-item {
            position: relative;
        }

        .radio-item input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .radio-item label {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .radio-item input[type="radio"]:checked+label {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border-color: #ff6b6b;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
            transform: translateY(-2px);
        }

        .radio-item label::before {
            content: '';
            width: 20px;
            height: 20px;
            border: 2px solid currentColor;
            border-radius: 50%;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .radio-item input[type="radio"]:checked+label::before {
            background: white;
            border-color: white;
        }

        .action-buttons {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-primary-custom {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border: none;
            padding: 1rem 3rem;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
        }

        .btn-secondary-custom {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 1rem 2rem;
            border-radius: 50px;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }

        .conditional-fields {
            margin-top: 1rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-title {
            text-align: center;
            color: white;
            margin-bottom: 2rem;
        }

        .page-title h1 {
            font-size: 3rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .page-title p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin: 0.5rem 0 0 0;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 2rem 0;">
        <div class="container">
            <div class="page-title">
                <h1><i class="fas fa-user-plus me-3"></i>Crear Nueva Persona</h1>
                <p>Complete la información para registrar una nueva persona en el sistema</p>
            </div>

            <x-session-alerts />
            <x-validation-errors />

            <form action="{{ route('personas.store') }}" method="POST" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @csrf

                <!-- Paso 1: Información Personal -->
                <div class="wizard-step">
                    <div class="step-header">
                        <div class="step-icon">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <h3 class="step-title">Información Personal</h3>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="tipo_documento" class="form-label text-white fw-semibold">
                                    <i class="fas fa-id-card me-2"></i>Tipo de Documento *
                                </label>
                                <select class="form-control select2" name="tipo_documento" id="tipo_documento_select"
                                    required>
                                    <option value="">Seleccione...</option>
                                    @foreach (App\Helpers\TipoDocumentoHelper::getOpciones() as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('tipo_documento') == $value ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_documento')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="numero_documento_input" class="form-label">
                                    <i class="fas fa-id-badge me-2"></i>Número de Documento *
                                </label>
                                <input type="text" class="form-control" id="numero_documento_input"
                                    name="numero_documento" value="{{ old('numero_documento') }}"
                                    placeholder="Ingrese el número de documento" required>
                                @error('numero_documento')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="primer_nombre_input" class="form-label">
                                    <i class="fas fa-user me-2"></i>Primer Nombre *
                                </label>
                                <input type="text" class="form-control" id="primer_nombre_input" name="primer_nombre"
                                    value="{{ old('primer_nombre') }}" placeholder="Ingrese el primer nombre" required>
                                @error('primer_nombre')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="segundo_nombre_input" class="form-label">
                                    <i class="fas fa-user me-2"></i>Segundo Nombre
                                </label>
                                <input type="text" class="form-control" id="segundo_nombre_input" name="segundo_nombre"
                                    value="{{ old('segundo_nombre') }}" placeholder="Ingrese el segundo nombre (opcional)">
                                @error('segundo_nombre')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="primer_apellido_input" class="form-label">
                                <i class="fas fa-user me-2"></i>Primer Apellido *
                            </label>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="primer_apellido_input" name="primer_apellido"
                                    value="{{ old('primer_apellido') }}" placeholder="Ingrese el primer apellido" required>
                                <label for="primer_apellido_input"><i class="fas fa-user me-2"></i>Primer Apellido *</label>
                                @error('primer_apellido')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="segundo_apellido_input"
                                    name="segundo_apellido" value="{{ old('segundo_apellido') }}"
                                    placeholder="Segundo apellido">
                                <label for="segundo_apellido_input"><i class="fas fa-user me-2"></i>Segundo Apellido</label>
                                @error('segundo_apellido')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="fecha_nacimiento_input"
                                    name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
                                <label for="fecha_nacimiento_input"><i class="fas fa-calendar me-2"></i>Fecha de Nacimiento
                                    *</label>
                                @error('fecha_nacimiento')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="sexo" class="form-label text-white fw-semibold">
                                    <i class="fas fa-venus-mars me-2"></i>Sexo *
                                </label>
                                <select class="form-control select2" name="sexo" id="sexo_select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach (App\Helpers\SexoHelper::getOpciones() as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('sexo') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('sexo')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="identidad_genero" class="form-label text-white fw-semibold">
                                    <i class="fas fa-transgender me-2"></i>Identidad de Género *
                                </label>
                                <select class="form-control select2" name="identidad_genero" id="identidad_genero_select"
                                    required>
                                    <option value="">Seleccione...</option>
                                    @foreach (App\Helpers\IdentidadGeneroHelper::getOpciones() as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('identidad_genero') == $value ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('identidad_genero')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="estado_civil" class="form-label text-white fw-semibold">
                                    <i class="fas fa-heart me-2"></i>Estado Civil *
                                </label>
                                <select class="form-control select2" name="estado_civil" id="estado_civil_select"
                                    required>
                                    <option value="">Seleccione...</option>
                                    @foreach (App\Helpers\EstadoCivilHelper::getOpciones() as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('estado_civil') == $value ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado_civil')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ocupacion" class="form-label text-white fw-semibold">
                                    <i class="fas fa-briefcase me-2"></i>Ocupación *
                                </label>
                                <select class="form-control select2" name="ocupacion" id="ocupacion_select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach (App\Helpers\OcupacionHelper::getOpciones() as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('ocupacion') == $value ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ocupacion')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paso 2: Información de Contacto -->
                <div class="wizard-step">
                    <div class="step-header">
                        <div class="step-icon">
                            <i class="fas fa-phone text-white"></i>
                        </div>
                        <h3 class="step-title">Información de Contacto</h3>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="telefono_input" name="telefono"
                                    value="{{ old('telefono') }}" placeholder="Teléfono fijo">
                                <label for="telefono_input"><i class="fas fa-phone me-2"></i>Teléfono Fijo</label>
                                @error('telefono')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="celular_input" name="celular"
                                    value="{{ old('celular') }}" placeholder="Número de celular" required>
                                <label for="celular_input"><i class="fas fa-mobile-alt me-2"></i>Número de Celular
                                    *</label>
                                @error('celular')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="barrio" class="form-label text-white fw-semibold">
                                    <i class="fas fa-map-marker-alt me-2"></i>Barrio *
                                </label>
                                <select class="form-control select2" name="barrio" id="barrio_select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach (App\Helpers\BarrioHelper::getOpciones() as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('barrio') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('barrio')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="direccion_input" name="direccion"
                                    value="{{ old('direccion') }}" placeholder="Dirección completa" required>
                                <label for="direccion_input"><i class="fas fa-home me-2"></i>Dirección *</label>
                                @error('direccion')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paso 3: Información de Salud -->
                <div class="wizard-step">
                    <div class="step-header">
                        <div class="step-icon">
                            <i class="fas fa-heartbeat text-white"></i>
                        </div>
                        <h3 class="step-title">Información de Salud</h3>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="tipo_sangre" class="form-label text-white fw-semibold">
                                    <i class="fas fa-tint me-2"></i>Tipo de Sangre *
                                </label>
                                <select class="form-control select2" name="tipo_sangre" id="tipo_sangre_select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach (App\Helpers\TipoSangreHelper::getOpciones() as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('tipo_sangre') == $value ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_sangre')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="factor_rh" class="form-label text-white fw-semibold">
                                    <i class="fas fa-tint me-2"></i>Factor RH *
                                </label>
                                <select class="form-control select2" name="factor_rh" id="factor_rh_select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach (App\Helpers\FactorRhHelper::getOpciones() as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('factor_rh') == $value ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('factor_rh')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-white fw-semibold mb-3">
                                    <i class="fas fa-hospital me-2"></i>¿Tiene Afiliación a Salud? *
                                </label>
                                <div class="radio-group">
                                    <div class="radio-item">
                                        <input type="radio" name="afiliacion_salud" id="afiliacion_salud_si"
                                            value="1" {{ old('afiliacion_salud') == '1' ? 'checked' : '' }}>
                                        <label for="afiliacion_salud_si">Sí</label>
                                    </div>
                                    <div class="radio-item">
                                        <input type="radio" name="afiliacion_salud" id="afiliacion_salud_no"
                                            value="0" {{ old('afiliacion_salud') == '0' ? 'checked' : '' }}>
                                        <label for="afiliacion_salud_no">No</label>
                                    </div>
                                </div>
                                @error('afiliacion_salud')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-white fw-semibold mb-3">
                                    <i class="fas fa-wheelchair me-2"></i>¿Presenta Discapacidad? *
                                </label>
                                <div class="radio-group">
                                    <div class="radio-item">
                                        <input type="radio" name="discapacidad" id="discapacidad_si" value="1"
                                            {{ old('discapacidad') == '1' ? 'checked' : '' }}>
                                        <label for="discapacidad_si">Sí</label>
                                    </div>
                                    <div class="radio-item">
                                        <input type="radio" name="discapacidad" id="discapacidad_no" value="0"
                                            {{ old('discapacidad') == '0' ? 'checked' : '' }}>
                                        <label for="discapacidad_no">No</label>
                                    </div>
                                </div>
                                @error('discapacidad')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Campos condicionales para afiliación salud -->
                    <div class="conditional-fields" id="campos_afiliacion_salud"
                        style="display: {{ old('afiliacion_salud') == '1' ? 'block' : 'none' }};">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo_afiliacion_salud" class="form-label text-white fw-semibold">
                                        <i class="fas fa-medical-file me-2"></i>Tipo de Afiliación
                                    </label>
                                    <select class="form-control select2" name="tipo_afiliacion_salud"
                                        id="tipo_afiliacion_salud_select">
                                        <option value="">Seleccione...</option>
                                        @foreach (App\Helpers\TipoAfiliacionSaludHelper::getOpciones() as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ old('tipo_afiliacion_salud') == $value ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo_afiliacion_salud')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="eps" class="form-label text-white fw-semibold">
                                        <i class="fas fa-hospital me-2"></i>EPS
                                    </label>
                                    <select class="form-control select2" name="eps" id="eps_select">
                                        <option value="">Seleccione...</option>
                                        @foreach (App\Helpers\EpsHelper::getOpciones() as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ old('eps') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('eps')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campos condicionales para discapacidad -->
                    <div class="conditional-fields" id="campos_discapacidad"
                        style="display: {{ old('discapacidad') == '1' ? 'block' : 'none' }};">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo_discapacidad" class="form-label text-white fw-semibold">
                                        <i class="fas fa-wheelchair me-2"></i>Tipo de Discapacidad
                                    </label>
                                    <select class="form-control select2" name="tipo_discapacidad"
                                        id="tipo_discapacidad_select">
                                        <option value="">Seleccione...</option>
                                        @foreach (App\Helpers\TipoDiscapacidadHelper::getOpciones() as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ old('tipo_discapacidad') == $value ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo_discapacidad')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-white fw-semibold mb-3">
                                        <i class="fas fa-hand-holding-medical me-2"></i>¿Recibe Atención Integral?
                                    </label>
                                    <div class="radio-group">
                                        <div class="radio-item">
                                            <input type="radio" name="atencion_integral_discapacidad"
                                                id="atencion_integral_si" value="1"
                                                {{ old('atencion_integral_discapacidad') == '1' ? 'checked' : '' }}>
                                            <label for="atencion_integral_si">Sí</label>
                                        </div>
                                        <div class="radio-item">
                                            <input type="radio" name="atencion_integral_discapacidad"
                                                id="atencion_integral_no" value="0"
                                                {{ old('atencion_integral_discapacidad') == '0' ? 'checked' : '' }}>
                                            <label for="atencion_integral_no">No</label>
                                        </div>
                                    </div>
                                    @error('atencion_integral_discapacidad')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paso 4: Información Étnica -->
                <div class="wizard-step">
                    <div class="step-header">
                        <div class="step-icon">
                            <i class="fas fa-globe text-white"></i>
                        </div>
                        <h3 class="step-title">Información Étnica</h3>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pertenencia_etnica" class="form-label text-white fw-semibold">
                                    <i class="fas fa-users me-2"></i>Pertenencia Étnica *
                                </label>
                                <select class="form-control select2" name="pertenencia_etnica"
                                    id="pertenencia_etnica_select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach (App\Helpers\PertenenciaEtnicaHelper::getOpciones() as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('pertenencia_etnica') == $value ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('pertenencia_etnica')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nombre_etnia_input" name="nombre_etnia"
                                    value="{{ old('nombre_etnia') }}" placeholder="Nombre de la etnia">
                                <label for="nombre_etnia_input"><i class="fas fa-users me-2"></i>Nombre de la Etnia
                                    (Opcional)</label>
                                @error('nombre_etnia')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="action-buttons">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('personas.index') }}" class="btn btn-secondary-custom">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Guardar Persona
                        </button>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('personas.index') }}" class="text-white text-decoration-none opacity-75">
                            <i class="fas fa-arrow-left me-2"></i>Volver al listado de personas
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <x-date-validation-script />

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
            // Crear labels externos para todos los campos floating
            const floatingContainers = document.querySelectorAll('.form-floating');
            floatingContainers.forEach(container => {
                const label = container.querySelector('label');
                const input = container.querySelector('input');
                if (label && input && !container.previousElementSibling?.classList.contains('form-label')) {
                    const externalLabel = document.createElement('label');
                    externalLabel.className = 'form-label';
                    externalLabel.setAttribute('for', input.id);
                    externalLabel.innerHTML = label.innerHTML;
                    container.parentNode.insertBefore(externalLabel, container);
                }
            });

            // Inicializar Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'Seleccione...',
                allowClear: false,
                width: '100%',
                dropdownParent: $('body'),
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });
            // Manejo de campos condicionales para afiliación de salud
            const afiliacionSaludRadios = document.querySelectorAll('input[name="afiliacion_salud"]');
            const camposAfiliacionSalud = document.getElementById('campos_afiliacion_salud');

            afiliacionSaludRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === '1') {
                        camposAfiliacionSalud.style.display = 'block';
                        camposAfiliacionSalud.style.animation = 'slideDown 0.3s ease';
                    } else {
                        camposAfiliacionSalud.style.display = 'none';
                        // Limpiar valores cuando se ocultan
                        $('#tipo_afiliacion_salud_select').val('').trigger('change');
                        $('#eps_select').val('').trigger('change');
                    }
                });
            });

            // Manejo de campos condicionales para discapacidad
            const discapacidadRadios = document.querySelectorAll('input[name="discapacidad"]');
            const camposDiscapacidad = document.getElementById('campos_discapacidad');

            discapacidadRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === '1') {
                        camposDiscapacidad.style.display = 'block';
                        camposDiscapacidad.style.animation = 'slideDown 0.3s ease';
                    } else {
                        camposDiscapacidad.style.display = 'none';
                        // Limpiar valores cuando se ocultan
                        $('#tipo_discapacidad_select').val('').trigger('change');
                        document.querySelectorAll('input[name="atencion_integral_discapacidad"]')
                            .forEach(r => r.checked = false);
                    }
                });
            });

            // Mostrar campos condicionales si ya estaban seleccionados (old values)
            const afiliacionSaludChecked = document.querySelector('input[name="afiliacion_salud"]:checked');
            if (afiliacionSaludChecked && afiliacionSaludChecked.value === '1') {
                camposAfiliacionSalud.style.display = 'block';
            }

            const discapacidadChecked = document.querySelector('input[name="discapacidad"]:checked');
            if (discapacidadChecked && discapacidadChecked.value === '1') {
                camposDiscapacidad.style.display = 'block';
            }

            // Validación de formulario
            const form = document.querySelector('.needs-validation');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    </script>
@endsection
