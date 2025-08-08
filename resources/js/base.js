/**
 * Funcionalidades JavaScript base para toda la aplicación
 * 
 * Responsabilidades:
 * - Funciones comunes de confirmación
 * - Manejo de formularios
 * - Utilidades generales
 * 
 * Principios aplicados: POO, DRY, KISS, Single Responsibility
 */

// Función base para confirmaciones con SweetAlert2
function confirmarAccion(config) {
    const defaultConfig = {
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    };

    const finalConfig = { ...defaultConfig, ...config };

    return Swal.fire(finalConfig);
}

// Función para confirmar eliminación
function confirmarEliminacion(url, titulo, tipo = 'elemento') {
    return confirmarAccion({
        title: '¿Estás seguro?',
        html: `¿Estás seguro de que quieres eliminar el ${tipo} <strong>"${titulo}"</strong>?<br><br>Esta acción no se puede deshacer.`,
        icon: 'warning',
        confirmButtonText: 'Sí, eliminar',
        preConfirm: () => {
            return enviarFormularioEliminacion(url);
        }
    });
}

// Función para enviar formulario de eliminación
function enviarFormularioEliminacion(url) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;

    // Agregar el token CSRF
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfToken);

    // Agregar el método DELETE
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    form.appendChild(methodField);

    // Agregar el formulario al DOM y enviarlo
    document.body.appendChild(form);
    form.submit();
}

// Función para mostrar/ocultar elementos
function toggleElement(elementId, showClass = 'd-block', hideClass = 'd-none') {
    const element = document.getElementById(elementId);
    if (!element) return;

    if (element.classList.contains(hideClass)) {
        element.classList.remove(hideClass);
        element.classList.add(showClass);
    } else {
        element.classList.remove(showClass);
        element.classList.add(hideClass);
    }
}

// Función para mostrar/ocultar filtros
function toggleFiltros() {
    const content = document.getElementById('filtrosContent');
    const icon = document.getElementById('filtrosIcon');

    if (!content || !icon) return;

    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        content.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}

// Función para validar formularios
function validarFormulario(formId, reglas = {}) {
    const form = document.getElementById(formId);
    if (!form) return true;

    let esValido = true;
    const errores = [];

    // Validar campos requeridos
    const camposRequeridos = form.querySelectorAll('[required]');
    camposRequeridos.forEach(campo => {
        if (!campo.value.trim()) {
            esValido = false;
            errores.push(`El campo "${campo.name}" es requerido`);
            campo.classList.add('is-invalid');
        } else {
            campo.classList.remove('is-invalid');
        }
    });

    // Validar reglas personalizadas
    Object.keys(reglas).forEach(campoId => {
        const campo = document.getElementById(campoId);
        if (campo && !reglas[campoId](campo.value)) {
            esValido = false;
            errores.push(`El campo "${campo.name}" no cumple con la validación`);
            campo.classList.add('is-invalid');
        }
    });

    if (!esValido) {
        Swal.fire({
            title: 'Error de validación',
            html: errores.join('<br>'),
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
    }

    return esValido;
}

// Función para mostrar mensajes de éxito
function mostrarExito(mensaje, titulo = '¡Éxito!') {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'success',
        confirmButtonText: 'Entendido'
    });
}

// Función para mostrar mensajes de error
function mostrarError(mensaje, titulo = 'Error') {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'error',
        confirmButtonText: 'Entendido'
    });
}

// Función para hacer peticiones AJAX
function hacerPeticionAjax(url, metodo = 'GET', datos = null, opciones = {}) {
    const defaultOpciones = {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    };

    const finalOpciones = { ...defaultOpciones, ...opciones };

    if (datos && metodo !== 'GET') {
        finalOpciones.body = JSON.stringify(datos);
    }

    return fetch(url, {
        method: metodo,
        ...finalOpciones
    }).then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

// Función para formatear fechas
function formatearFecha(fecha, formato = 'DD/MM/YYYY') {
    if (!fecha) return '';

    const date = new Date(fecha);
    if (isNaN(date.getTime())) return '';

    const dia = String(date.getDate()).padStart(2, '0');
    const mes = String(date.getMonth() + 1).padStart(2, '0');
    const año = date.getFullYear();

    return formato
        .replace('DD', dia)
        .replace('MM', mes)
        .replace('YYYY', año);
}

// Función para formatear números
function formatearNumero(numero, decimales = 2) {
    if (isNaN(numero)) return '0';

    return Number(numero).toLocaleString('es-ES', {
        minimumFractionDigits: decimales,
        maximumFractionDigits: decimales
    });
}

// Función para inicializar componentes cuando el DOM esté listo
function inicializarComponentes() {
    // Mostrar filtros si hay parámetros en la URL
    const urlParams = new URLSearchParams(window.location.search);
    const hasFilters = urlParams.has('buscar') || urlParams.has('estado') ||
        urlParams.has('orden') || urlParams.has('direccion');

    if (hasFilters) {
        const filtrosContent = document.getElementById('filtrosContent');
        if (filtrosContent) {
            toggleFiltros();
        }
    }

    // Inicializar tooltips de Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Inicializar popovers de Bootstrap
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

// Exportar funciones para uso global
window.confirmarAccion = confirmarAccion;
window.confirmarEliminacion = confirmarEliminacion;
window.toggleElement = toggleElement;
window.toggleFiltros = toggleFiltros;
window.validarFormulario = validarFormulario;
window.mostrarExito = mostrarExito;
window.mostrarError = mostrarError;
window.hacerPeticionAjax = hacerPeticionAjax;
window.formatearFecha = formatearFecha;
window.formatearNumero = formatearNumero;

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', inicializarComponentes);
