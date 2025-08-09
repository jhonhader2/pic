<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PIC - Plan de Intervenciones Colectivas')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @vite(['resources/css/app.css'])
    @stack('styles')

    <!-- Estilos adicionales para dropdowns -->
    <style>
        .dropdown-menu.show {
            display: block !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            z-index: 1000 !important;
            float: left !important;
            min-width: 10rem !important;
            padding: 0.5rem 0 !important;
            margin: 0.125rem 0 0 !important;
            font-size: 1rem !important;
            color: #212529 !important;
            text-align: left !important;
            list-style: none !important;
            background-color: #fff !important;
            background-clip: padding-box !important;
            border: 1px solid rgba(0, 0, 0, 0.15) !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175) !important;
        }

        .dropdown-menu-end.show {
            right: 0 !important;
            left: auto !important;
        }

        .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.255em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }

        .dropdown-item {
            display: block;
            width: 100%;
            padding: 0.25rem 1rem;
            clear: both;
            font-weight: 400;
            color: #212529;
            text-align: inherit;
            text-decoration: none;
            white-space: nowrap;
            background-color: transparent;
            border: 0;
        }

        .dropdown-item:hover {
            color: #1e2125;
            background-color: #e9ecef;
        }

        .dropdown-divider {
            height: 0;
            margin: 0.5rem 0;
            border: 0;
            border-top: 1px solid rgba(0, 0, 0, 0.175);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">

    <!-- Navigation -->
    @include('components.navigation')

    <!-- Main Content -->
    <main class="flex-grow-1 content-wrapper">
        <div class="page-content">
            <!-- Session Alerts -->
            @include('components.session-alerts')

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Variables globales para JavaScript -->
    <script>
        window.userId = {{ Auth::id() ?? 'null' }};
        window.csrfToken = '{{ csrf_token() }}';

        // Asegurar que los dropdowns de Bootstrap funcionen
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar todos los dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });

            // También inicializar tooltips si existen
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Función global para quitar persona - Definida aquí para garantizar disponibilidad
        window.quitarPersona = function(personaId, nombrePersona) {
            // Verificar que SweetAlert2 esté disponible
            if (typeof Swal === 'undefined') {
                if (confirm(`¿Está seguro de que desea quitar a "${nombrePersona}" como aplicador de esta encuesta?`)) {
                    // Fallback a confirm nativo
                    submitRemoveForm(personaId);
                }
                return;
            }

            Swal.fire({
                title: '¿Está seguro?',
                text: `¿Está seguro de que desea quitar a "${nombrePersona}" como aplicador de esta encuesta?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, quitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitRemoveForm(personaId);
                }
            });
        };

        // Función auxiliar para enviar el formulario de eliminación
        function submitRemoveForm(personaId) {
            // Verificar que las rutas estén disponibles
            if (typeof window.routes === 'undefined' || !window.routes.encuestasPersonasDetach) {
                return;
            }

            // Crear formulario temporal para eliminar
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.routes.encuestasPersonasDetach;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = window.csrfToken;

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            const personaField = document.createElement('input');
            personaField.type = 'hidden';
            personaField.name = 'persona_id';
            personaField.value = personaId;

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            form.appendChild(personaField);

            document.body.appendChild(form);
            form.submit();
        }

        // Funciones para el modal de personas asignadas
        function initPersonasModal() {
            const buscarPersonas = document.getElementById('buscarPersonas');
            const btnSeleccionarTodos = document.getElementById('btnSeleccionarTodos');
            const btnDeseleccionarTodos = document.getElementById('btnDeseleccionarTodos');

            if (buscarPersonas) {
                // Búsqueda de personas
                buscarPersonas.addEventListener('input', function() {
                    const busqueda = this.value.toLowerCase();
                    const personas = document.querySelectorAll('.persona-item');

                    personas.forEach(persona => {
                        const nombre = persona.querySelector('label').textContent.toLowerCase();
                        if (nombre.includes(busqueda)) {
                            persona.style.display = 'block';
                        } else {
                            persona.style.display = 'none';
                        }
                    });
                });
            }

            // Contar personas seleccionadas
            document.querySelectorAll('.persona-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', actualizarContador);
            });

            if (btnSeleccionarTodos) {
                // Botón seleccionar todos
                btnSeleccionarTodos.addEventListener('click', function() {
                    document.querySelectorAll('.persona-checkbox').forEach(checkbox => {
                        checkbox.checked = true;
                    });
                    actualizarContador();
                });
            }

            if (btnDeseleccionarTodos) {
                // Botón deseleccionar todos
                btnDeseleccionarTodos.addEventListener('click', function() {
                    document.querySelectorAll('.persona-checkbox').forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    actualizarContador();
                });
            }

            // Inicializar contador
            actualizarContador();
        }

        function actualizarContador() {
            const contador = document.getElementById('personasSeleccionadas');
            if (contador) {
                const seleccionadas = document.querySelectorAll('.persona-checkbox:checked').length;
                contador.textContent = seleccionadas;
            }
        }

        // Inicializar modal cuando se muestre
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar modal de personas cuando se abra
            const modalPersonas = document.getElementById('modalPersonas');
            if (modalPersonas) {
                modalPersonas.addEventListener('shown.bs.modal', function() {
                    initPersonasModal();
                });
            }
        });
    </script>

    @stack('scripts')

    <!-- Script adicional para asegurar funcionamiento de dropdowns -->
    <script>
        // Solución directa para dropdowns
        document.addEventListener('DOMContentLoaded', function() {


            // Verificar si Bootstrap está disponible
            if (typeof bootstrap !== 'undefined') {

                var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                dropdownElementList.forEach(function(dropdownToggleEl) {
                    new bootstrap.Dropdown(dropdownToggleEl);
                });
            } else {

                // Fallback manual
                var dropdowns = document.querySelectorAll('.dropdown-toggle');
                dropdowns.forEach(function(dropdown) {
                    dropdown.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();


                        var menu = this.nextElementSibling;
                        if (menu && menu.classList.contains('dropdown-menu')) {
                            // Cerrar otros dropdowns abiertos
                            document.querySelectorAll('.dropdown-menu.show').forEach(function(
                                openMenu) {
                                if (openMenu !== menu) {
                                    openMenu.classList.remove('show');
                                }
                            });

                            menu.classList.toggle('show');

                        }
                    });
                });

                // Cerrar dropdowns al hacer clic fuera
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.dropdown')) {
                        var openMenus = document.querySelectorAll('.dropdown-menu.show');
                        openMenus.forEach(function(menu) {
                            menu.classList.remove('show');
                        });
                    }
                });
            }
        });
    </script>

    @vite(['resources/js/app.js'])
    @vite(['resources/js/base.js'])
    @vite(['resources/js/notifications.js'])


</body>

</html>
