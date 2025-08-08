@props(['distribucionEdad', 'canvasId' => 'distribucionEdadChart', 'title' => 'Distribución por Edad'])

<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-chart-pie me-2"></i>{{ $title }}
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <canvas id="{{ $canvasId }}" width="400" height="200"></canvas>
            </div>
            <div class="col-lg-4">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="text-center p-3 border rounded" style="background-color: #17a2b8; color: white;">
                            <h4 class="mb-0">{{ $distribucionEdad['ninos'] }}</h4>
                            <small>Niños (0-11)</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 border rounded" style="background-color: #ffc107; color: #000;">
                            <h4 class="mb-0">{{ $distribucionEdad['adolescentes'] }}</h4>
                            <small>Adolescentes (12-17)</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 border rounded" style="background-color: #28a745; color: white;">
                            <h4 class="mb-0">{{ $distribucionEdad['adultos'] }}</h4>
                            <small>Adultos (18-59)</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 border rounded" style="background-color: #dc3545; color: white;">
                            <h4 class="mb-0">{{ $distribucionEdad['adultos_mayores'] }}</h4>
                            <small>Adultos Mayores (60+)</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-center">
                        <h6 class="text-muted">Total de Personas</h6>
                        <h3 class="text-primary">{{ array_sum($distribucionEdad) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de distribución por edad
        const ctx = document.getElementById('{{ $canvasId }}').getContext('2d');
        const distribucionData = {
            labels: ['Niños (0-11)', 'Adolescentes (12-17)', 'Adultos (18-59)', 'Adultos Mayores (60+)'],
            datasets: [{
                data: [
                    {{ $distribucionEdad['ninos'] }},
                    {{ $distribucionEdad['adolescentes'] }},
                    {{ $distribucionEdad['adultos'] }},
                    {{ $distribucionEdad['adultos_mayores'] }}
                ],
                backgroundColor: ['#17a2b8', '#ffc107', '#28a745', '#dc3545'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: distribucionData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    </script>
@endpush
