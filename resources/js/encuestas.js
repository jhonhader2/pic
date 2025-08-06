/**
 * Funcionalidades JavaScript para la gestión de encuestas
 */

// Función para confirmar eliminación de encuestas con SweetAlert2
function confirmarEliminacion(url, titulo) {
    Swal.fire({
        title: '¿Estás seguro?',
        html: `¿Estás seguro de que quieres eliminar la encuesta <strong>"${titulo}"</strong>?<br><br>Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear un formulario temporal para enviar la petición DELETE
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
    });
}

// Función para mostrar/ocultar filtros de búsqueda
function toggleFiltros() {
    const content = document.getElementById('filtrosContent');
    const icon = document.getElementById('filtrosIcon');

    // Verificar que los elementos existen antes de usarlos
    if (!content || !icon) {
        return;
    }

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

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    // Mostrar filtros si hay parámetros en la URL
    const urlParams = new URLSearchParams(window.location.search);
    const hasFilters = urlParams.has('buscar') || urlParams.has('estado') ||
        urlParams.has('orden') || urlParams.has('direccion');

    // Solo ejecutar si existen los elementos de filtros
    const filtrosContent = document.getElementById('filtrosContent');
    if (hasFilters && filtrosContent) {
        toggleFiltros(); // Mostrar filtros si hay parámetros activos
    }
});

// Exportar funciones para uso global
window.confirmarEliminacion = confirmarEliminacion;
window.toggleFiltros = toggleFiltros;
