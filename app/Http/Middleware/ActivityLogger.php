<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo registrar si hay un usuario autenticado
        if (Auth::check()) {
            $user = Auth::user();
            $method = $request->method();
            $url = $request->fullUrl();
            $ip = $request->ip();
            $userAgent = $request->userAgent();

            // Determinar el tipo de actividad basado en la ruta
            $activity = $this->determineActivity($request);

            if ($activity) {
                Log::info('Actividad de Usuario', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'activity' => $activity,
                    'method' => $method,
                    'url' => $url,
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                    'timestamp' => now()->toISOString(),
                ]);
            }
        }

        return $response;
    }

    /**
     * Determina el tipo de actividad basado en la ruta
     */
    private function determineActivity(Request $request): ?string
    {
        $route = $request->route();
        if (!$route) return null;

        $routeName = $route->getName();
        $method = $request->method();

        // Actividades de encuestas
        if (str_contains($routeName, 'encuestas')) {
            if ($method === 'POST' && str_contains($routeName, 'store')) {
                return 'Crear Encuesta';
            }
            if ($method === 'PUT' && str_contains($routeName, 'update')) {
                return 'Actualizar Encuesta';
            }
            if ($method === 'DELETE' && str_contains($routeName, 'destroy')) {
                return 'Eliminar Encuesta';
            }
            if (str_contains($routeName, 'responder')) {
                return 'Responder Encuesta';
            }
        }

        // Actividades de personas
        if (str_contains($routeName, 'personas')) {
            if ($method === 'POST' && str_contains($routeName, 'store')) {
                return 'Crear Persona';
            }
            if ($method === 'PUT' && str_contains($routeName, 'update')) {
                return 'Actualizar Persona';
            }
            if ($method === 'DELETE' && str_contains($routeName, 'destroy')) {
                return 'Eliminar Persona';
            }
        }

        // Actividades de autenticación
        if (str_contains($routeName, 'login')) {
            return 'Iniciar Sesión';
        }
        if (str_contains($routeName, 'logout')) {
            return 'Cerrar Sesión';
        }
        if (str_contains($routeName, 'register')) {
            return 'Registrar Usuario';
        }

        return null;
    }
}
