<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador para el Panel de Control (Dashboard)
 * 
 * Responsabilidades:
 * - Manejar las peticiones HTTP del dashboard
 * - Coordinar con el servicio para obtener datos
 * 
 * Principios aplicados: POO, DRY, KISS, Single Responsibility
 */
class DashboardController extends Controller
{
    /**
     * Constructor con inyección de dependencias
     *
     * @param DashboardService $dashboardService
     */
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    /**
     * Muestra el panel de control principal
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $stats = $this->dashboardService->getStats();

        return view('dashboard', compact('stats'));
    }
}
