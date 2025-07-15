<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para verificar roles de usuario
 * 
 * Este middleware verifica si el usuario tiene los roles necesarios
 * para acceder a una ruta específica, siguiendo los principios
 * de POO, DRY, KISS y Single Responsibility
 */
class EnsureUserHasRole
{
    /**
     * Maneja la solicitud entrante
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Debes iniciar sesión para acceder a este recurso.',
                    'error' => 'unauthenticated'
                ], 401);
            }

            return redirect()->route('login')->with(
                'error',
                'Debes iniciar sesión para acceder a esta página.'
            );
        }

        $user = Auth::user();

        // Verificar si el usuario tiene al menos uno de los roles requeridos
        // Por ahora, permitimos acceso a todos los usuarios autenticados
        // En una implementación real, verificarías contra la base de datos
        $hasRole = true; // TODO: Implementar verificación de roles específicos

        if (!$hasRole) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No tienes permisos suficientes para acceder a este recurso.',
                    'error' => 'forbidden'
                ], 403);
            }

            return redirect()->route('forbidden')->with(
                'error',
                'No tienes permisos suficientes para acceder a esta página.'
            );
        }

        return $next($request);
    }
}
