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
                                        <x-form-input name="primer_nombre" label="Primer Nombre" placeholder="Ejemplo: Juan"
                                            required icon="user" autofocus />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="segundo_nombre" label="Segundo Nombre" placeholder="Opcional"
                                            icon="user" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="primer_apellido" label="Primer Apellido"
                                            placeholder="Ejemplo: Pérez" required icon="user" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="segundo_apellido" label="Segundo Apellido"
                                            placeholder="Opcional" icon="user" />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <x-form-input name="email" label="Correo Electrónico" type="email"
                                            placeholder="ejemplo@correo.com" required icon="envelope" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="password" label="Contraseña" type="password"
                                            placeholder="Mínimo 8 caracteres" required icon="lock" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="password_confirmation" label="Confirmar Contraseña"
                                            type="password" placeholder="Repite la contraseña" required icon="lock" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="documentation">
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="tipo_documento" label="Tipo de Documento"
                                            placeholder="Cédula, Pasaporte, etc." required icon="id-card" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="numero_documento" label="Número de Documento"
                                            placeholder="Número de documento" required icon="id-card" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="personal-details">
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="fecha_nacimiento" label="Fecha de Nacimiento" type="date"
                                            required icon="calendar-alt" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="sexo" label="Sexo" :options="['1' => 'Masculino', '0' => 'Femenino']" required
                                            icon="venus-mars" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="identidad_genero" label="Identidad de Género"
                                            placeholder="Identidad de género" icon="genderless" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="estado_civil" label="Estado Civil"
                                            placeholder="Soltero, Casado, etc." required icon="heart" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="contact">
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="telefono" label="Teléfono" placeholder="Número de teléfono"
                                            icon="phone" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="celular" label="Celular" placeholder="Número de celular"
                                            required icon="mobile-alt" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="health">
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="tipo_sangre" label="Tipo de Sangre"
                                            placeholder="A+, O-, etc." required icon="tint" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="factor_rh" label="Factor RH" placeholder="Positivo, Negativo"
                                            required icon="tint" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="afiliacion_salud" label="Afiliación Salud" :options="['1' => 'Sí', '0' => 'No']"
                                            required icon="hospital" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="tipo_afiliacion_salud" label="Tipo de Afiliación Salud"
                                            placeholder="Contributivo, Subsidiado, etc." icon="hospital" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="eps" label="EPS" placeholder="Nombre de la EPS"
                                            icon="hospital" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="disability">
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="discapacidad" label="Discapacidad" :options="['1' => 'Sí', '0' => 'No']" required
                                            icon="wheelchair" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="tipo_discapacidad" label="Tipo de Discapacidad"
                                            placeholder="Tipo de discapacidad" icon="wheelchair" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-select name="atencion_integral_discapacidad"
                                            label="Atención Integral Discapacidad" :options="['1' => 'Sí', '0' => 'No']" required
                                            icon="wheelchair" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="cultural">
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="pertenencia_etnica" label="Pertenencia Étnica"
                                            placeholder="Pertenencia étnica" required icon="users" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="nombre_etnia" label="Nombre Etnia"
                                            placeholder="Nombre de la etnia" icon="users" />
                                    </div>
                                </x-form-tab-content>

                                <x-form-tab-content id="location">
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="barrio" label="Barrio" placeholder="Barrio" required
                                            icon="map-marker-alt" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-form-input name="direccion" label="Dirección" placeholder="Dirección" required
                                            icon="map-marker-alt" />
                                    </div>
                                </x-form-tab-content>
                            </div>

                            <!-- Botón de Envío -->
                            <div class="d-grid mb-3 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Crear Usuario
                                </button>
                            </div>

                            <!-- Enlaces -->
                            <div class="text-center">
                                <a href="{{ route('dashboard') }}" class="text-decoration-none">
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
            });
        </script>
    @endpush
@endsection
