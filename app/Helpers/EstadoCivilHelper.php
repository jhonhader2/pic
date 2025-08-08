<?php

namespace App\Helpers;

class EstadoCivilHelper
{
    /**
     * Obtiene las opciones de estado civil
     */
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('ESTADO CIVIL');
    }

    /**
     * Verifica si un ID de estado civil es válido
     */
    public static function esValido(?int $id): bool
    {
        return ParametroHelper::esValido($id, 'ESTADO CIVIL');
    }
}
