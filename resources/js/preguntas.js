// Variables globales
let preguntaCounter = 0;
let preguntaActual = null;

// Inicializar la aplicación
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('preguntasForm')) {
        initPreguntasApp();
    }
});

function initPreguntasApp() {
    // Cargar preguntas existentes si las hay
    if (window.preguntasExistentes && window.preguntasExistentes.length > 0) {
        window.preguntasExistentes.forEach(pregunta => {
            agregarPregunta(pregunta);
        });
    } else {
        // Agregar primera pregunta por defecto si no hay preguntas existentes
        agregarPregunta();
    }

    // Event listeners
    const btnAgregarPregunta = document.getElementById('btn-agregar-pregunta');
    const btnAgregarOpcion = document.getElementById('btn-agregar-opcion');
    const btnGuardarOpciones = document.getElementById('btn-guardar-opciones');
    const preguntasForm = document.getElementById('preguntasForm');

    if (btnAgregarPregunta) {
        btnAgregarPregunta.addEventListener('click', function () {
            agregarPregunta();
        });
    }

    if (btnAgregarOpcion) {
        btnAgregarOpcion.addEventListener('click', agregarOpcion);
    }

    if (btnGuardarOpciones) {
        btnGuardarOpciones.addEventListener('click', guardarOpciones);
    }

    if (preguntasForm) {
        preguntasForm.addEventListener('submit', validarFormulario);
    }
}

function agregarPregunta(datosPregunta = null) {
    preguntaCounter++;
    const container = document.getElementById('preguntas-container');

    const preguntaDiv = document.createElement('div');
    preguntaDiv.className = 'pregunta-item mb-4 p-4 border rounded';
    preguntaDiv.id = `pregunta-${preguntaCounter}`;

    // Valores por defecto o de pregunta existente
    const titulo = datosPregunta ? datosPregunta.titulo : '';
    const tipo = datosPregunta ? datosPregunta.tipo : '';
    const descripcion = datosPregunta ? datosPregunta.descripcion : '';
    const requerida = datosPregunta ? datosPregunta.requerida : true;
    const opciones = datosPregunta ? datosPregunta.opciones : [];

    preguntaDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-start mb-3">
            <h6 class="mb-0">
                <i class="fas fa-question-circle me-2 text-primary"></i>
                Pregunta ${preguntaCounter}
            </h6>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarPregunta(${preguntaCounter})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Título de la pregunta</label>
                    <input type="text" 
                           name="preguntas[${preguntaCounter}][titulo]" 
                           class="form-control form-control-sm" 
                           placeholder="Ej: ¿Cuál es su color favorito?"
                           value="${titulo}"
                           required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tipo de pregunta</label>
                    <select name="preguntas[${preguntaCounter}][tipo]" 
                            class="form-control form-control-sm tipo-pregunta-select" 
                            onchange="cambiarTipoPregunta(${preguntaCounter}, this.value)">
                        <option value="">Seleccionar tipo...</option>
                        ${generarOpcionesTipos(tipo)}
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">Descripción (opcional)</label>
                    <textarea name="preguntas[${preguntaCounter}][descripcion]" 
                              class="form-control form-control-sm" 
                              rows="2" 
                              placeholder="Descripción adicional de la pregunta...">${descripcion}</textarea>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-check">
                    <input type="checkbox" 
                           name="preguntas[${preguntaCounter}][requerida]" 
                           value="1" 
                           class="form-check-input" 
                           ${requerida ? 'checked' : ''}>
                    <label class="form-check-label">
                        <i class="fas fa-asterisk me-1"></i>Pregunta requerida
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <div id="opciones-pregunta-${preguntaCounter}" style="display: none;">
                    <button type="button" 
                            class="btn btn-outline-primary btn-sm" 
                            onclick="configurarOpciones(${preguntaCounter})">
                        <i class="fas fa-cog me-1"></i>Configurar Opciones
                    </button>
                </div>
            </div>
        </div>
        
        <div id="opciones-preview-${preguntaCounter}" class="mt-3" style="display: none;">
            <label class="form-label fw-semibold">Opciones configuradas:</label>
            <div class="opciones-lista" id="opciones-lista-${preguntaCounter}">
                <!-- Se mostrarán las opciones aquí -->
            </div>
        </div>
    `;

    container.appendChild(preguntaDiv);

    // Si hay opciones, mostrarlas
    if (opciones && opciones.length > 0) {
        mostrarOpciones(preguntaCounter, opciones);
    }

    // Si hay tipo seleccionado, mostrar opciones si corresponde
    if (tipo) {
        cambiarTipoPregunta(preguntaCounter, tipo);
    }
}

function generarOpcionesTipos(tipoSeleccionado = '') {
    let options = '';
    if (window.tiposPregunta) {
        for (const [key, tipo] of Object.entries(window.tiposPregunta)) {
            const selected = key === tipoSeleccionado ? 'selected' : '';
            options += `<option value="${key}" ${selected}>${tipo.nombre}</option>`;
        }
    }
    return options;
}

function cambiarTipoPregunta(preguntaId, tipo) {
    const opcionesDiv = document.getElementById(`opciones-pregunta-${preguntaId}`);
    const previewDiv = document.getElementById(`opciones-preview-${preguntaId}`);

    if (tipo === 'seleccion_unica' || tipo === 'seleccion_multiple') {
        if (opcionesDiv) opcionesDiv.style.display = 'block';
        if (previewDiv) previewDiv.style.display = 'block';
    } else {
        if (opcionesDiv) opcionesDiv.style.display = 'none';
        if (previewDiv) previewDiv.style.display = 'none';
    }
}

function configurarOpciones(preguntaId) {
    preguntaActual = preguntaId;
    const modal = new bootstrap.Modal(document.getElementById('modalOpciones'));

    // Limpiar opciones anteriores
    const opcionesContainer = document.getElementById('opciones-container');
    if (opcionesContainer) {
        opcionesContainer.innerHTML = '';
    }

    // Agregar opciones por defecto
    agregarOpcion();
    agregarOpcion();

    modal.show();
}

function agregarOpcion() {
    const container = document.getElementById('opciones-container');
    if (!container) return;

    const opcionId = Date.now() + Math.random();

    const opcionDiv = document.createElement('div');
    opcionDiv.className = 'd-flex align-items-center mb-2';
    opcionDiv.innerHTML = `
        <input type="text" 
               class="form-control form-control-sm me-2 opcion-input" 
               placeholder="Opción..."
               data-opcion-id="${opcionId}">
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarOpcion(this)">
            <i class="fas fa-times"></i>
        </button>
    `;

    container.appendChild(opcionDiv);
}

function eliminarOpcion(button) {
    button.closest('.d-flex').remove();
}

function mostrarOpciones(preguntaId, opciones) {
    const opcionesLista = document.getElementById(`opciones-lista-${preguntaId}`);
    if (!opcionesLista) return;

    opcionesLista.innerHTML = '';

    opciones.forEach((opcion, index) => {
        const opcionDiv = document.createElement('div');
        opcionDiv.className = 'd-flex align-items-center mb-1';
        opcionDiv.innerHTML = `
            <i class="fas fa-circle text-primary me-2" style="font-size: 0.5rem;"></i>
            <span class="small">${opcion}</span>
        `;
        opcionesLista.appendChild(opcionDiv);
    });

    // Guardar opciones en inputs hidden
    const preguntaDiv = document.getElementById(`pregunta-${preguntaId}`);
    if (!preguntaDiv) return;

    let opcionesInput = preguntaDiv.querySelector('input[name*="[opciones]"]');

    if (!opcionesInput) {
        opcionesInput = document.createElement('input');
        opcionesInput.type = 'hidden';
        opcionesInput.name = `preguntas[${preguntaId}][opciones]`;
        preguntaDiv.appendChild(opcionesInput);
    }

    opcionesInput.value = JSON.stringify(opciones);
}

function guardarOpciones() {
    if (!preguntaActual) return;

    const opciones = [];
    document.querySelectorAll('.opcion-input').forEach(input => {
        if (input.value.trim()) {
            opciones.push(input.value.trim());
        }
    });

    // Guardar opciones en la pregunta
    const opcionesLista = document.getElementById(`opciones-lista-${preguntaActual}`);
    if (opcionesLista) {
        opcionesLista.innerHTML = '';

        opciones.forEach((opcion, index) => {
            const opcionDiv = document.createElement('div');
            opcionDiv.className = 'd-flex align-items-center mb-1';
            opcionDiv.innerHTML = `
                <i class="fas fa-circle text-primary me-2" style="font-size: 0.5rem;"></i>
                <span class="small">${opcion}</span>
            `;
            opcionesLista.appendChild(opcionDiv);
        });
    }

    // Guardar opciones en inputs hidden
    const preguntaDiv = document.getElementById(`pregunta-${preguntaActual}`);
    if (preguntaDiv) {
        let opcionesInput = preguntaDiv.querySelector('input[name*="[opciones]"]');

        if (!opcionesInput) {
            opcionesInput = document.createElement('input');
            opcionesInput.type = 'hidden';
            opcionesInput.name = `preguntas[${preguntaActual}][opciones]`;
            preguntaDiv.appendChild(opcionesInput);
        }

        opcionesInput.value = JSON.stringify(opciones);
    }

    // Cerrar modal
    const modal = document.getElementById('modalOpciones');
    if (modal) {
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
}

function eliminarPregunta(preguntaId) {
    const preguntaDiv = document.getElementById(`pregunta-${preguntaId}`);
    if (preguntaDiv) {
        preguntaDiv.remove();
        renumerarPreguntas();
    }
}

function renumerarPreguntas() {
    const preguntas = document.querySelectorAll('.pregunta-item');
    preguntas.forEach((pregunta, index) => {
        const titulo = pregunta.querySelector('h6');
        if (titulo) {
            titulo.innerHTML = `<i class="fas fa-question-circle me-2 text-primary"></i>Pregunta ${index + 1}`;
        }
    });
}

function validarFormulario(e) {
    const preguntas = document.querySelectorAll('.pregunta-item');

    if (preguntas.length === 0) {
        e.preventDefault();
        alert('Debe agregar al menos una pregunta.');
        return false;
    }

    // Validar que todas las preguntas tengan título y tipo
    for (let pregunta of preguntas) {
        const tituloInput = pregunta.querySelector('input[name*="[titulo]"]');
        const tipoSelect = pregunta.querySelector('select[name*="[tipo]"]');

        if (!tituloInput || !tipoSelect) continue;

        const titulo = tituloInput.value.trim();
        const tipo = tipoSelect.value;

        if (!titulo) {
            e.preventDefault();
            alert('Todas las preguntas deben tener un título.');
            return false;
        }

        if (!tipo) {
            e.preventDefault();
            alert('Todas las preguntas deben tener un tipo seleccionado.');
            return false;
        }

        // Validar opciones para tipos que las requieren
        if (tipo === 'seleccion_unica' || tipo === 'seleccion_multiple') {
            const opcionesInput = pregunta.querySelector('input[name*="[opciones]"]');
            if (!opcionesInput || !opcionesInput.value) {
                e.preventDefault();
                alert('Las preguntas de selección deben tener opciones configuradas.');
                return false;
            }
        }
    }

    return true;
}

// Hacer funciones disponibles globalmente
window.agregarPregunta = agregarPregunta;
window.cambiarTipoPregunta = cambiarTipoPregunta;
window.configurarOpciones = configurarOpciones;
window.eliminarOpcion = eliminarOpcion;
window.eliminarPregunta = eliminarPregunta;
