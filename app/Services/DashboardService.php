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
    public function getStats($forceRefresh = false): array
    {
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
    }

    /**
     * Obtiene el total de usuarios registrados
     *
     * @return int
     */
    public function getTotalUsers(): int
    {
        return \App\Models\User::count();
    }

    /**
     * Obtiene el total de personas registradas
     *
     * @return int
     */
    public function getTotalPersonas(): int
    {
        return \App\Models\Persona::count();
    }

    /**
     * Obtiene el total de encuestas
     *
     * @return int
     */
    public function getTotalEncuestas($forceRefresh = false): int
    {
        return \App\Models\Encuesta::count();
    }

    /**
     * Obtiene el total de respuestas
     *
     * @return int
     */
    public function getTotalRespuestas(): int
    {
        return \App\Models\Respuesta::count();
    }

    /**
     * Obtiene el total de encuestas activas
     *
     * @return int
     */
    public function getEncuestasActivas(): int
    {
        return \App\Models\Encuesta::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->count();
    }

    /**
     * Obtiene el total de respuestas de hoy
     *
     * @return int
     */
    public function getRespuestasHoy(): int
    {
        return \App\Models\Respuesta::whereDate('fecha_respuesta', today())->count();
    }

    /**
     * Obtiene las respuestas de los últimos 7 días
     *
     * @return array
     */
    public function getRespuestasUltimos7Dias(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = \App\Models\Respuesta::whereDate('fecha_respuesta', $date)->count();
        }
        return $data;
    }

    /**
     * Obtiene las encuestas por estado
     *
     * @return array
     */
    public function getEncuestasPorEstado(): array
    {
        return [
            'activas' => \App\Models\Encuesta::where('activa', true)
                ->where('fecha_inicio', '<=', now())
                ->where('fecha_fin', '>=', now())
                ->count(),
            'pendientes' => \App\Models\Encuesta::where('activa', true)
                ->where('fecha_inicio', '>', now())
                ->count(),
            'expiradas' => \App\Models\Encuesta::where('fecha_fin', '<', now())->count(),
        ];
    }
}
