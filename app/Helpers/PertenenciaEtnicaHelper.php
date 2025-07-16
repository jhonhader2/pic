<?php

namespace App\Helpers;

class PertenenciaEtnicaHelper
{
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('TIPO DE PERTENENCIA ETNICA');
    }

    public static function esValido(int $id): bool
    {
        return ParametroHelper::esValido($id, 'TIPO DE PERTENENCIA ETNICA');
    }
}
