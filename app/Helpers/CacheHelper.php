<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use App\Models\Parametro;

/**
 * Helper para manejar el cache de configuraciones del sistema
 * 
 * Responsabilidades:
 * - Cachear configuraciones que no cambian frecuentemente
 * - Proporcionar métodos para invalidar cache cuando sea necesario
 * - Optimizar consultas repetitivas
 * 
 * Principios aplicados: POO, DRY, KISS, Single Responsibility
 */
class CacheHelper
{
    /**
     * Obtiene los tipos de pregunta con cache
     *
     * @return array
     */
    public static function getTiposPregunta(): array
    {
        return Cache::remember('tipos_pregunta', 7200, function () {
            return TipoPreguntaHelper::getTiposDisponibles();
        });
    }

    /**
     * Obtiene todos los parámetros activos con cache
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getParametrosActivos()
    {
        return Cache::remember('parametros_activos', 3600, function () {
            return Parametro::where('status', true)
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Obtiene parámetros por nombre con cache
     *
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getParametrosPorNombre(string $name)
    {
        $cacheKey = "parametros_name_{$name}";

        return Cache::remember($cacheKey, 3600, function () use ($name) {
            return Parametro::where('name', 'like', "%{$name}%")
                ->where('status', true)
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Obtiene estadísticas del sistema con cache
     *
     * @return array
     */
    public static function getEstadisticasSistema(): array
    {
        return Cache::remember('estadisticas_sistema', 1800, function () {
            return [
                'total_encuestas' => \App\Models\Encuesta::count(),
                'encuestas_activas' => \App\Models\Encuesta::where('activa', true)->count(),
                'total_respuestas' => \App\Models\Respuesta::count(),
                'total_personas' => \App\Models\Persona::count(),
                'total_usuarios' => \App\Models\User::count(),
            ];
        });
    }

    /**
     * Limpia el cache de configuraciones
     *
     * @return void
     */
    public static function limpiarCacheConfiguraciones(): void
    {
        Cache::forget('tipos_pregunta');
        Cache::forget('parametros_activos');

        // Limpiar cache de parámetros por nombre
        Cache::forget('parametros_name_*');
    }

    /**
     * Limpia el cache de estadísticas
     *
     * @return void
     */
    public static function limpiarCacheEstadisticas(): void
    {
        Cache::forget('estadisticas_sistema');
        Cache::forget('dashboard_stats');
        Cache::forget('total_users');
        Cache::forget('total_personas');
        Cache::forget('total_encuestas');
        Cache::forget('total_respuestas');
        Cache::forget('encuestas_activas');
        Cache::forget('respuestas_hoy');
    }

    /**
     * Limpia todo el cache relacionado con encuestas
     *
     * @return void
     */
    public static function limpiarCacheEncuestas(): void
    {
        Cache::forget('personas_list');
        Cache::forget('encuestas_with_relations');
        Cache::forget('encuestas_activas');
        Cache::forget('total_encuestas');
        Cache::forget('total_respuestas');
        Cache::forget('respuestas_hoy');
    }

    /**
     * Limpia todo el cache del sistema
     *
     * @return void
     */
    public static function limpiarTodoCache(): void
    {
        self::limpiarCacheConfiguraciones();
        self::limpiarCacheEstadisticas();
        self::limpiarCacheEncuestas();
    }
}
