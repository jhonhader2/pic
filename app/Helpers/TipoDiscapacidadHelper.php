<?php

namespace App\Helpers;

class TipoDiscapacidadHelper
{
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('TIPO DE DISCAPACIDAD');
    }

    public static function esValido(?int $id): bool
    {
        return ParametroHelper::esValido($id, 'TIPO DE DISCAPACIDAD');
    }
}
