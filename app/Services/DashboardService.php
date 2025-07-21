<?php

namespace App\Services;

use App\Models\User;
use App\Models\Persona;
use App\Models\Encuesta;
use App\Models\Respuesta;
use Illuminate\Support\Facades\Cache;

/**
 * Servicio para manejar estadísticas del dashboard
 * 
 * Responsabilidades:
 * - Calcular estadísticas del sistema
 * - Proporcionar datos para el dashboard
 * 
 * Principios aplicados: POO, DRY, KISS, Single Responsibility
 */
class DashboardService
{
    /**
     * Obtiene todas las estadísticas del sistema con cache
     *
     * @return array
     */
    public function getStats(): array
    {
        return Cache::remember('dashboard_stats', 1800, function () {
            return [
                'total_usuarios' => $this->getTotalUsers(),
                'total_personas' => $this->getTotalPersonas(),
                'total_encuestas' => $this->getTotalEncuestas(),
                'total_respuestas' => $this->getTotalRespuestas(),
                'encuestas_activas' => $this->getEncuestasActivas(),
                'respuestas_hoy' => $this->getRespuestasHoy(),
                'respuestas_ultimos_7_dias' => $this->getRespuestasUltimos7Dias(),
                'encuestas_por_estado' => $this->getEncuestasPorEstado(),
            ];
        });
    }

    /**
     * Obtiene el total de usuarios registrados
     *
     * @return int
     */
    public function getTotalUsers(): int
    {
        return Cache::remember('total_users', 3600, function () {
            return User::count();
        });
    }

    /**
     * Obtiene el total de personas registradas
     *
     * @return int
     */
    public function getTotalPersonas(): int
    {
        return Cache::remember('total_personas', 3600, function () {
            return Persona::count();
        });
    }

    /**
     * Obtiene el total de encuestas
     *
     * @return int
     */
    public function getTotalEncuestas(): int
    {
        return Cache::remember('total_encuestas', 1800, function () {
            return Encuesta::count();
        });
    }

    /**
     * Obtiene el total de respuestas
     *
     * @return int
     */
    public function getTotalRespuestas(): int
    {
        return Cache::remember('total_respuestas', 900, function () {
            return Respuesta::count();
        });
    }

    /**
     * Obtiene el total de encuestas activas
     *
     * @return int
     */
    public function getEncuestasActivas(): int
    {
        return Cache::remember('encuestas_activas', 900, function () {
            return Encuesta::where('activa', true)
                ->where('fecha_inicio', '<=', now())
                ->where('fecha_fin', '>=', now())
                ->count();
        });
    }

    /**
     * Obtiene el total de respuestas de hoy
     *
     * @return int
     */
    public function getRespuestasHoy(): int
    {
        return Cache::remember('respuestas_hoy', 300, function () {
            return Respuesta::whereDate('fecha_respuesta', today())->count();
        });
    }

    /**
     * Obtiene las respuestas de los últimos 7 días
     *
     * @return array
     */
    public function getRespuestasUltimos7Dias(): array
    {
        return Cache::remember('respuestas_ultimos_7_dias', 900, function () {
            $data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $data[] = Respuesta::whereDate('fecha_respuesta', $date)->count();
            }
            return $data;
        });
    }

    /**
     * Obtiene las encuestas por estado
     *
     * @return array
     */
    public function getEncuestasPorEstado(): array
    {
        return Cache::remember('encuestas_por_estado', 1800, function () {
            return [
                'activas' => Encuesta::where('activa', true)
                    ->where('fecha_inicio', '<=', now())
                    ->where('fecha_fin', '>=', now())
                    ->count(),
                'pendientes' => Encuesta::where('activa', true)
                    ->where('fecha_inicio', '>', now())
                    ->count(),
                'expiradas' => Encuesta::where('fecha_fin', '<', now())->count(),
            ];
        });
    }
}
