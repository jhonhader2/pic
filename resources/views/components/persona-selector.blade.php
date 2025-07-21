@props(['personas', 'selected' => [], 'name' => 'personas[]'])

<div class="mb-3">
    <label class="form-label fw-semibold">
        <i class="fas fa-users me-2"></i>Personas Asignadas (Opcional)
    </label>

    <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
        @if ($personas->count() > 0)
            <div class="row">
                @foreach ($personas as $persona)
                    <div class="col-md-6 col-lg-4 mb-2">
                        <div class="form-check">
                            <input type="checkbox" id="persona_{{ $persona->id }}" name="{{ $name }}"
                                value="{{ $persona->id }}" {{ in_array($persona->id, $selected) ? 'checked' : '' }}
                                class="form-check-input">
                            <label for="persona_{{ $persona->id }}" class="form-check-label">
                                <i class="fas fa-user me-1"></i>
                                <strong>{{ $persona->primer_nombre }} {{ $persona->primer_apellido }}</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-id-card me-1"></i>{{ $persona->numero_documento }}
                                </small>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted small">
                <i class="fas fa-exclamation-triangle me-1"></i>No hay personas registradas
            </p>
        @endif
    </div>

    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
