<?php

namespace App\Services;

use App\Models\Familia;
use Illuminate\Support\Facades\Auth;

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
        // Acepta lista de UUIDs simples
        if (empty($personas)) {
            $familia->personas()->sync([]);
            return;
        }

        $pivotData = [];
        foreach ($personas as $personaId) {
            $pivotData[$personaId] = [
                'created_by' => Auth::id(),
            ];
        }

        $familia->personas()->sync($pivotData);
    }
}
