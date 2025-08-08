<?php

namespace App\Helpers;

/**
 * Helper para manejo de sexo
 * 
 * Este helper centraliza la lógica de conversión de sexo
 * siguiendo los principios de DRY y Single Responsibility
 */
class SexoHelper
{
    /**
     * Obtener las opciones de sexo para formularios
     *
     * @return array
     */
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('SEXO');
    }

    /**
     * Obtener el texto del sexo por ID
     *
     * @param int $id
     * @return string|null
     */
    public static function getTexto(?int $id): ?string
    {
        return ParametroHelper::getNombrePorId($id, 'SEXO');
    }

    /**
     * Verifica si un ID de sexo es válido
     */
    public static function esValido(?int $id): bool
    {
        return ParametroHelper::esValido($id, 'SEXO');
    }

    /**
     * Obtener el valor booleano del sexo (para compatibilidad con código existente)
     *
     * @param string $texto
     * @return bool|null
     */
    public static function getValor(string $texto): ?bool
    {
        $opciones = self::getOpciones();
        $opcionesInvertidas = array_flip($opciones);

        return $opcionesInvertidas[$texto] ?? null;
    }

    /**
     * Validar si el valor es válido (para compatibilidad con código existente)
     *
     * @param mixed $valor
     * @return bool
     */
    public static function esValidoLegacy($valor): bool
    {
        return is_bool($valor) || in_array($valor, ['0', '1', 0, 1, true, false], true);
    }
}
