// JavaScript específico para la página de configuración de preguntas

document.addEventListener('DOMContentLoaded', function () {
    // Variables globales
    let tiposSeleccionados = [];
    let tiposConOpciones = [];
    let tiposSinOpciones = [];

    // Elementos del DOM
    const form = document.getElementById('preguntasForm');
    const btnGuardar = document.getElementById('btn-guardar');
    const seccionTemas = document.getElementById('seccion-temas');
    const seccionConfiguracion = document.getElementById('seccion-configuracion');

    // Obtener datos de tipos de pregunta desde el servidor
    const tiposPregunta = window.tiposPregunta || {};

    // Inicializar la aplicación
    initPreguntasApp();

    function initPreguntasApp() {
        setupTipoPreguntaListeners();
        setupTemaListeners();
        setupFormValidation();
    }

    // Configurar listeners para tipos de pregunta
    function setupTipoPreguntaListeners() {
        document.querySelectorAll('.tipo-pregunta-radio').forEach(function (radio) {
            radio.addEventListener('change', function () {
                handleTipoPreguntaChange(this);
            });
        });
    }

    // Manejar cambio de tipo de pregunta
    function handleTipoPreguntaChange(radio) {
        const tipo = radio.value;
        const tipoInfo = tiposPregunta[tipo];

        if (radio.checked) {
            // Agregar a la lista de tipos seleccionados
            if (!tiposSeleccionados.includes(tipo)) {
                tiposSeleccionados.push(tipo);
            }

            // Clasificar el tipo
            if (tipoInfo.requiere_opciones) {
                if (!tiposConOpciones.includes(tipo)) {
                    tiposConOpciones.push(tipo);
                }
            } else {
                if (!tiposSinOpciones.includes(tipo)) {
                    tiposSinOpciones.push(tipo);
                }
            }

            // Actualizar UI
            actualizarUI();
        }
    }

    // Configurar listeners para temas
    function setupTemaListeners() {
        document.querySelectorAll('.tema-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                handleTemaChange(this);
            });
        });
    }

    // Manejar cambio de tema
    function handleTemaChange(checkbox) {
        const temaItem = checkbox.closest('.tema-item');
        const configuracion = temaItem.querySelector('.configuracion-tema');

        if (checkbox.checked) {
            configuracion.style.display = 'block';
            configuracion.classList.add('fade-in');
        } else {
            configuracion.style.display = 'none';
            configuracion.classList.remove('fade-in');
        }
    }

    // Actualizar la interfaz de usuario
    function actualizarUI() {
        actualizarInformacionTipos();
        mostrarOcultarSecciones();
        actualizarEstadoBoton();
    }

    // Actualizar información de tipos seleccionados
    function actualizarInformacionTipos() {
        const tiposSeleccionadosSpan = document.getElementById('tipos-seleccionados');
        const tiposSinOpcionesSpan = document.getElementById('tipos-sin-opciones');

        if (tiposSeleccionadosSpan) {
            tiposSeleccionadosSpan.textContent = tiposSeleccionados
                .map(tipo => tiposPregunta[tipo].nombre)
                .join(', ');
        }

        if (tiposSinOpcionesSpan) {
            tiposSinOpcionesSpan.textContent = tiposSinOpciones
                .map(tipo => tiposPregunta[tipo].nombre)
                .join(', ');
        }
    }

    // Mostrar u ocultar secciones según los tipos seleccionados
    function mostrarOcultarSecciones() {
        // Sección de temas
        if (tiposConOpciones.length > 0) {
            seccionTemas.style.display = 'block';
            seccionTemas.classList.add('fade-in');
        } else {
            seccionTemas.style.display = 'none';
            seccionTemas.classList.remove('fade-in');
        }

        // Sección de configuración
        if (tiposSinOpciones.length > 0) {
            seccionConfiguracion.style.display = 'block';
            seccionConfiguracion.classList.add('fade-in');
            generarPreguntasGenericas();
        } else {
            seccionConfiguracion.style.display = 'none';
            seccionConfiguracion.classList.remove('fade-in');
        }
    }

    // Actualizar estado del botón de guardar
    function actualizarEstadoBoton() {
        btnGuardar.disabled = tiposSeleccionados.length === 0;
    }

    // Generar preguntas genéricas dinámicamente
    function generarPreguntasGenericas() {
        const container = document.getElementById('preguntas-genericas');
        if (!container) return;

        container.innerHTML = '';

        tiposSinOpciones.forEach((tipo, index) => {
            const tipoInfo = tiposPregunta[tipo];
            const preguntaDiv = crearElementoPreguntaGenerica(tipo, tipoInfo, index);
            container.appendChild(preguntaDiv);
        });
    }

    // Crear elemento de pregunta genérica
    function crearElementoPreguntaGenerica(tipo, tipoInfo, index) {
        const preguntaDiv = document.createElement('div');
        preguntaDiv.className = 'pregunta-generica mb-4 p-3 border rounded fade-in';

        preguntaDiv.innerHTML = `
            <div class="d-flex align-items-start mb-3">
                <span class="badge bg-primary me-3">${index + 1}</span>
                <div class="flex-grow-1">
                    <h6 class="mb-1">${tipoInfo.nombre}</h6>
                    <span class="badge bg-info">
                        <i class="${tipoInfo.icono} me-1"></i>${tipoInfo.nombre}
                    </span>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Título de la pregunta</label>
                    <input type="text" 
                           name="titulos_genericos[${tipo}]" 
                           class="form-control form-control-sm" 
                           placeholder="Ej: ¿Cuál es su opinión sobre...?"
                           required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Descripción (Opcional)</label>
                    <textarea name="descripciones_genericas[${tipo}]" 
                              class="form-control form-control-sm" 
                              rows="2" 
                              placeholder="Descripción adicional..."></textarea>
                </div>
            </div>
            
            <div class="mt-3">
                <div class="form-check">
                    <input type="checkbox" 
                           name="requeridas_genericas[${tipo}]" 
                           value="1" 
                           class="form-check-input" 
                           checked>
                    <label class="form-check-label small">
                        <i class="fas fa-asterisk me-1"></i>Pregunta requerida
                    </label>
                </div>
            </div>
        `;

        return preguntaDiv;
    }

    // Configurar validación del formulario
    function setupFormValidation() {
        if (!form) return;

        form.addEventListener('submit', function (e) {
            if (!validarFormulario()) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Validar formulario antes de enviar
    function validarFormulario() {
        // Validar que se seleccionen tipos de pregunta
        if (tiposSeleccionados.length === 0) {
            mostrarError('Debe seleccionar al menos un tipo de pregunta.');
            return false;
        }

        // Validar que se seleccionen temas si hay tipos que los requieren
        if (tiposConOpciones.length > 0) {
            const temasSeleccionados = document.querySelectorAll('input[name="temas[]"]:checked');
            if (temasSeleccionados.length === 0) {
                mostrarError('Debe seleccionar al menos un tema para los tipos de pregunta que requieren opciones.');
                return false;
            }
        }

        return true;
    }

    // Mostrar error usando SweetAlert2 si está disponible
    function mostrarError(mensaje) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error de Validación',
                text: mensaje,
                confirmButtonText: 'Entendido'
            });
        } else {
            alert(mensaje);
        }
    }

    // Función para limpiar selecciones
    function limpiarSelecciones() {
        tiposSeleccionados = [];
        tiposConOpciones = [];
        tiposSinOpciones = [];

        // Desmarcar todos los radio buttons
        document.querySelectorAll('.tipo-pregunta-radio').forEach(radio => {
            radio.checked = false;
        });

        // Desmarcar todos los checkboxes de temas
        document.querySelectorAll('.tema-checkbox').forEach(checkbox => {
            checkbox.checked = false;
            const temaItem = checkbox.closest('.tema-item');
            const configuracion = temaItem.querySelector('.configuracion-tema');
            if (configuracion) {
                configuracion.style.display = 'none';
            }
        });

        actualizarUI();
    }

    // Exponer funciones globalmente para uso en otros scripts
    window.preguntasApp = {
        limpiarSelecciones,
        actualizarUI,
        validarFormulario
    };
});
