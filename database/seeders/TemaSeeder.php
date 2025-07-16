<?php

namespace Database\Seeders;

use App\Models\Tema;
use Illuminate\Database\Seeder;

class TemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->crearTemaConParametros('ESTADO', [1, 2]);
        $this->crearTemaConParametros('TIPO DE DOCUMENTO', range(8, 12));
        $this->crearTemaConParametros('SEXO', [13, 14]);
        $this->crearTemaConParametros('IDENTIDAD DE GENERO', range(15, 19));
        $this->crearTemaConParametros('ESTADO CIVIL', range(20, 23));
        $this->crearTemaConParametros('TIPO DE SANGRE', range(24, 27));
        $this->crearTemaConParametros('FACTOR RH', [28, 29]);
        $this->crearTemaConParametros('TIPO DE AFILIACIÓN EN SALUD', range(30, 32));
        $this->crearTemaConParametros('EPS', range(33, 35));
        $this->crearTemaConParametros('TIPO DE DISCAPACIDAD', range(36, 42));
        $this->crearTemaConParametros('TIPO DE PERTENENCIA ETNICA', array_merge(range(43, 47), [5]));
        $this->crearTemaConParametros('ESTRATO', range(49, 54));
        $this->crearTemaConParametros('FRECUENCIA', range(68, 74));
        $this->crearTemaConParametros('OCUPACION', array_merge(range(75, 80), [5]));
        $this->crearTemaConParametros('TIPO DE VIVIENDA', range(81, 84));
        $this->crearTemaConParametros('TENENCIA DE VIVIENDA', range(85, 89));
        $this->crearTemaConParametros('BARRIO', range(101, 113));
    }

    /**
     * Crea un tema y sincroniza sus parámetros
     */
    private function crearTemaConParametros(string $nombre, array $parametrosIds): void
    {
        $tema = Tema::create([
            'name' => $nombre,
            'status' => 1,
            'user_create_id' => 1,
            'user_edit_id' => 1,
        ]);

        $this->sincronizarParametros($tema, $parametrosIds);
    }

    /**
     * Sincroniza parámetros con un tema
     */
    private function sincronizarParametros(Tema $tema, array $parametrosIds): void
    {
        $syncData = [];
        foreach ($parametrosIds as $id) {
            $syncData[$id] = [
                'user_create_id' => 1,
                'user_edit_id' => 1,
                'status' => 1
            ];
        }

        $tema->parametros()->sync($syncData);
    }
}
