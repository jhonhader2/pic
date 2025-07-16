<?php

namespace App\Helpers;

class BarrioHelper
{
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('BARRIO');
    }

    public static function esValido(int $id): bool
    {
        return ParametroHelper::esValido($id, 'BARRIO');
    }
}
