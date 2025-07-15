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
        return [
            true => 'Masculino',
            false => 'Femenino'
        ];
    }

    /**
     * Obtener el texto del sexo
     *
     * @param bool $sexo
     * @return string
     */
    public static function getTexto(bool $sexo): string
    {
        return $sexo ? 'Masculino' : 'Femenino';
    }

    /**
     * Obtener el valor booleano del sexo
     *
     * @param string $texto
     * @return bool|null
     */
    public static function getValor(string $texto): ?bool
    {
        $opciones = array_flip(self::getOpciones());
        return $opciones[$texto] ?? null;
    }

    /**
     * Validar si el valor es válido
     *
     * @param mixed $valor
     * @return bool
     */
    public static function esValido($valor): bool
    {
        return is_bool($valor) || in_array($valor, ['0', '1', 0, 1, true, false], true);
    }
}
