<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class EdadHelper
{
    /**
     * Calcula la distribución por edad de una colección de personas
     *
     * @param Collection $personas
     * @return array
     */
    public static function calcularDistribucionEdad(Collection $personas): array
    {
        $distribucion = [
            'ninos' => 0,      // 0-11 años
            'adolescentes' => 0, // 12-17 años
            'adultos' => 0,    // 18-59 años
            'adultos_mayores' => 0 // 60+ años
        ];

        foreach ($personas as $persona) {
            if (!$persona->fecha_nacimiento) {
                continue;
            }

            $edad = Carbon::parse($persona->fecha_nacimiento)->diffInYears(now());

            if ($edad < 12) {
                $distribucion['ninos']++;
            } elseif ($edad >= 12 && $edad < 18) {
                $distribucion['adolescentes']++;
            } elseif ($edad >= 18 && $edad < 60) {
                $distribucion['adultos']++;
            } else {
                $distribucion['adultos_mayores']++;
            }
        }

        return $distribucion;
    }

    /**
     * Obtiene los datos para el gráfico de distribución por edad
     *
     * @param array $distribucion
     * @return array
     */
    public static function getDatosGrafico(array $distribucion): array
    {
        return [
            'labels' => ['Niños (0-11)', 'Adolescentes (12-17)', 'Adultos (18-59)', 'Adultos Mayores (60+)'],
            'data' => [
                $distribucion['ninos'],
                $distribucion['adolescentes'],
                $distribucion['adultos'],
                $distribucion['adultos_mayores']
            ],
            'backgroundColor' => ['#17a2b8', '#ffc107', '#28a745', '#dc3545'],
            'total' => array_sum($distribucion)
        ];
    }
}
