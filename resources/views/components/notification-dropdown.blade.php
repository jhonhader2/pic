@php
    $user = Auth::user();
    $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
@endphp

<div class="dropdown">
    <button class="btn btn-link nav-link position-relative" type="button" id="notificationDropdown"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        @if ($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown"
        style="width: 350px; max-height: 400px; overflow-y: auto;">
        <li class="dropdown-header d-flex justify-content-between align-items-center">
            <span>Notificaciones</span>
            @if ($unreadCount > 0)
                <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                    Marcar todas como leídas
                </button>
            @endif
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>

        <div id="notifications-list">
            @if ($unreadCount > 0)
                <li class="text-center py-3">
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </li>
            @else
                <li class="text-center py-3 text-muted">
                    <i class="fas fa-check-circle mb-2"></i>
                    <div>No hay notificaciones nuevas</div>
                </li>
            @endif
        </div>

        <li>
            <hr class="dropdown-divider">
        </li>

        <li>
            <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                <i class="fas fa-list me-2"></i>Ver todas las notificaciones
            </a>
        </li>
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ({{ $unreadCount }}) {
            loadUnreadNotifications();
        }

        // Actualizar cada 30 segundos
        setInterval(function() {
            updateNotificationCount();
        }, 30000);
    });

    function loadUnreadNotifications() {
        fetch('{{ route('notifications.unread') }}')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('notifications-list');
                container.innerHTML = '';

                if (data.notifications.length === 0) {
                    container.innerHTML = `
                    <li class="text-center py-3 text-muted">
                        <i class="fas fa-check-circle mb-2"></i>
                        <div>No hay notificaciones nuevas</div>
                    </li>
                `;
                    return;
                }

                data.notifications.forEach(notification => {
                    const item = document.createElement('li');
                    item.innerHTML = `
                    <a class="dropdown-item notification-item" href="#" onclick="markAsRead('${notification.id}')">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-circle text-primary" style="font-size: 8px;"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <div class="fw-bold small">${notification.data.mensaje || 'Nueva notificación'}</div>
                                <div class="text-muted small">${formatDate(notification.created_at)}</div>
                            </div>
                        </div>
                    </a>
                `;
                    container.appendChild(item);
                });
            })
            .catch(error => {

            });
    }

    function markAsRead(id) {
        fetch(`/notifications/${id}/read`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationCount();
                    loadUnreadNotifications();
                }
            })
            .catch(error => {

            });
    }

    function markAllAsRead() {
        fetch('{{ route('notifications.markAllAsRead') }}', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationCount();
                    loadUnreadNotifications();
                }
            })
            .catch(error => {

            });
    }

    function updateNotificationCount() {
        fetch('{{ route('notifications.unreadCount') }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('#notificationDropdown .badge');
                if (data.count > 0) {
                    if (!badge) {
                        const newBadge = document.createElement('span');
                        newBadge.className =
                            'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                        newBadge.textContent = data.count > 99 ? '99+' : data.count;
                        document.querySelector('#notificationDropdown').appendChild(newBadge);
                    } else {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                    }
                } else {
                    if (badge) {
                        badge.remove();
                    }
                }
            })
            .catch(error => {

            });
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInHours = Math.floor((now - date) / (1000 * 60 * 60));

        if (diffInHours < 1) {
            return 'Hace unos minutos';
        } else if (diffInHours < 24) {
            return `Hace ${diffInHours} hora${diffInHours > 1 ? 's' : ''}`;
        } else {
            const diffInDays = Math.floor(diffInHours / 24);
            return `Hace ${diffInDays} día${diffInDays > 1 ? 's' : ''}`;
        }
    }
</script>
