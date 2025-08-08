<?php

namespace App\Services;

use App\Models\Familia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FamiliaService
{
    public function create(array $data): Familia
    {
        $familia = Familia::create([
            'codigo' => $data['codigo'],
            'direccion' => $data['direccion'],
            'barrio_id' => $data['barrio_id'] ?? null,
            'jefe_persona_id' => $data['jefe_persona_id'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $this->syncPersonas($familia, $data['personas'] ?? []);

        return $familia;
    }

    public function update(Familia $familia, array $data): Familia
    {
        $familia->update([
            'codigo' => $data['codigo'],
            'direccion' => $data['direccion'],
            'barrio_id' => $data['barrio_id'] ?? null,
            'jefe_persona_id' => $data['jefe_persona_id'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null,
            'updated_by' => Auth::id(),
        ]);

        $this->syncPersonas($familia, $data['personas'] ?? []);

        return $familia;
    }

    private function syncPersonas(Familia $familia, array $personas): void
    {
        $pivotData = [];

        // Agregar el jefe de familia si existe y no está en la lista
        if ($familia->jefe_persona_id && !in_array($familia->jefe_persona_id, $personas)) {
            $personas[] = $familia->jefe_persona_id;
        }

        // Si no hay personas y no hay jefe, limpiar la relación
        if (empty($personas)) {
            $familia->personas()->sync([]);
            return;
        }

        foreach ($personas as $personaId) {
            $pivotData[$personaId] = [
                'created_by' => Auth::id(),
            ];
        }

        $familia->personas()->sync($pivotData);
    }

    public function validateJefeUnico(string $jefePersonaId, ?string $excludeFamiliaId = null): bool
    {
        $query = Familia::where('jefe_persona_id', $jefePersonaId);

        if ($excludeFamiliaId) {
            $query->where('id', '!=', $excludeFamiliaId);
        }

        return !$query->exists();
    }

    public function validatePersonaUnicaEnFamilia(string $personaId, ?string $excludeFamiliaId = null): bool
    {
        $query = DB::table('familia_personas')->where('persona_id', $personaId);

        if ($excludeFamiliaId) {
            $query->where('familia_id', '!=', $excludeFamiliaId);
        }

        return !$query->exists();
    }
}
