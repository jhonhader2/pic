@extends('layouts.app')

@section('title', 'Configurar Preguntas - ' . $encuesta->titulo)

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-dark">
                            <i class="fas fa-question-circle me-2 text-primary"></i>Configurar Preguntas
                        </h1>
                        <p class="text-muted mb-0">{{ $encuesta->titulo }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('encuestas.show', $encuesta) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-eye me-2"></i>Ver Encuesta
                        </a>
                        <a href="{{ route('encuestas.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Configuración de Preguntas -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cogs me-2"></i>Configurar Preguntas de la Encuesta
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('encuestas.preguntas.store', $encuesta) }}"
                            id="preguntasForm">
                            @csrf

                            <!-- Área de Preguntas -->
                            <div id="preguntas-container">
                                <!-- Las preguntas se agregarán aquí dinámicamente -->
                            </div>

                            <!-- Botón para agregar nueva pregunta -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-pregunta">
                                        <i class="fas fa-plus me-2"></i>Agregar Pregunta
                                    </button>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('encuestas.show', $encuesta) }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times me-2"></i>Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-sm" id="btn-guardar">
                                            <i class="fas fa-save me-2"></i>Guardar Preguntas
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para configurar opciones -->
    <div class="modal fade" id="modalOpciones" tabindex="-1" aria-labelledby="modalOpcionesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalOpcionesLabel">
                        <i class="fas fa-list-ul me-2"></i>Configurar Opciones
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Opciones de respuesta:</label>
                        <div id="opciones-container">
                            <!-- Las opciones se agregarán aquí -->
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="btn-agregar-opcion">
                            <i class="fas fa-plus me-1"></i>Agregar Opción
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-sm" id="btn-guardar-opciones">Guardar
                        Opciones</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Datos de tipos de pregunta
        window.tiposPregunta = @json($tiposPregunta);
        window.temas = @json($temas);

        // Variables globales
        let preguntaCounter = 0;
        let preguntaActual = null;

        // Inicializar la aplicación
        document.addEventListener('DOMContentLoaded', function() {
            initPreguntasApp();
        });

        function initPreguntasApp() {
            // Agregar primera pregunta por defecto
            agregarPregunta();

            // Event listeners
            document.getElementById('btn-agregar-pregunta').addEventListener('click', agregarPregunta);
            document.getElementById('btn-agregar-opcion').addEventListener('click', agregarOpcion);
            document.getElementById('btn-guardar-opciones').addEventListener('click', guardarOpciones);

            // Validación del formulario
            document.getElementById('preguntasForm').addEventListener('submit', validarFormulario);
        }

        function agregarPregunta() {
            preguntaCounter++;
            const container = document.getElementById('preguntas-container');

            const preguntaDiv = document.createElement('div');
            preguntaDiv.className = 'pregunta-item mb-4 p-4 border rounded';
            preguntaDiv.id = `pregunta-${preguntaCounter}`;

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
                                ${generarOpcionesTipos()}
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
                                      placeholder="Descripción adicional de la pregunta..."></textarea>
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
                                   checked>
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
        }

        function generarOpcionesTipos() {
            let options = '';
            for (const [key, tipo] of Object.entries(window.tiposPregunta)) {
                options += `<option value="${key}">${tipo.nombre}</option>`;
            }
            return options;
        }

        function cambiarTipoPregunta(preguntaId, tipo) {
            const opcionesDiv = document.getElementById(`opciones-pregunta-${preguntaId}`);
            const previewDiv = document.getElementById(`opciones-preview-${preguntaId}`);

            if (tipo === 'seleccion_unica' || tipo === 'seleccion_multiple') {
                opcionesDiv.style.display = 'block';
                previewDiv.style.display = 'block';
            } else {
                opcionesDiv.style.display = 'none';
                previewDiv.style.display = 'none';
            }
        }

        function configurarOpciones(preguntaId) {
            preguntaActual = preguntaId;
            const modal = new bootstrap.Modal(document.getElementById('modalOpciones'));

            // Limpiar opciones anteriores
            document.getElementById('opciones-container').innerHTML = '';

            // Agregar opciones por defecto
            agregarOpcion();
            agregarOpcion();

            modal.show();
        }

        function agregarOpcion() {
            const container = document.getElementById('opciones-container');
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
            const preguntaDiv = document.getElementById(`pregunta-${preguntaActual}`);
            let opcionesInput = preguntaDiv.querySelector('input[name*="[opciones]"]');

            if (!opcionesInput) {
                opcionesInput = document.createElement('input');
                opcionesInput.type = 'hidden';
                opcionesInput.name = `preguntas[${preguntaActual}][opciones]`;
                preguntaDiv.appendChild(opcionesInput);
            }

            opcionesInput.value = JSON.stringify(opciones);

            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('modalOpciones')).hide();
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
                titulo.innerHTML = `<i class="fas fa-question-circle me-2 text-primary"></i>Pregunta ${index + 1}`;
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
                const titulo = pregunta.querySelector('input[name*="[titulo]"]').value.trim();
                const tipo = pregunta.querySelector('select[name*="[tipo]"]').value;

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
    </script>
@endpush
