/**
 * Funcionalidades JavaScript para la gestión de encuestas
 */

// Función para confirmar eliminación de encuestas
function confirmarEliminacion(url, titulo) {
    return window.confirmarEliminacion(url, titulo, 'encuesta');
}

// Función para mostrar/ocultar filtros de búsqueda
function toggleFiltros() {
    return window.toggleFiltros();
}

// La inicialización se maneja en base.js

// Exportar funciones para uso global
window.confirmarEliminacion = confirmarEliminacion;
window.toggleFiltros = toggleFiltros;
