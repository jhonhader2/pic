<?php

namespace App\Helpers;

class FactorRhHelper
{
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('FACTOR RH');
    }

    public static function esValido(int $id): bool
    {
        return ParametroHelper::esValido($id, 'FACTOR RH');
    }
}
