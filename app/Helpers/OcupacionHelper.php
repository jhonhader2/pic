<?php

namespace App\Helpers;

class OcupacionHelper
{
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('OCUPACION');
    }

    public static function esValido(int $id): bool
    {
        return ParametroHelper::esValido($id, 'OCUPACION');
    }
}
