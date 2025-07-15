<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para manejo de errores de autenticación
 * 
 * Este controlador centraliza la lógica de manejo de errores
 * relacionados con autenticación y acceso no autorizado,
 * siguiendo los principios de POO, DRY, KISS y Single Responsibility
 */
class ErrorController extends Controller
{
    /**
     * Muestra la página de acceso no autorizado (401)
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function unauthorized(Request $request)
    {
        // Log del intento de acceso no autorizado
        Log::warning('Intento de acceso no autorizado', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->url(),
            'referer' => $request->header('referer'),
            'user_id' => Auth::id() ?? 'guest'
        ]);

        return view('errors.401');
    }

    /**
     * Muestra la página de acceso prohibido (403)
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function forbidden(Request $request)
    {
        // Log del intento de acceso prohibido
        Log::warning('Intento de acceso prohibido', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->url(),
            'user_id' => Auth::id(),
            'user_roles' => Auth::user() ? Auth::user()->roles : null
        ]);

        return view('errors.403');
    }

    /**
     * Muestra la página de recurso no encontrado (404)
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function notFound(Request $request)
    {
        // Log del recurso no encontrado
        Log::info('Recurso no encontrado', [
            'ip' => $request->ip(),
            'url' => $request->url(),
            'user_agent' => $request->userAgent()
        ]);

        return view('errors.404');
    }
}
