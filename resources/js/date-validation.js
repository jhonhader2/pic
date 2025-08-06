/**
 * Validación de fechas para formularios
 */

document.addEventListener('DOMContentLoaded', function () {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    const form = document.getElementById('encuestaForm');

    // Validar que la fecha de fin sea posterior a la de inicio
    if (fechaInicio && fechaFin) {
        fechaInicio.addEventListener('change', function () {
            fechaFin.min = this.value;
            if (fechaFin.value && fechaFin.value <= this.value) {
                fechaFin.value = '';
            }
        });

        // Establecer fecha mínima para fecha_inicio
        fechaInicio.min = new Date().toISOString().split('T')[0];
    }

    // Validación del formulario
    if (form) {
        form.addEventListener('submit', function (e) {
            if (fechaInicio && fechaFin && fechaInicio.value && fechaFin.value && fechaFin.value <=
                fechaInicio.value) {
                e.preventDefault();

                // Usar SweetAlert2 si está disponible, sino alert normal
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error de validación',
                        text: 'La fecha de fin debe ser posterior a la fecha de inicio.',
                        icon: 'error',
                        confirmButtonText: 'Entendido'
                    });
                } else {
                    alert('La fecha de fin debe ser posterior a la fecha de inicio.');
                }
                return false;
            }
        });
    }
});
