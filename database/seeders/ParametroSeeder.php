<?php

namespace Database\Seeders;

use App\Models\Parametro;
use Illuminate\Database\Seeder;
use App\Models\User;

class ParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : null;

        $parametros = [
            ['id' => 1, 'name' => 'ACTIVO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 2, 'name' => 'INACTIVO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 3, 'name' => 'SI', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 4, 'name' => 'NO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 5, 'name' => 'NO DEFINE', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 6, 'name' => 'OTRO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 7, 'name' => 'NINGUNO/A', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Tipos de documento
            ['id' => 8, 'name' => 'CEDULA DE CIUDADANIA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 9, 'name' => 'CEDULA DE EXTRANJERIA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 10, 'name' => 'PASAPORTE', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 11, 'name' => 'TARJETA DE IDENTIDAD', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 12, 'name' => 'REGISTRO CIVIL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Sexo
            ['id' => 13, 'name' => 'MASCULINO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 14, 'name' => 'FEMENINO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Identidad de genero
            ['id' => 15, 'name' => 'HOMBRE', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 16, 'name' => 'MUJER', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 17, 'name' => 'HOMOSEXUAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 18, 'name' => 'BISEXUAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 19, 'name' => 'TRANSGENERO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Estado civil
            ['id' => 20, 'name' => 'SOLTERO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 21, 'name' => 'CASADO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 22, 'name' => 'DIVORCIADO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 23, 'name' => 'VIUDO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Tipo de sangre
            ['id' => 24, 'name' => 'A', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 25, 'name' => 'B', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 26, 'name' => 'AB', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 27, 'name' => 'O', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Factor RH
            ['id' => 28, 'name' => 'POSITIVO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 29, 'name' => 'NEGATIVO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Tipo de Afiliación en Salud
            ['id' => 30, 'name' => 'CONTRIBUTIVO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 31, 'name' => 'SUBSIDIADO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 32, 'name' => 'REGIMEN ESPECIAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // EPS
            ['id' => 33, 'name' => 'SISBEN', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 34, 'name' => 'NUEVA EPS', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 35, 'name' => 'COOMEVA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Tipo de Discapacidad
            ['id' => 36, 'name' => 'FISICA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 37, 'name' => 'AUDITIVA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 38, 'name' => 'VISUAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 39, 'name' => 'SORDOCEGUERA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 40, 'name' => 'COGNITIVA O INTELECTUAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 41, 'name' => 'MENTAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 42, 'name' => 'MULTIPLE', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Tipo de pertenencia etnica
            ['id' => 43, 'name' => 'INDIGENA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 44, 'name' => 'AFROCOLOMBIANO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 45, 'name' => 'RAIZAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 46, 'name' => 'ROM / GITANO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 47, 'name' => 'PALENQUERO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Numeros
            ['id' => 48, 'name' => '0', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 49, 'name' => '1', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 50, 'name' => '2', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 51, 'name' => '3', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 52, 'name' => '4', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 53, 'name' => '5', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 54, 'name' => '6', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 55, 'name' => '7', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 56, 'name' => '8', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 57, 'name' => '9', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 58, 'name' => '10', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 59, 'name' => '11', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 60, 'name' => '12', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 61, 'name' => '13', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 62, 'name' => '14', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 63, 'name' => '15', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 64, 'name' => '16', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 65, 'name' => '17', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 66, 'name' => '18', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 67, 'name' => '19', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Frecuencia
            ['id' => 68, 'name' => 'DIARIO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 69, 'name' => 'SEMANAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 70, 'name' => 'QUINCENAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 71, 'name' => 'MENSUAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 72, 'name' => 'BIMESTRAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 73, 'name' => 'TRIMESTRAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 74, 'name' => 'CUATRIMESTRAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Ocupación
            ['id' => 75, 'name' => 'PERSONA EN TRABAJO FORMAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 76, 'name' => 'PERSONA EN TRABAJO INFORMAL', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 77, 'name' => 'PENSIONADO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 78, 'name' => 'DESEMPLEADO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 79, 'name' => 'ESTUDIANTE', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 80, 'name' => 'AMA DE CASA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Tipo de vivienda
            ['id' => 81, 'name' => 'CASA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 82, 'name' => 'CASA INDIGENA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 83, 'name' => 'APARTAMENTO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 84, 'name' => 'PIEZA / CUARTO DE INQUILINATO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Tenencia de la vivienda
            ['id' => 85, 'name' => 'PROPIA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 86, 'name' => 'PROPIA PAGANDO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 87, 'name' => 'ARRIENDO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 88, 'name' => 'SUBARRIENDO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 89, 'name' => 'POSESIÓN SIN TÍTULO [OCUPANTE DE HECHO]', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Tipo de material
            ['id' => 90, 'name' => 'CONCRETO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 91, 'name' => 'ZINC', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 92, 'name' => 'PALMA O PAJA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 93, 'name' => 'PLÁSTICO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 94, 'name' => 'MADERA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 95, 'name' => 'BLOQUE', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 96, 'name' => 'TIERRA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 97, 'name' => 'ARENA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 98, 'name' => 'BARRO', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 99, 'name' => 'GRAVILLA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 100, 'name' => 'CERÁMICA', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

            // Barrios
            ['id' => 101, 'name' => 'Primero de Mayo', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 102, 'name' => 'La Paz', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 103, 'name' => 'La Esperanza', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 104, 'name' => 'El Triunfo', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 105, 'name' => 'El Centro', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 106, 'name' => 'Divino Niño', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 107, 'name' => 'El Modelo', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 108, 'name' => 'San Jorge', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 109, 'name' => 'Obrero', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 110, 'name' => 'Comuneros', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 111, 'name' => 'Popular', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 112, 'name' => 'San Ignacio', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],
            ['id' => 113, 'name' => 'El Remanso', 'status' => 1, 'user_create_id' => $adminId, 'user_edit_id' => $adminId],

        ];

        foreach ($parametros as $parametro) {
            Parametro::create($parametro);
        }
    }
}
