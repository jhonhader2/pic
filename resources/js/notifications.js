// Sistema de notificaciones simplificado - Sin Pusher
document.addEventListener('DOMContentLoaded', function () {
    // Solo actualizar contador inicial si el usuario está autenticado
    if (typeof window.userId !== 'undefined' && window.userId !== null) {
        updateNotificationCount();
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
        .catch(error => { });
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
            }
        })
        .catch(error => { });
} 