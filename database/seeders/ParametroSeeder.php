<?php

namespace Database\Seeders;

use App\Models\Parametro;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParametroSeeder extends Seeder
{
    /**
     * Ejecuta los seeders de la base de datos.
     */
    public function run(): void
    {
        $adminId = $this->getAdminUserId();

        $this->seedParametrosGenerales($adminId);
        $this->seedTiposDocumento($adminId);
        $this->seedSexoIdentidad($adminId);
        $this->seedEstadoCivil($adminId);
        $this->seedTipoSangre($adminId);
        $this->seedAfiliacionSalud($adminId);
        $this->seedDiscapacidad($adminId);
        $this->seedPertenenciaEtnica($adminId);
        $this->seedNumeros($adminId);
        $this->seedFrecuencias($adminId);
        $this->seedOcupacion($adminId);
        $this->seedVivienda($adminId);
        $this->seedBarrios($adminId);
    }

    /**
     * Obtiene el ID del usuario administrador.
     */
    private function getAdminUserId(): ?int
    {
        $admin = User::first();
        return $admin?->id;
    }

    /**
     * Crea un parámetro con los datos básicos.
     */
    private function createParametro(int $id, string $name, ?int $adminId): void
    {
        Parametro::create([
            'id' => $id,
            'name' => $name,
            'status' => true,
            'user_create_id' => $adminId,
            'user_edit_id' => $adminId,
        ]);
    }

    /**
     * Crea múltiples parámetros desde un array.
     */
    private function createParametros(array $parametros, ?int $adminId): void
    {
        foreach ($parametros as $parametro) {
            $this->createParametro($parametro['id'], $parametro['name'], $adminId);
        }
    }

    /**
     * Seedea parámetros generales del sistema.
     */
    private function seedParametrosGenerales(?int $adminId): void
    {
        $parametros = [
            ['id' => 1, 'name' => 'ACTIVO'],
            ['id' => 2, 'name' => 'INACTIVO'],
            ['id' => 3, 'name' => 'SI'],
            ['id' => 4, 'name' => 'NO'],
            ['id' => 5, 'name' => 'NO DEFINE'],
            ['id' => 6, 'name' => 'OTRO'],
            ['id' => 7, 'name' => 'NINGUNO/A'],
        ];

        $this->createParametros($parametros, $adminId);
    }

    /**
     * Seedea tipos de documento.
     */
    private function seedTiposDocumento(?int $adminId): void
    {
        $parametros = [
            ['id' => 8, 'name' => 'CEDULA DE CIUDADANIA'],
            ['id' => 9, 'name' => 'CEDULA DE EXTRANJERIA'],
            ['id' => 10, 'name' => 'PASAPORTE'],
            ['id' => 11, 'name' => 'TARJETA DE IDENTIDAD'],
            ['id' => 12, 'name' => 'REGISTRO CIVIL'],
        ];

        $this->createParametros($parametros, $adminId);
    }

    /**
     * Seedea sexo e identidad de género.
     */
    private function seedSexoIdentidad(?int $adminId): void
    {
        $parametros = [
            ['id' => 13, 'name' => 'MASCULINO'],
            ['id' => 14, 'name' => 'FEMENINO'],
            ['id' => 15, 'name' => 'HOMBRE'],
            ['id' => 16, 'name' => 'MUJER'],
            ['id' => 17, 'name' => 'HOMOSEXUAL'],
            ['id' => 18, 'name' => 'BISEXUAL'],
            ['id' => 19, 'name' => 'TRANSGENERO'],
        ];

        $this->createParametros($parametros, $adminId);
    }

    /**
     * Seedea estados civiles.
     */
    private function seedEstadoCivil(?int $adminId): void
    {
        $parametros = [
            ['id' => 20, 'name' => 'SOLTERO'],
            ['id' => 21, 'name' => 'CASADO'],
            ['id' => 22, 'name' => 'DIVORCIADO'],
            ['id' => 23, 'name' => 'VIUDO'],
        ];

        $this->createParametros($parametros, $adminId);
    }

    /**
     * Seedea tipos de sangre.
     */
    private function seedTipoSangre(?int $adminId): void
    {
        $parametros = [
            ['id' => 24, 'name' => 'A'],
            ['id' => 25, 'name' => 'B'],
            ['id' => 26, 'name' => 'AB'],
            ['id' => 27, 'name' => 'O'],
            ['id' => 28, 'name' => 'POSITIVO'],
            ['id' => 29, 'name' => 'NEGATIVO'],
        ];

        $this->createParametros($parametros, $adminId);
    }

    /**
     * Seedea afiliación en salud.
     */
    private function seedAfiliacionSalud(?int $adminId): void
    {
        $parametros = [
            ['id' => 30, 'name' => 'CONTRIBUTIVO'],
            ['id' => 31, 'name' => 'SUBSIDIADO'],
            ['id' => 32, 'name' => 'REGIMEN ESPECIAL'],
            ['id' => 33, 'name' => 'SISBEN'],
            ['id' => 34, 'name' => 'NUEVA EPS'],
            ['id' => 35, 'name' => 'COOMEVA'],
        ];

        $this->createParametros($parametros, $adminId);
    }

    /**
     * Seedea tipos de discapacidad.
     */
    private function seedDiscapacidad(?int $adminId): void
    {
        $parametros = [
            ['id' => 36, 'name' => 'FISICA'],
            ['id' => 37, 'name' => 'AUDITIVA'],
            ['id' => 38, 'name' => 'VISUAL'],
            ['id' => 39, 'name' => 'SORDOCEGUERA'],
            ['id' => 40, 'name' => 'COGNITIVA O INTELECTUAL'],
            ['id' => 41, 'name' => 'MENTAL'],
            ['id' => 42, 'name' => 'MULTIPLE'],
        ];

        $this->createParametros($parametros, $adminId);
    }

    /**
     * Seedea pertenencia étnica.
     */
    private function seedPertenenciaEtnica(?int $adminId): void
    {
        $parametros = [
            ['id' => 43, 'name' => 'INDIGENA'],
            ['id' => 44, 'name' => 'AFROCOLOMBIANO'],
            ['id' => 45, 'name' => 'RAIZAL'],
            ['id' => 46, 'name' => 'ROM / GITANO'],
            ['id' => 47, 'name' => 'PALENQUERO'],
        ];

        $this->createParametros($parametros, $adminId);
    }

    /**
     * Seedea números del 0 al 19.
     */
    private function seedNumeros(?int $adminId): void
    {
        $parametros = [];
        for ($i = 0; $i <= 19; $i++) {
            $parametros[] = ['id' => 48 + $i, 'name' => (string) $i];
        }

        $this->createParametros($parametros, $adminId);
    }

    /**
     * Seedea frecuencias.
     */
    private function seedFrecuencias(?int $adminId): void
    {
        $parametros = [
            ['id' => 68, 'name' => 'DIARIO'],
            ['id' => 69, 'name' => 'SEMANAL'],
            ['id' => 70, 'name' => 'QUINCENAL'],
            ['id' => 71, 'name' => 'MENSUAL'],
            ['id' => 72, 'name' => 'BIMESTRAL'],
            ['id' => 73, 'name' => 'TRIMESTRAL'],
            ['id' => 74, 'name' => 'CUATRIMESTRAL'],
        ];

        $this->createParametros($parametros, $adminId);
    }

    /**
     * Seedea ocupaciones.
     */
    private function seedOcupacion(?int $adminId): void
    {
        $parametros = [
            ['id' => 75, 'name' => 'PERSONA EN TRABAJO FORMAL'],
            ['id' => 76, 'name' => 'PERSONA EN TRABAJO INFORMAL'],
            ['id' => 77, 'name' => 'PENSIONADO'],
            ['id' => 78, 'name' => 'DESEMPLEADO'],
            ['id' => 79, 'name' => 'ESTUDIANTE'],
            ['id' => 80, 'name' => 'AMA DE CASA'],
        ];

        $this->createParametros($parametros, $adminId);
    }

    /**
     * Seedea información de vivienda.
     */
    private function seedVivienda(?int $adminId): void
    {
        $parametros = [
            // Tipo de vivienda
            ['id' => 81, 'name' => 'CASA'],
            ['id' => 82, 'name' => 'CASA INDIGENA'],
            ['id' => 83, 'name' => 'APARTAMENTO'],
            ['id' => 84, 'name' => 'PIEZA / CUARTO DE INQUILINATO'],

            // Tenencia de la vivienda
            ['id' => 85, 'name' => 'PROPIA'],
            ['id' => 86, 'name' => 'PROPIA PAGANDO'],
            ['id' => 87, 'name' => 'ARRIENDO'],
            ['id' => 88, 'name' => 'SUBARRIENDO'],
            ['id' => 89, 'name' => 'POSESIÓN SIN TÍTULO [OCUPANTE DE HECHO]'],

            // Tipo de material
            ['id' => 90, 'name' => 'CONCRETO'],
            ['id' => 91, 'name' => 'ZINC'],
            ['id' => 92, 'name' => 'PALMA O PAJA'],
            ['id' => 93, 'name' => 'PLÁSTICO'],
            ['id' => 94, 'name' => 'MADERA'],
            ['id' => 95, 'name' => 'BLOQUE'],
            ['id' => 96, 'name' => 'TIERRA'],
            ['id' => 97, 'name' => 'ARENA'],
            ['id' => 98, 'name' => 'BARRO'],
            ['id' => 99, 'name' => 'GRAVILLA'],
            ['id' => 100, 'name' => 'CERÁMICA'],
        ];

        $this->createParametros($parametros, $adminId);
    }

    /**
     * Seedea barrios.
     */
    private function seedBarrios(?int $adminId): void
    {
        $parametros = [
            ['id' => 101, 'name' => 'Primero de Mayo'],
            ['id' => 102, 'name' => 'La Paz'],
            ['id' => 103, 'name' => 'La Esperanza'],
            ['id' => 104, 'name' => 'El Triunfo'],
            ['id' => 105, 'name' => 'El Centro'],
            ['id' => 106, 'name' => 'Divino Niño'],
            ['id' => 107, 'name' => 'El Modelo'],
            ['id' => 108, 'name' => 'San Jorge'],
            ['id' => 109, 'name' => 'Obrero'],
            ['id' => 110, 'name' => 'Comuneros'],
            ['id' => 111, 'name' => 'Popular'],
            ['id' => 112, 'name' => 'San Ignacio'],
            ['id' => 113, 'name' => 'El Remanso'],
        ];

        $this->createParametros($parametros, $adminId);
    }
}
