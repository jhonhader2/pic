@props(['familia'])

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h4 class="mb-0">{{ $familia->personas->count() }}</h4>
                <small>Integrantes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-male fa-2x mb-2"></i>
                <h4 class="mb-0">{{ $familia->personas->whereIn('sexo_id', [13, 15])->count() }}</h4>
                <small>Hombres</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-female fa-2x mb-2"></i>
                <h4 class="mb-0">{{ $familia->personas->whereIn('sexo_id', [14, 16])->count() }}</h4>
                <small>Mujeres</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-child fa-2x mb-2"></i>
                <h4 class="mb-0">{{ $familia->personas->where('edad', '<', 18)->count() }}</h4>
                <small>Menores</small>
            </div>
        </div>
    </div>
</div>
