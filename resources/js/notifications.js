// Notificaciones en tiempo real
document.addEventListener('DOMContentLoaded', function () {
    // Verificar si el usuario está autenticado
    if (typeof window.userId !== 'undefined') {
        // Suscribirse al canal privado de notificaciones
        window.Echo.private(`notifications.${window.userId}`)
            .listen('NotificationSent', (e) => {
                console.log('Nueva notificación recibida:', e);

                // Actualizar contador de notificaciones
                updateNotificationCount();

                // Mostrar notificación toast
                showNotificationToast(e);

                // Actualizar dropdown de notificaciones
                updateNotificationDropdown();
            });

        // Suscribirse al canal público del dashboard
        window.Echo.channel('dashboard')
            .listen('DashboardUpdated', (e) => {
                console.log('Dashboard actualizado:', e);

                // Mostrar notificación toast
                showNotificationToast(e);

                // Actualizar estadísticas del dashboard si estamos en esa página
                if (window.location.pathname === '/dashboard') {
                    updateDashboardStats();
                }
            });
    }
});

// Función para actualizar el contador de notificaciones
function updateNotificationCount() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notification-badge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'inline';
                } else {
                    badge.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error actualizando contador:', error));
}

// Función para mostrar notificación toast
function showNotificationToast(notification) {
    // Crear elemento toast
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.innerHTML = `
        <div class="toast-header">
            <i class="fas fa-bell text-primary me-2"></i>
            <strong class="me-auto">Nueva Notificación</strong>
            <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
        <div class="toast-body">
            ${notification.data.mensaje || 'Nueva notificación recibida'}
        </div>
    `;

    // Agregar estilos
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        max-width: 350px;
        animation: slideIn 0.3s ease-out;
    `;

    // Agregar al DOM
    document.body.appendChild(toast);

    // Remover después de 5 segundos
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
}

// Función para actualizar el dropdown de notificaciones
function updateNotificationDropdown() {
    fetch('/notifications/unread')
        .then(response => response.json())
        .then(data => {
            const dropdown = document.getElementById('notification-dropdown');
            if (dropdown) {
                // Actualizar contenido del dropdown
                // Esta función se puede expandir según la estructura del dropdown
                console.log('Dropdown actualizado con:', data.notifications);
            }
        })
        .catch(error => console.error('Error actualizando dropdown:', error));
}

// Función para actualizar estadísticas del dashboard
function updateDashboardStats() {
    fetch('/dashboard/stats?force=1')
        .then(response => response.json())
        .then(data => {
            // Actualizar contadores principales
            updateCounter('total-encuestas', data.total_encuestas);
            updateCounter('encuestas-activas', data.encuestas_activas);
            updateCounter('total-respuestas', data.total_respuestas);
            updateCounter('respuestas-hoy', data.respuestas_hoy);

            // Actualizar gráficos si existen
            if (window.respuestasChart) {
                updateRespuestasChart(data.respuestas_ultimos_7_dias);
            }
            if (window.estadoChart) {
                updateEstadoChart(data.encuestas_por_estado);
            }
        })
        .catch(error => console.error('Error actualizando estadísticas:', error));
}

// Función para actualizar contadores
function updateCounter(elementId, value) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = value;
    }
}

// Función para actualizar gráfico de respuestas
function updateRespuestasChart(data) {
    if (window.respuestasChart) {
        window.respuestasChart.data.datasets[0].data = data;
        window.respuestasChart.update();
    }
}

// Función para actualizar gráfico de estado
function updateEstadoChart(data) {
    if (window.estadoChart) {
        window.estadoChart.data.datasets[0].data = [
            data.activas || 0,
            data.pendientes || 0,
            data.expiradas || 0
        ];
        window.estadoChart.update();
    }
}

// Agregar estilos CSS para las animaciones
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .toast-notification {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
`;
document.head.appendChild(style); 