/**
 * Funcionalidades JavaScript para páginas de autenticación
 */

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerForm');

    if (form) {
        const submitButton = form.querySelector('button[type="submit"]');

        // Manejar el envío del formulario
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Validar todos los campos requeridos
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;

            requiredFields.forEach(field => {
                // Validación condicional para campos de discapacidad
                if (field.name === 'atencion_integral_discapacidad' || field.name === 'tipo_discapacidad') {
                    const discapacidadSelect = document.getElementById('discapacidad');
                    if (discapacidadSelect && discapacidadSelect.value === '0') {
                        // Si discapacidad es "No", estos campos no son obligatorios
                        field.classList.remove('is-invalid');
                        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
                        if (errorDiv) {
                            errorDiv.remove();
                        }
                        return; // Saltar la validación para estos campos
                    }
                }

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

            // Validar contraseña
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
                // Enfocar el primer campo con error
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }

                // Mostrar mensaje de error general
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error de validación',
                        text: 'Por favor corrija los errores en el formulario.',
                        icon: 'error',
                        confirmButtonText: 'Entendido'
                    });
                }
            }
        });

        // Manejar cambios en el campo de discapacidad
        const discapacidadSelect = document.getElementById('discapacidad');
        if (discapacidadSelect) {
            discapacidadSelect.addEventListener('change', function () {
                const atencionField = document.getElementById('atencion_integral_discapacidad');
                const tipoField = document.getElementById('tipo_discapacidad');

                if (this.value === '0') {
                    // Si no tiene discapacidad, limpiar y deshabilitar campos
                    if (atencionField) {
                        atencionField.value = '';
                        atencionField.disabled = true;
                        atencionField.classList.remove('is-invalid');
                    }
                    if (tipoField) {
                        tipoField.value = '';
                        tipoField.disabled = true;
                        tipoField.classList.remove('is-invalid');
                    }
                } else {
                    // Si tiene discapacidad, habilitar campos
                    if (atencionField) {
                        atencionField.disabled = false;
                    }
                    if (tipoField) {
                        tipoField.disabled = false;
                    }
                }
            });
        }
    }
});
