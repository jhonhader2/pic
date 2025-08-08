<?php

namespace App\Helpers;

class EpsHelper
{
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('EPS');
    }

    public static function esValido(?int $id): bool
    {
        return ParametroHelper::esValido($id, 'EPS');
    }
}
