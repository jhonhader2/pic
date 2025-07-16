<?php

namespace App\Helpers;

class TipoSangreHelper
{
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('TIPO DE SANGRE');
    }

    public static function esValido(int $id): bool
    {
        return ParametroHelper::esValido($id, 'TIPO DE SANGRE');
    }
}
