/**
 * Funcionalidades JavaScript para el dashboard
 */

// Inicialización de gráficos cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    // Verificar si Chart.js está disponible
    if (typeof Chart === 'undefined') {

        return;
    }

    // Gráfico de Respuestas por Día
    const respuestasCtx = document.getElementById('respuestasChart');
    if (respuestasCtx) {
        window.respuestasChart = new Chart(respuestasCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Hace 6 días', 'Hace 5 días', 'Hace 4 días', 'Hace 3 días', 'Hace 2 días',
                    'Ayer', 'Hoy'
                ],
                datasets: [{
                    label: 'Respuestas',
                    data: window.dashboardStats?.respuestas_ultimos_7_dias || [0, 0, 0, 0, 0, 0, 0],
                    borderColor: '#0066CC',
                    backgroundColor: 'rgba(0, 102, 204, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        }
                    }
                }
            }
        });
    }

    // Gráfico de Estado de Encuestas
    const estadoCtx = document.getElementById('estadoEncuestasChart');
    if (estadoCtx) {
        const stats = window.dashboardStats?.encuestas_por_estado || { activas: 0, pendientes: 0, expiradas: 0 };

        window.estadoChart = new Chart(estadoCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Activas', 'Pendientes', 'Expiradas'],
                datasets: [{
                    data: [
                        stats.activas || 0,
                        stats.pendientes || 0,
                        stats.expiradas || 0
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#dc3545'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
