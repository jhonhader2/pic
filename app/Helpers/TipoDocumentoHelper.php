<?php

namespace App\Helpers;

class TipoDocumentoHelper
{
    /**
     * Obtiene las opciones de tipos de documento
     */
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('TIPO DE DOCUMENTO');
    }

    /**
     * Verifica si un ID de tipo de documento es válido
     */
    public static function esValido(int $id): bool
    {
        return ParametroHelper::esValido($id, 'TIPO DE DOCUMENTO');
    }
}
