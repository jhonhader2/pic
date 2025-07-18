<?php

namespace App\Services;

use App\Models\User;
use App\Models\Persona;

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
     * Obtiene todas las estadísticas del sistema
     *
     * @return array
     */
    public function getStats(): array
    {
        return [
            'total_usuarios' => $this->getTotalUsers(),
            'total_personas' => $this->getTotalPersonas(),
            'citas_hoy' => $this->getCitasHoy(),
            'reportes' => $this->getTotalReportes(),
        ];
    }

    /**
     * Obtiene el total de usuarios registrados
     *
     * @return int
     */
    public function getTotalUsers(): int
    {
        return User::count();
    }

    /**
     * Obtiene el total de personas registradas
     *
     * @return int
     */
    public function getTotalPersonas(): int
    {
        return Persona::count();
    }

    /**
     * Obtiene el total de citas para hoy
     * 
     * @return int
     */
    public function getCitasHoy(): int
    {
        // TODO: Implementar cuando el modelo Cita esté disponible
        return 0;
    }

    /**
     * Obtiene el total de reportes
     *
     * @return int
     */
    public function getTotalReportes(): int
    {
        // TODO: Implementar cuando el modelo Reporte esté disponible
        return 0;
    }
}
