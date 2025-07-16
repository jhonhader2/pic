<?php

namespace App\Helpers;

/**
 * Helper para manejo de identidad de género
 * 
 * Este helper centraliza la lógica de conversión de identidad de género
 * siguiendo los principios de DRY y Single Responsibility
 */
class IdentidadGeneroHelper
{
    /**
     * Obtener las opciones de identidad de género para formularios
     *
     * @return array
     */
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('IDENTIDAD DE GENERO');
    }

    /**
     * Obtener el texto de la identidad de género por ID
     *
     * @param int $id
     * @return string|null
     */
    public static function getTexto(int $id): ?string
    {
        return ParametroHelper::getNombrePorId($id, 'IDENTIDAD DE GENERO');
    }

    /**
     * Verifica si un ID de identidad de género es válido
     */
    public static function esValido(int $id): bool
    {
        return ParametroHelper::esValido($id, 'IDENTIDAD DE GENERO');
    }
}
