<?php

namespace Database\Seeders;

use App\Models\Tema;
use App\Models\User;
use Illuminate\Database\Seeder;

class TemaSeeder extends Seeder
{
    /**
     * Ejecuta los seeders de la base de datos.
     */
    public function run(): void
    {
        $adminId = $this->getAdminUserId();

        $this->seedTemas($adminId);
    }

    /**
     * Obtiene el ID del usuario administrador.
     */
    private function getAdminUserId(): int
    {
        $admin = User::first();
        return $admin?->id ?? 1;
    }

    /**
     * Crea un tema con parámetros asociados.
     */
    private function createTemaConParametros(string $nombre, array $parametrosIds, int $adminId): void
    {
        $tema = Tema::create([
            'name' => $nombre,
            'status' => true,
            'user_create_id' => $adminId,
            'user_edit_id' => $adminId,
        ]);

        $this->sincronizarParametros($tema, $parametrosIds, $adminId);
    }

    /**
     * Sincroniza parámetros con un tema.
     */
    private function sincronizarParametros(Tema $tema, array $parametrosIds, int $adminId): void
    {
        $syncData = [];
        foreach ($parametrosIds as $id) {
            $syncData[$id] = [
                'user_create_id' => $adminId,
                'user_edit_id' => $adminId,
                'status' => true
            ];
        }

        $tema->parametros()->sync($syncData);
    }

    /**
     * Seedea todos los temas del sistema.
     */
    private function seedTemas(int $adminId): void
    {
        $temas = [
            'ESTADO' => [1, 2],
            'TIPO DE DOCUMENTO' => range(8, 12),
            'SEXO' => [13, 14],
            'IDENTIDAD DE GENERO' => range(15, 19),
            'ESTADO CIVIL' => range(20, 23),
            'TIPO DE SANGRE' => range(24, 27),
            'FACTOR RH' => [28, 29],
            'TIPO DE AFILIACIÓN EN SALUD' => range(30, 32),
            'EPS' => range(33, 35),
            'TIPO DE DISCAPACIDAD' => range(36, 42),
            'TIPO DE PERTENENCIA ETNICA' => array_merge(range(43, 47), [5]),
            'ESTRATO' => range(49, 54),
            'FRECUENCIA' => range(68, 74),
            'OCUPACION' => array_merge(range(75, 80), [5]),
            'TIPO DE VIVIENDA' => range(81, 84),
            'TENENCIA DE VIVIENDA' => range(85, 89),
            'BARRIO' => range(101, 113),
        ];

        foreach ($temas as $nombre => $parametrosIds) {
            $this->createTemaConParametros($nombre, $parametrosIds, $adminId);
        }
    }
}
