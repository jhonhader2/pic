<?php

namespace App\Helpers;

use App\Models\Parametro;

/**
 * Helper para tipos de sangre
 * 
 * Responsabilidades:
 * - Obtener tipos de sangre del sistema
 * - Proporcionar métodos para trabajar con tipos de sangre
 * 
 * Principios aplicados: POO, DRY, KISS, Single Responsibility
 */
class TipoSangreHelper
{
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('TIPO DE SANGRE');
    }

    public static function esValido(?int $id): bool
    {
        return ParametroHelper::esValido($id, 'TIPO DE SANGRE');
    }

    public static function getTiposSangre()
    {
        return ParametroHelper::getParametros('TIPO DE SANGRE');
    }

    public static function getTipoSangre(int $id): ?Parametro
    {
        return ParametroHelper::getParametro($id, 'TIPO DE SANGRE');
    }

    public static function getTexto(?int $id): ?string
    {
        return ParametroHelper::getNombrePorId($id, 'TIPO DE SANGRE');
    }
}
