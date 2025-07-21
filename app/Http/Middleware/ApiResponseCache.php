<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo cachear peticiones GET
        if (!$request->isMethod('GET')) {
            return $next($request);
        }

        // Generar clave de cache única
        $cacheKey = $this->generateCacheKey($request);

        // Intentar obtener respuesta del cache
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Procesar la petición
        $response = $next($request);

        // Cachear la respuesta si es exitosa
        if ($response->getStatusCode() === 200) {
            $ttl = $this->getCacheTTL($request);
            Cache::put($cacheKey, $response, $ttl);
        }

        return $response;
    }

    /**
     * Genera una clave única de cache para la petición
     *
     * @param Request $request
     * @return string
     */
    private function generateCacheKey(Request $request): string
    {
        $data = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => $request->user()?->id ?? 'guest',
            'query_params' => $request->query(),
        ];

        return 'api_response_' . md5(serialize($data));
    }

    /**
     * Obtiene el tiempo de vida del cache basado en la ruta
     *
     * @param Request $request
     * @return int
     */
    private function getCacheTTL(Request $request): int
    {
        $route = $request->route();
        if (!$route) return 300; // 5 minutos por defecto

        $routeName = $route->getName();

        // TTL específico por ruta
        $ttlMap = [
            'encuestas.index' => 900,      // 15 minutos
            'personas.index' => 1800,      // 30 minutos
            'dashboard' => 300,            // 5 minutos
        ];

        return $ttlMap[$routeName] ?? 300;
    }
}
