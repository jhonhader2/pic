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
                                    <label class="form-label fw-semibold">Afiliación Salud <span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="afiliacion_salud"
                                                id="afiliacion_salud_si" value="1" required>
                                            <label class="form-check-label" for="afiliacion_salud_si">Sí</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="afiliacion_salud"
                                                id="afiliacion_salud_no" value="0" required>
                                            <label class="form-check-label" for="afiliacion_salud_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="tipo_afiliacion_container" style="display: none;">
                                    <x-form-select name="tipo_afiliacion_salud" label="Tipo de Afiliación Salud"
                                        :options="\App\Helpers\TipoAfiliacionSaludHelper::getOpciones()" />
                                </div>
                                <div class="col-md-6" id="eps_container" style="display: none;">
                                    <x-form-select name="eps" label="EPS" :options="\App\Helpers\EpsHelper::getOpciones()" />
                                </div>
                            </div>
                        </div>

                        <!-- Discapacidad -->
                        <div class="tab-pane fade" id="discapacidad" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">¿Presenta Discapacidad? <span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="discapacidad"
                                                id="discapacidad_si" value="1" required>
                                            <label class="form-check-label" for="discapacidad_si">Sí</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="discapacidad"
                                                id="discapacidad_no" value="0" required>
                                            <label class="form-check-label" for="discapacidad_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="tipo_discapacidad_container" style="display: none;">
                                    <x-form-select name="tipo_discapacidad" label="Tipo de Discapacidad"
                                        :options="\App\Helpers\TipoDiscapacidadHelper::getOpciones()" />
                                </div>
                                <div class="col-md-6" id="atencion_integral_container" style="display: none;">
                                    <label class="form-label fw-semibold">Atención Integral Discapacidad</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                name="atencion_integral_discapacidad" id="atencion_integral_si"
                                                value="1">
                                            <label class="form-check-label" for="atencion_integral_si">Sí</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                name="atencion_integral_discapacidad" id="atencion_integral_no"
                                                value="0">
                                            <label class="form-check-label" for="atencion_integral_no">No</label>
                                        </div>
                                    </div>
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
                                <div class="col-md-6" id="nombre_etnia_container" style="display: none;">
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

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        /* Mejorar apariencia de radio buttons en el modal */
        #{{ $modalId }} .form-check {
            margin-bottom: 0.5rem;
        }

        #{{ $modalId }} .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        #{{ $modalId }} .form-check-input.is-invalid {
            border-color: #dc3545;
        }

        #{{ $modalId }} .form-check-label {
            cursor: pointer;
        }

        /* Asegurar que Select2 se vea bien en el modal */
        .select2-container--bootstrap-5 .select2-selection {
            border: 1px solid #ced4da;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const guardarPersonaBtn = document.getElementById('guardarPersonaBtn');
            const crearPersonaForm = document.getElementById('{{ $formId }}');
            const modal = document.getElementById('{{ $modalId }}');
            const modalInstance = new bootstrap.Modal(modal);

            // Función para manejar la visibilidad de campos de afiliación
            function toggleAfiliacionFields() {
                const afiliacionRadio = document.querySelector('input[name="afiliacion_salud"]:checked');
                const tipoAfiliacionContainer = document.getElementById('tipo_afiliacion_container');
                const epsContainer = document.getElementById('eps_container');

                if (tipoAfiliacionContainer && epsContainer) {
                    const tipoAfiliacionInput = tipoAfiliacionContainer.querySelector('select');
                    const epsInput = epsContainer.querySelector('select');

                    if (!afiliacionRadio || afiliacionRadio.value === '0') {
                        tipoAfiliacionContainer.style.display = 'none';
                        epsContainer.style.display = 'none';

                        if (tipoAfiliacionInput) {
                            tipoAfiliacionInput.value = '';
                            tipoAfiliacionInput.removeAttribute('required');
                        }
                        if (epsInput) {
                            epsInput.value = '';
                            epsInput.removeAttribute('required');
                        }
                    } else if (afiliacionRadio.value === '1') {
                        tipoAfiliacionContainer.style.display = 'block';
                        epsContainer.style.display = 'block';

                        if (tipoAfiliacionInput) tipoAfiliacionInput.setAttribute('required', 'required');
                        if (epsInput) epsInput.setAttribute('required', 'required');
                    }
                }
            }

            // Función para manejar la visibilidad de campos de discapacidad
            function toggleDiscapacidadFields() {
                const discapacidadRadio = document.querySelector('input[name="discapacidad"]:checked');
                const tipoDiscapacidadContainer = document.getElementById('tipo_discapacidad_container');
                const atencionIntegralContainer = document.getElementById('atencion_integral_container');
                const tipoDiscapacidadSelect = document.getElementById('tipo_discapacidad');
                const atencionIntegralRadios = document.querySelectorAll(
                    'input[name="atencion_integral_discapacidad"]');

                if (tipoDiscapacidadContainer && atencionIntegralContainer) {
                    if (!discapacidadRadio || discapacidadRadio.value === '0') {
                        tipoDiscapacidadContainer.style.display = 'none';
                        atencionIntegralContainer.style.display = 'none';

                        if (tipoDiscapacidadSelect) {
                            tipoDiscapacidadSelect.value = '';
                            tipoDiscapacidadSelect.removeAttribute('required');
                        }
                        atencionIntegralRadios.forEach(radio => {
                            radio.checked = false;
                            radio.removeAttribute('required');
                        });
                    } else if (discapacidadRadio.value === '1') {
                        tipoDiscapacidadContainer.style.display = 'block';
                        atencionIntegralContainer.style.display = 'block';

                        if (tipoDiscapacidadSelect) tipoDiscapacidadSelect.setAttribute('required', 'required');
                        atencionIntegralRadios.forEach(radio => {
                            radio.setAttribute('required', 'required');
                        });
                    }
                }
            }

            // Función para manejar la visibilidad del campo nombre etnia
            function toggleNombreEtniaField() {
                const pertenenciaEtnicaSelect = document.getElementById('pertenencia_etnica');
                const nombreEtniaContainer = document.getElementById('nombre_etnia_container');
                const nombreEtniaInput = document.getElementById('nombre_etnia');

                if (pertenenciaEtnicaSelect && nombreEtniaContainer && nombreEtniaInput) {
                    // ID 5 corresponde a "NO DEFINE" según el seeder
                    if (pertenenciaEtnicaSelect.value === '' || pertenenciaEtnicaSelect.value === '5') {
                        nombreEtniaContainer.style.display = 'none';
                        nombreEtniaInput.value = '';
                        nombreEtniaInput.removeAttribute('required');
                    } else {
                        // Para cualquier etnia específica (43-47), mostrar el campo nombre
                        nombreEtniaContainer.style.display = 'block';
                        nombreEtniaInput.setAttribute('required', 'required');
                    }
                }
            }

            // Event listeners para campos condicionales
            const afiliacionRadios = document.querySelectorAll('input[name="afiliacion_salud"]');
            afiliacionRadios.forEach(radio => {
                radio.addEventListener('change', toggleAfiliacionFields);
            });

            const discapacidadRadios = document.querySelectorAll('input[name="discapacidad"]');
            discapacidadRadios.forEach(radio => {
                radio.addEventListener('change', toggleDiscapacidadFields);
            });

            const pertenenciaEtnicaSelect = document.getElementById('pertenencia_etnica');
            if (pertenenciaEtnicaSelect) {
                pertenenciaEtnicaSelect.addEventListener('change', toggleNombreEtniaField);
                // También agregar evento Select2
                $(pertenenciaEtnicaSelect).on('change', toggleNombreEtniaField);
            }

            // Ejecutar al abrir el modal para inicializar correctamente
            modal.addEventListener('shown.bs.modal', function() {
                // Inicializar Select2 solo para selects que no sean campos binarios
                $('#{{ $modalId }} select:not([name="afiliacion_salud"]):not([name="discapacidad"])')
                    .select2({
                        dropdownParent: $('#{{ $modalId }}'),
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: 'Seleccione...',
                        allowClear: true
                    });

                // Ejecutar toggle después de inicializar
                setTimeout(function() {
                    toggleAfiliacionFields();
                    toggleDiscapacidadFields();
                    toggleNombreEtniaField();
                }, 100);
            });

            // Limpiar formulario al cerrar el modal
            modal.addEventListener('hidden.bs.modal', function() {
                crearPersonaForm.reset();
                // Limpiar Select2 solo de selects que no sean radio buttons
                $('#{{ $modalId }} select:not([name="afiliacion_salud"]):not([name="discapacidad"])')
                    .val('').trigger('change');
                // Destruir Select2 instances
                $('#{{ $modalId }} select:not([name="afiliacion_salud"]):not([name="discapacidad"])')
                    .select2('destroy');
                // Limpiar clases de validación
                crearPersonaForm.querySelectorAll('.is-invalid').forEach(field => {
                    field.classList.remove('is-invalid');
                });
                crearPersonaForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                    feedback.remove();
                });
                // Resetear campos condicionales
                toggleAfiliacionFields();
                toggleDiscapacidadFields();
                toggleNombreEtniaField();
            });

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
                        const discapacidadRadio = document.querySelector(
                            'input[name="discapacidad"]:checked');
                        if (discapacidadRadio && discapacidadRadio.value === '0') {
                            field.classList.remove('is-invalid');
                            const errorDiv = field.parentNode.querySelector('.invalid-feedback');
                            if (errorDiv) errorDiv.remove();
                            return;
                        }
                    }

                    // Validación condicional para nombre de etnia
                    if (field.name === 'nombre_etnia') {
                        const pertenenciaEtnicaSelect = document.getElementById(
                            'pertenencia_etnica');
                        if (pertenenciaEtnicaSelect && (pertenenciaEtnicaSelect.value === '' ||
                                pertenenciaEtnicaSelect.value === '5')) {
                            field.classList.remove('is-invalid');
                            const errorDiv = field.parentNode.querySelector('.invalid-feedback');
                            if (errorDiv) errorDiv.remove();
                            return;
                        }
                    }

                    // Validación especial para radio buttons
                    if (field.type === 'radio') {
                        const radioGroup = document.querySelectorAll(
                            `input[name="${field.name}"]:checked`);
                        if (radioGroup.length === 0) {
                            isValid = false;
                            field.classList.add('is-invalid');
                            if (!firstInvalidField) {
                                firstInvalidField = field;
                            }
                        } else {
                            // Remover clase is-invalid de todos los radio buttons del grupo
                            document.querySelectorAll(`input[name="${field.name}"]`).forEach(
                                radio => {
                                    radio.classList.remove('is-invalid');
                                });
                        }
                    } else {
                        // Validación normal para otros campos
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('is-invalid');
                            if (!firstInvalidField) {
                                firstInvalidField = field;
                            }
                        } else {
                            field.classList.remove('is-invalid');
                        }
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
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Disparar evento personalizado para que el componente padre maneje la actualización
                            const event = new CustomEvent('personaCreated', {
                                detail: {
                                    persona: data.persona
                                }
                            });
                            document.dispatchEvent(event);

                            // Cerrar modal después del evento
                            modalInstance.hide();

                            // Limpiar formulario
                            setTimeout(() => {
                                crearPersonaForm.reset();
                                toggleAfiliacionFields();
                                toggleDiscapacidadFields();
                                toggleNombreEtniaField();
                            }, 300);

                            // Mostrar notificación de éxito
                            showNotification('Persona creada exitosamente', 'success');
                        } else {
                            showNotification('Error al crear la persona: ' + (data.message ||
                                'Error desconocido'), 'error');
                        }
                    })
                    .catch(error => {
                        showNotification('Error al crear la persona: ' + error.message, 'error');
                    })
                    .finally(() => {
                        guardarPersonaBtn.disabled = false;
                        guardarPersonaBtn.innerHTML = '<i class="fas fa-save me-1"></i>Guardar Persona';
                    });
            });

            // Función para mostrar notificaciones (versión simplificada)
            function showNotification(message, type) {
                try {
                    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                    const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; max-width: 400px;" role="alert">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

                    // Insertar en el body directamente
                    document.body.insertAdjacentHTML('afterbegin', alertHtml);

                    // Auto-remover después de 5 segundos
                    setTimeout(() => {
                        const alerts = document.querySelectorAll('.alert.position-fixed');
                        alerts.forEach(alert => alert.remove());
                    }, 5000);
                } catch (e) {
                    // Fallback a alert simple
                    alert(message);
                }
            }


        });
    </script>
@endpush
