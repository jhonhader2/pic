<?php

namespace App\Helpers;

class TipoAfiliacionSaludHelper
{
    public static function getOpciones(): array
    {
        return ParametroHelper::getOpciones('TIPO DE AFILIACIÓN EN SALUD');
    }

    public static function esValido(?int $id): bool
    {
        return ParametroHelper::esValido($id, 'TIPO DE AFILIACIÓN EN SALUD');
    }
}
