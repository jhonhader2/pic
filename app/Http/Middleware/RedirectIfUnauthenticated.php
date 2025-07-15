<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para manejar acceso no autorizado a rutas protegidas
 * 
 * Este middleware verifica si el usuario está autenticado y redirige
 * con un mensaje apropiado si no lo está, siguiendo los principios
 * de POO, DRY, KISS y Single Responsibility
 */
class RedirectIfUnauthenticated
{
    /**
     * Maneja la solicitud entrante
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            // Determinar el tipo de respuesta basado en la solicitud
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Acceso no autorizado. Debes iniciar sesión para continuar.',
                    'error' => 'unauthenticated'
                ], 401);
            }

            // Redirigir a login con mensaje de error
            return redirect()->route('login')->with(
                'error',
                'Debes iniciar sesión para acceder a esta página. Por favor, ingresa tus credenciales.'
            );
        }

        return $next($request);
    }
}
