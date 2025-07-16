@extends('layouts.app')

@section('title', 'Crear Usuario - PIC')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="fas fa-user-plus text-primary"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-1" style="color: #0066CC;">Crear Nuevo Usuario</h1>
                        <p class="text-muted mb-0">Registra un nuevo usuario en el sistema PIC</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card border-0 shadow-lg rounded-3">
                    <div class="card-body p-5">
                        <!-- Mensajes de error de validación -->
                        <x-validation-errors :errors="$errors" />

                        <!-- Mensaje de éxito -->
                        @if (session('success'))
                            <x-alert type="success">
                                {{ session('success') }}
                            </x-alert>
                        @endif

                        <!-- Mensaje de error general -->
                        @if (session('error'))
                            <x-alert type="error">
                                {{ session('error') }}
                            </x-alert>
                        @endif

                        <!-- Mensaje informativo cuando hay errores -->
                        @if ($errors->any())
                            <x-alert type="info" title="Información">
                                Por favor, revisa los errores marcados en rojo y completa todos los campos requeridos.
                                Los campos con errores están resaltados y las pestañas correspondientes se activarán
                                automáticamente.
                            </x-alert>
                        @endif

                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                <i class="fas fa-user-plus text-primary" style="font-size: 32px;"></i>
                            </div>
                            <h2 class="fw-bold" style="color: #0056b3;">Formulario de Registro</h2>
                            <p class="text-muted">Complete la información del nuevo usuario</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                            @csrf

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs mb-4" id="registerTab" role="tablist">
                                <x-form-tab id="personal-info" title="Información Personal" :active="true" />
                                <x-form-tab id="documentation" title="Documentación" />
                                <x-form-tab id="personal-details" title="Detalles Personales" />
                                <x-form-tab id="contact" title="Contacto" />
                                <x-form-tab id="health" title="Salud" />
                                <x-form-tab id="disability" title="Discapacidad" />
                                <x-form-tab id="cultural" title="Cultural" />
                                <x-form-tab id="location" title="Ubicación" />
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <x-form-tab-content id="personal-info" :active="true">
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="primer_nombre" id="primer_nombre" label="Primer Nombre"
                                            placeholder="Juan" required icon="user" autofocus />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="segundo_nombre" id="segundo_nombre" label="Segundo Nombre"
                                            placeholder="Opcional" icon="user" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="primer_apellido" id="primer_apellido" label="Primer Apellido"
                                            placeholder="Pérez" required icon="user" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="segundo_apellido" id="segundo_apellido" label="Segundo Apellido"
                                            placeholder="Opcional" icon="user" />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <x-form-input name="email" id="email" label="Correo Electrónico"
                                            type="email" placeholder="ejemplo@correo.com" required icon="envelope" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="password" id="password" label="Contraseña" type="password"
                                            placeholder="Mínimo 8 caracteres" required icon="lock" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="password_confirmation" id="password_confirmation"
                                            label="Confirmar Contraseña" type="password" placeholder="Repite la contraseña"
                                            required icon="lock" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="documentation">
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="tipo_documento" id="tipo_documento" label="Tipo de Documento"
                                            :options="\App\Helpers\TipoDocumentoHelper::getOpciones()" required icon="id-card" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="numero_documento" id="numero_documento"
                                            label="Número de Documento" placeholder="Número de documento" required
                                            icon="id-card" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="personal-details">
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="fecha_nacimiento" id="fecha_nacimiento"
                                            label="Fecha de Nacimiento" type="date" required icon="calendar-alt" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="sexo" id="sexo" label="Sexo" :options="\App\Helpers\SexoHelper::getOpciones()"
                                            required icon="venus-mars" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="identidad_genero" id="identidad_genero"
                                            label="Identidad de Género" :options="\App\Helpers\IdentidadGeneroHelper::getOpciones()" required icon="genderless" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="estado_civil" id="estado_civil" label="Estado Civil"
                                            :options="\App\Helpers\EstadoCivilHelper::getOpciones()" required icon="heart" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="ocupacion" id="ocupacion" label="Ocupación"
                                            :options="\App\Helpers\OcupacionHelper::getOpciones()" required icon="briefcase" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="contact">
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="telefono" id="telefono" label="Teléfono"
                                            placeholder="Número de teléfono" icon="phone" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="celular" id="celular" label="Celular"
                                            placeholder="Número de celular" required icon="mobile-alt" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="health">
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="tipo_sangre" id="tipo_sangre" label="Tipo de Sangre"
                                            :options="\App\Helpers\TipoSangreHelper::getOpciones()" required icon="tint" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="factor_rh" id="factor_rh" label="Factor RH"
                                            :options="\App\Helpers\FactorRhHelper::getOpciones()" required icon="tint" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="afiliacion_salud" id="afiliacion_salud"
                                            label="Afiliación Salud" :options="['1' => 'Sí', '0' => 'No']" required icon="hospital" />
                                    </div>
                                    <div class="col-md-6 mb-3" id="tipo_afiliacion_container">
                                        <x-form-select name="tipo_afiliacion_salud" id="tipo_afiliacion_salud"
                                            label="Tipo de Afiliación Salud" :options="\App\Helpers\TipoAfiliacionSaludHelper::getOpciones()" icon="hospital" />
                                    </div>
                                    <div class="col-md-6 mb-3" id="eps_container">
                                        <x-form-select name="eps" id="eps" label="EPS" :options="\App\Helpers\EpsHelper::getOpciones()"
                                            icon="hospital" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="disability">
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="discapacidad" id="discapacidad"
                                            label="¿Presenta Discapacidad?" :options="['1' => 'Sí', '0' => 'No']" required
                                            icon="wheelchair" />
                                    </div>
                                    <div class="col-md-6 mb-3" id="tipo_discapacidad_container">
                                        <x-form-select name="tipo_discapacidad" id="tipo_discapacidad"
                                            label="Tipo de Discapacidad" :options="\App\Helpers\TipoDiscapacidadHelper::getOpciones()" icon="wheelchair" />
                                    </div>
                                    <div class="col-md-6 mb-3" id="atencion_integral_container">
                                        <x-form-select name="atencion_integral_discapacidad"
                                            id="atencion_integral_discapacidad" label="Atención Integral Discapacidad"
                                            :options="['1' => 'Sí', '0' => 'No']" icon="wheelchair" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="cultural">
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="pertenencia_etnica" id="pertenencia_etnica"
                                            label="Pertenencia Étnica" :options="\App\Helpers\PertenenciaEtnicaHelper::getOpciones()" required icon="users" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="nombre_etnia" id="nombre_etnia" label="Nombre Etnia"
                                            placeholder="Nombre de la etnia" icon="users" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="location">
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="barrio" id="barrio" label="Barrio" :options="\App\Helpers\BarrioHelper::getOpciones()"
                                            required icon="map-marker-alt" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="direccion" id="direccion" label="Dirección"
                                            placeholder="Dirección" required icon="map-marker-alt" />
                                    </div>
                                </x-form-tab-content>
                            </div>

                            <!-- Botón de Envío -->
                            <div class="d-grid mb-3 mt-4">
                                <button type="submit" id="submit_register" class="btn btn-primary btn-lg rounded-pill">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Crear Usuario
                                </button>
                            </div>

                            <!-- Enlaces -->
                            <div class="text-center">
                                <a href="{{ route('dashboard') }}" id="back_to_dashboard" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Volver al Dashboard
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .hidden-tab {
                display: none;
            }

            /* Fix tab content height to prevent window resizing */
            .tab-content {
                min-height: 400px;
            }

            .tab-pane {
                min-height: 400px;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('registerForm');
                const submitButton = form.querySelector('button[type="submit"]');

                // Manejar el envío del formulario
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validar todos los campos requeridos
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    let firstInvalidField = null;

                    requiredFields.forEach(field => {
                        // Validación condicional para campos de discapacidad
                        if (field.name === 'atencion_integral_discapacidad' || field.name ===
                            'tipo_discapacidad') {
                            const discapacidadSelect = document.getElementById('discapacidad');
                            if (discapacidadSelect && discapacidadSelect.value === '0') {
                                // Si discapacidad es "No", estos campos no son obligatorios
                                field.classList.remove('is-invalid');
                                const errorDiv = field.parentNode.querySelector('.invalid-feedback');
                                if (errorDiv) {
                                    errorDiv.remove();
                                }
                                return; // Saltar la validación para estos campos
                            }
                        }

                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('is-invalid');

                            // Mostrar mensaje de error personalizado
                            let errorDiv = field.parentNode.querySelector('.invalid-feedback');
                            if (!errorDiv) {
                                errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback d-block';
                                field.parentNode.appendChild(errorDiv);
                            }
                            errorDiv.textContent = 'Este campo es obligatorio.';

                            if (!firstInvalidField) {
                                firstInvalidField = field;
                            }
                        } else {
                            field.classList.remove('is-invalid');
                            const errorDiv = field.parentNode.querySelector('.invalid-feedback');
                            if (errorDiv) {
                                errorDiv.remove();
                            }
                        }
                    });

                    // Validar email
                    const emailField = form.querySelector('input[type="email"]');
                    if (emailField && emailField.value) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(emailField.value)) {
                            isValid = false;
                            emailField.classList.add('is-invalid');
                            let errorDiv = emailField.parentNode.querySelector('.invalid-feedback');
                            if (!errorDiv) {
                                errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback d-block';
                                emailField.parentNode.appendChild(errorDiv);
                            }
                            errorDiv.textContent = 'Por favor ingrese un email válido.';
                            if (!firstInvalidField) {
                                firstInvalidField = emailField;
                            }
                        }
                    }

                    // Validar contraseñas
                    const passwordField = form.querySelector('input[name="password"]');
                    const confirmPasswordField = form.querySelector('input[name="password_confirmation"]');
                    if (passwordField && confirmPasswordField) {
                        if (passwordField.value !== confirmPasswordField.value) {
                            isValid = false;
                            confirmPasswordField.classList.add('is-invalid');
                            let errorDiv = confirmPasswordField.parentNode.querySelector('.invalid-feedback');
                            if (!errorDiv) {
                                errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback d-block';
                                confirmPasswordField.parentNode.appendChild(errorDiv);
                            }
                            errorDiv.textContent = 'Las contraseñas no coinciden.';
                            if (!firstInvalidField) {
                                firstInvalidField = confirmPasswordField;
                            }
                        }
                    }

                    if (isValid) {
                        // Si todo es válido, enviar el formulario
                        form.submit();
                    } else {
                        // Mostrar el primer campo inválido
                        if (firstInvalidField) {
                            // Activar la pestaña que contiene el campo
                            const tabPane = firstInvalidField.closest('.tab-pane');
                            if (tabPane) {
                                const tabId = tabPane.id;
                                const tabButton = document.querySelector(`[data-bs-target="#${tabId}"]`);
                                if (tabButton) {
                                    const tab = new bootstrap.Tab(tabButton);
                                    tab.show();
                                }
                            }

                            // Hacer scroll al campo
                            firstInvalidField.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            firstInvalidField.focus();
                        }
                    }
                });

                // Función para manejar la visibilidad de campos de afiliación
                function toggleAfiliacionFields() {
                    const afiliacionSelect = document.getElementById('afiliacion_salud');
                    const tipoAfiliacionContainer = document.getElementById('tipo_afiliacion_container');
                    const epsContainer = document.getElementById('eps_container');

                    if (afiliacionSelect.value === '0') {
                        // Ocultar campos si selecciona "No"
                        tipoAfiliacionContainer.style.display = 'none';
                        epsContainer.style.display = 'none';

                        // Limpiar valores de los campos ocultos
                        const tipoAfiliacionInput = tipoAfiliacionContainer.querySelector('input');
                        const epsInput = epsContainer.querySelector('input');
                        if (tipoAfiliacionInput) tipoAfiliacionInput.value = '';
                        if (epsInput) epsInput.value = '';
                    } else {
                        // Mostrar campos si selecciona "Sí"
                        tipoAfiliacionContainer.style.display = 'block';
                        epsContainer.style.display = 'block';
                    }
                }

                // Función para manejar la visibilidad de campos de discapacidad
                function toggleDiscapacidadFields() {
                    const discapacidadSelect = document.getElementById('discapacidad');
                    const tipoDiscapacidadContainer = document.getElementById('tipo_discapacidad_container');
                    const atencionIntegralContainer = document.getElementById('atencion_integral_container');
                    const tipoDiscapacidadSelect = document.getElementById('tipo_discapacidad');
                    const atencionIntegralSelect = document.getElementById('atencion_integral_discapacidad');

                    if (discapacidadSelect.value === '0') {
                        // Ocultar campos si selecciona "No"
                        tipoDiscapacidadContainer.style.display = 'none';
                        atencionIntegralContainer.style.display = 'none';

                        // Limpiar valores de los campos ocultos
                        if (tipoDiscapacidadSelect) {
                            tipoDiscapacidadSelect.value = '';
                            tipoDiscapacidadSelect.removeAttribute('required');
                        }
                        if (atencionIntegralSelect) {
                            atencionIntegralSelect.value = '';
                            atencionIntegralSelect.removeAttribute('required');
                        }

                        // Limpiar errores de validación
                        if (tipoDiscapacidadSelect) {
                            tipoDiscapacidadSelect.classList.remove('is-invalid');
                            const errorDiv = tipoDiscapacidadSelect.parentNode.querySelector('.invalid-feedback');
                            if (errorDiv) {
                                errorDiv.remove();
                            }
                        }
                        if (atencionIntegralSelect) {
                            atencionIntegralSelect.classList.remove('is-invalid');
                            const errorDiv = atencionIntegralSelect.parentNode.querySelector('.invalid-feedback');
                            if (errorDiv) {
                                errorDiv.remove();
                            }
                        }
                    } else {
                        // Mostrar campos si selecciona "Sí"
                        tipoDiscapacidadContainer.style.display = 'block';
                        atencionIntegralContainer.style.display = 'block';

                        // Hacer obligatorios los campos de discapacidad
                        if (tipoDiscapacidadSelect) {
                            tipoDiscapacidadSelect.setAttribute('required', 'required');
                        }
                        if (atencionIntegralSelect) {
                            atencionIntegralSelect.setAttribute('required', 'required');
                        }
                    }
                }

                // Event listener para el campo de afiliación
                const afiliacionSelect = document.getElementById('afiliacion_salud');
                if (afiliacionSelect) {
                    afiliacionSelect.addEventListener('change', toggleAfiliacionFields);
                    // Ejecutar al cargar la página para establecer el estado inicial
                    toggleAfiliacionFields();
                }

                // Event listener para el campo de discapacidad
                const discapacidadSelect = document.getElementById('discapacidad');
                if (discapacidadSelect) {
                    discapacidadSelect.addEventListener('change', toggleDiscapacidadFields);
                    // Ejecutar al cargar la página para establecer el estado inicial
                    toggleDiscapacidadFields();
                }

                // Limpiar errores cuando el usuario empiece a escribir
                form.querySelectorAll('input, select').forEach(field => {
                    field.addEventListener('input', function() {
                        if (this.classList.contains('is-invalid')) {
                            this.classList.remove('is-invalid');
                            const errorDiv = this.parentNode.querySelector('.invalid-feedback');
                            if (errorDiv) {
                                errorDiv.remove();
                            }
                        }
                    });
                });

                // Mostrar errores del servidor en campos específicos
                @if ($errors->any())
                    @foreach ($errors->getMessages() as $field => $messages)
                        const {{ $field }}Field = form.querySelector('[name="{{ $field }}"]');
                        if ({{ $field }}Field) {
                            {{ $field }}Field.classList.add('is-invalid');
                            let errorDiv = {{ $field }}Field.parentNode.querySelector('.invalid-feedback');
                            if (!errorDiv) {
                                errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback d-block';
                                {{ $field }}Field.parentNode.appendChild(errorDiv);
                            }
                            errorDiv.textContent = '{{ $messages[0] }}';

                            // Activar la pestaña que contiene el campo con error
                            const tabPane = {{ $field }}Field.closest('.tab-pane');
                            if (tabPane) {
                                const tabId = tabPane.id;
                                const tabButton = document.querySelector(`[data-bs-target="#${tabId}"]`);
                                if (tabButton) {
                                    const tab = new bootstrap.Tab(tabButton);
                                    tab.show();
                                }
                            }
                        }
                    @endforeach
                @endif
            });
        </script>
    @endpush
@endsection
