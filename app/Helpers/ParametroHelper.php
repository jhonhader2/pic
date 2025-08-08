<?php

namespace App\Helpers;

use App\Models\Tema;
use App\Models\Parametro;

/**
 * Helper genérico para manejo de parámetros
 * 
 * Este helper centraliza la lógica de obtención de parámetros
 * siguiendo los principios de DRY y Single Responsibility
 */
class ParametroHelper
{
    /**
     * Obtiene las opciones de parámetros desde el tema correspondiente
     */
    public static function getOpciones(string $temaNombre): array
    {
        $tema = Tema::where('name', $temaNombre)
            ->where('status', 1)
            ->with(['parametros' => function ($query) {
                $query->where('parametros.status', 1);
            }])
            ->first();

        if (!$tema || $tema->parametros->isEmpty()) {
            return ['' => 'Seleccione...'];
        }

        $opciones = ['' => 'Seleccione...'];
        foreach ($tema->parametros->sortBy('name') as $parametro) {
            $opciones[$parametro->id] = $parametro->name;
        }

        return $opciones;
    }

    /**
     * Obtiene el nombre del parámetro por ID y tema
     */
    public static function getNombrePorId(int $id, string $temaNombre): ?string
    {
        $tema = Tema::where('name', $temaNombre)
            ->where('status', 1)
            ->with(['parametros' => function ($query) use ($id) {
                $query->where('parametros.status', 1)->where('parametros.id', $id);
            }])
            ->first();

        if (!$tema || $tema->parametros->isEmpty()) {
            return null;
        }

        return $tema->parametros->first()->name;
    }

    /**
     * Obtiene el parámetro completo por ID y tema
     */
    public static function getParametro(int $id, string $temaNombre): ?Parametro
    {
        $tema = Tema::where('name', $temaNombre)
            ->where('status', 1)
            ->with(['parametros' => function ($query) use ($id) {
                $query->where('parametros.status', 1)->where('parametros.id', $id);
            }])
            ->first();

        if (!$tema || $tema->parametros->isEmpty()) {
            return null;
        }

        return $tema->parametros->first();
    }

    /**
     * Obtiene todos los parámetros de un tema específico
     */
    public static function getParametros(string $temaNombre)
    {
        $tema = Tema::where('name', $temaNombre)
            ->where('status', 1)
            ->with(['parametros' => function ($query) {
                $query->where('parametros.status', 1);
            }])
            ->first();

        if (!$tema) {
            return collect();
        }

        return $tema->parametros->sortBy('name');
    }

    /**
     * Verifica si un ID de parámetro es válido para un tema específico
     */
    public static function esValido(?int $id, string $temaNombre): bool
    {
        if (!$id) {
            return false;
        }

        return self::getNombrePorId($id, $temaNombre) !== null;
    }

    /**
     * Obtiene el texto de un parámetro por ID y tema
     */
    public static function getTexto(?int $id, string $temaNombre): ?string
    {
        return self::getNombrePorId($id, $temaNombre);
    }
}
