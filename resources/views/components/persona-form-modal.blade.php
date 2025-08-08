@props(['modalId' => 'crearPersonaModal', 'formId' => 'crearPersonaForm', 'title' => 'Crear Nueva Persona'])

<!-- Modal para Crear Nueva Persona -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="{{ $modalId }}Label">
                    <i class="fas fa-user-plus me-2"></i>
                    {{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Información:</strong> Complete todos los datos de la nueva persona. Los campos marcados con
                    * son obligatorios.
                </div>

                <form id="{{ $formId }}" method="POST" action="{{ route('personas.store') }}" novalidate>
                    @csrf

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs mb-4" id="personaTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab"
                                data-bs-target="#personal" type="button" role="tab">
                                <i class="fas fa-user me-1"></i>Personal
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="documento-tab" data-bs-toggle="tab" data-bs-target="#documento"
                                type="button" role="tab">
                                <i class="fas fa-id-card me-1"></i>Documento
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="detalles-tab" data-bs-toggle="tab" data-bs-target="#detalles"
                                type="button" role="tab">
                                <i class="fas fa-info-circle me-1"></i>Detalles
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contacto-tab" data-bs-toggle="tab" data-bs-target="#contacto"
                                type="button" role="tab">
                                <i class="fas fa-phone me-1"></i>Contacto
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="salud-tab" data-bs-toggle="tab" data-bs-target="#salud"
                                type="button" role="tab">
                                <i class="fas fa-heartbeat me-1"></i>Salud
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="discapacidad-tab" data-bs-toggle="tab"
                                data-bs-target="#discapacidad" type="button" role="tab">
                                <i class="fas fa-wheelchair me-1"></i>Discapacidad
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cultural-tab" data-bs-toggle="tab" data-bs-target="#cultural"
                                type="button" role="tab">
                                <i class="fas fa-users me-1"></i>Cultural
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ubicacion-tab" data-bs-toggle="tab" data-bs-target="#ubicacion"
                                type="button" role="tab">
                                <i class="fas fa-map-marker-alt me-1"></i>Ubicación
                            </button>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Información Personal -->
                        <div class="tab-pane fade show active" id="personal" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-form-input name="primer_nombre" label="Primer Nombre" placeholder="Juan"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <x-form-input name="segundo_nombre" label="Segundo Nombre"
                                        placeholder="Opcional" />
                                </div>
                                <div class="col-md-6">
                                    <x-form-input name="primer_apellido" label="Primer Apellido" placeholder="Pérez"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <x-form-input name="segundo_apellido" label="Segundo Apellido"
                                        placeholder="Opcional" />
                                </div>
                                <div class="col-md-6">
                                    <x-form-input name="fecha_nacimiento" label="Fecha de Nacimiento" type="date"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <x-form-select name="sexo" label="Sexo" :options="\App\Helpers\SexoHelper::getOpciones()" required />
                                </div>
                            </div>
                        </div>

                        <!-- Documentación -->
                        <div class="tab-pane fade" id="documento" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-form-select name="tipo_documento" label="Tipo de Documento" :options="\App\Helpers\TipoDocumentoHelper::getOpciones()"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <x-form-input name="numero_documento" label="Número de Documento"
                                        placeholder="Número de documento" required />
                                </div>
                            </div>
                        </div>

                        <!-- Detalles Personales -->
                        <div class="tab-pane fade" id="detalles" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-form-select name="identidad_genero" label="Identidad de Género"
                                        :options="\App\Helpers\IdentidadGeneroHelper::getOpciones()" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form-select name="estado_civil" label="Estado Civil" :options="\App\Helpers\EstadoCivilHelper::getOpciones()"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <x-form-select name="ocupacion" label="Ocupación" :options="\App\Helpers\OcupacionHelper::getOpciones()" required />
                                </div>
                            </div>
                        </div>

                        <!-- Contacto -->
                        <div class="tab-pane fade" id="contacto" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-form-input name="telefono" label="Teléfono"
                                        placeholder="Número de teléfono" />
                                </div>
                                <div class="col-md-6">
                                    <x-form-input name="celular" label="Celular" placeholder="Número de celular"
                                        required />
                                </div>
                            </div>
                        </div>

                        <!-- Salud -->
                        <div class="tab-pane fade" id="salud" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-form-select name="tipo_sangre" label="Tipo de Sangre" :options="\App\Helpers\TipoSangreHelper::getOpciones()"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <x-form-select name="factor_rh" label="Factor RH" :options="\App\Helpers\FactorRhHelper::getOpciones()" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form-select name="afiliacion_salud" label="Afiliación Salud" :options="['1' => 'Sí', '0' => 'No']"
                                        required />
                                </div>
                                <div class="col-md-6" id="tipo_afiliacion_container">
                                    <x-form-select name="tipo_afiliacion_salud" label="Tipo de Afiliación Salud"
                                        :options="\App\Helpers\TipoAfiliacionSaludHelper::getOpciones()" />
                                </div>
                                <div class="col-md-6" id="eps_container">
                                    <x-form-select name="eps" label="EPS" :options="\App\Helpers\EpsHelper::getOpciones()" />
                                </div>
                            </div>
                        </div>

                        <!-- Discapacidad -->
                        <div class="tab-pane fade" id="discapacidad" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-form-select name="discapacidad" label="¿Presenta Discapacidad?"
                                        :options="['1' => 'Sí', '0' => 'No']" required />
                                </div>
                                <div class="col-md-6" id="tipo_discapacidad_container">
                                    <x-form-select name="tipo_discapacidad" label="Tipo de Discapacidad"
                                        :options="\App\Helpers\TipoDiscapacidadHelper::getOpciones()" />
                                </div>
                                <div class="col-md-6" id="atencion_integral_container">
                                    <x-form-select name="atencion_integral_discapacidad"
                                        label="Atención Integral Discapacidad" :options="['1' => 'Sí', '0' => 'No']" />
                                </div>
                            </div>
                        </div>

                        <!-- Cultural -->
                        <div class="tab-pane fade" id="cultural" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-form-select name="pertenencia_etnica" label="Pertenencia Étnica"
                                        :options="\App\Helpers\PertenenciaEtnicaHelper::getOpciones()" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form-input name="nombre_etnia" label="Nombre Etnia"
                                        placeholder="Nombre de la etnia" />
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div class="tab-pane fade" id="ubicacion" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-form-select name="barrio" label="Barrio" :options="\App\Helpers\BarrioHelper::getOpciones()" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form-input name="direccion" label="Dirección" placeholder="Dirección"
                                        required />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="guardarPersonaBtn">
                    <i class="fas fa-save me-1"></i>
                    Guardar Persona
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const guardarPersonaBtn = document.getElementById('guardarPersonaBtn');
            const crearPersonaForm = document.getElementById('{{ $formId }}');
            const modal = document.getElementById('{{ $modalId }}');
            const modalInstance = new bootstrap.Modal(modal);

            // Función para manejar la visibilidad de campos de afiliación
            function toggleAfiliacionFields() {
                const afiliacionSelect = document.getElementById('afiliacion_salud');
                const tipoAfiliacionContainer = document.getElementById('tipo_afiliacion_container');
                const epsContainer = document.getElementById('eps_container');

                if (afiliacionSelect && tipoAfiliacionContainer && epsContainer) {
                    if (afiliacionSelect.value === '0') {
                        tipoAfiliacionContainer.style.display = 'none';
                        epsContainer.style.display = 'none';

                        const tipoAfiliacionInput = tipoAfiliacionContainer.querySelector('select');
                        const epsInput = epsContainer.querySelector('select');
                        if (tipoAfiliacionInput) tipoAfiliacionInput.value = '';
                        if (epsInput) epsInput.value = '';
                    } else {
                        tipoAfiliacionContainer.style.display = 'block';
                        epsContainer.style.display = 'block';
                    }
                }
            }

            // Función para manejar la visibilidad de campos de discapacidad
            function toggleDiscapacidadFields() {
                const discapacidadSelect = document.getElementById('discapacidad');
                const tipoDiscapacidadContainer = document.getElementById('tipo_discapacidad_container');
                const atencionIntegralContainer = document.getElementById('atencion_integral_container');
                const tipoDiscapacidadSelect = document.getElementById('tipo_discapacidad');
                const atencionIntegralSelect = document.getElementById('atencion_integral_discapacidad');

                if (discapacidadSelect && tipoDiscapacidadContainer && atencionIntegralContainer) {
                    if (discapacidadSelect.value === '0') {
                        tipoDiscapacidadContainer.style.display = 'none';
                        atencionIntegralContainer.style.display = 'none';

                        if (tipoDiscapacidadSelect) {
                            tipoDiscapacidadSelect.value = '';
                            tipoDiscapacidadSelect.removeAttribute('required');
                        }
                        if (atencionIntegralSelect) {
                            atencionIntegralSelect.value = '';
                            atencionIntegralSelect.removeAttribute('required');
                        }
                    } else {
                        tipoDiscapacidadContainer.style.display = 'block';
                        atencionIntegralContainer.style.display = 'block';

                        if (tipoDiscapacidadSelect) tipoDiscapacidadSelect.setAttribute('required', 'required');
                        if (atencionIntegralSelect) atencionIntegralSelect.setAttribute('required', 'required');
                    }
                }
            }

            // Event listeners para campos condicionales
            const afiliacionSelect = document.getElementById('afiliacion_salud');
            if (afiliacionSelect) {
                afiliacionSelect.addEventListener('change', toggleAfiliacionFields);
                toggleAfiliacionFields(); // Ejecutar al cargar
            }

            const discapacidadSelect = document.getElementById('discapacidad');
            if (discapacidadSelect) {
                discapacidadSelect.addEventListener('change', toggleDiscapacidadFields);
                toggleDiscapacidadFields(); // Ejecutar al cargar
            }

            guardarPersonaBtn.addEventListener('click', function() {
                // Validar formulario
                const formData = new FormData(crearPersonaForm);
                let isValid = true;
                let firstInvalidField = null;

                // Validar campos requeridos
                const requiredFields = crearPersonaForm.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    // Validación condicional para campos de discapacidad
                    if (field.name === 'atencion_integral_discapacidad' || field.name ===
                        'tipo_discapacidad') {
                        const discapacidadSelect = document.getElementById('discapacidad');
                        if (discapacidadSelect && discapacidadSelect.value === '0') {
                            field.classList.remove('is-invalid');
                            const errorDiv = field.parentNode.querySelector('.invalid-feedback');
                            if (errorDiv) errorDiv.remove();
                            return;
                        }
                    }

                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    // Mostrar el primer campo inválido
                    if (firstInvalidField) {
                        const tabPane = firstInvalidField.closest('.tab-pane');
                        if (tabPane) {
                            const tabId = tabPane.id;
                            const tabButton = document.querySelector(`[data-bs-target="#${tabId}"]`);
                            if (tabButton) {
                                const tab = new bootstrap.Tab(tabButton);
                                tab.show();
                            }
                        }
                        firstInvalidField.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstInvalidField.focus();
                    }
                    return;
                }

                // Enviar formulario via AJAX
                guardarPersonaBtn.disabled = true;
                guardarPersonaBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';

                fetch(crearPersonaForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Disparar evento personalizado para que el componente padre maneje la actualización
                            const event = new CustomEvent('personaCreated', {
                                detail: {
                                    persona: data.persona
                                }
                            });
                            document.dispatchEvent(event);

                            // Cerrar modal y mostrar mensaje
                            modalInstance.hide();
                            crearPersonaForm.reset();

                            // Mostrar notificación de éxito
                            showNotification('Persona creada exitosamente', 'success');
                        } else {
                            showNotification('Error al crear la persona: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error al crear la persona', 'error');
                    })
                    .finally(() => {
                        guardarPersonaBtn.disabled = false;
                        guardarPersonaBtn.innerHTML = '<i class="fas fa-save me-1"></i>Guardar Persona';
                    });
            });

            // Función para mostrar notificaciones
            function showNotification(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

                // Insertar al inicio del contenido principal
                const container = document.querySelector('.container');
                container.insertAdjacentHTML('afterbegin', alertHtml);

                // Auto-remover después de 5 segundos
                setTimeout(() => {
                    const alert = container.querySelector('.alert');
                    if (alert) {
                        alert.remove();
                    }
                }, 5000);
            }

            // Limpiar formulario cuando se cierre el modal
            modal.addEventListener('hidden.bs.modal', function() {
                crearPersonaForm.reset();
                crearPersonaForm.querySelectorAll('.is-invalid').forEach(field => {
                    field.classList.remove('is-invalid');
                });
                toggleAfiliacionFields();
                toggleDiscapacidadFields();
            });
        });
    </script>
@endpush
