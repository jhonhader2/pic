<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * Controlador base con métodos comunes para todos los controladores
 * 
 * Responsabilidades:
 * - Proporcionar métodos comunes para transacciones
 * - Manejar respuestas estándar
 * - Validación común de archivos
 * 
 * Principios aplicados: POO, DRY, KISS, Single Responsibility
 */
abstract class BaseController extends Controller
{
    /**
     * Ejecuta una operación dentro de una transacción de base de datos
     *
     * @param callable $operation
     * @param string $successMessage
     * @param string $errorMessage
     * @param string $redirectRoute
     * @param array $routeParams
     * @return RedirectResponse
     */
    protected function executeTransaction(
        callable $operation,
        string $successMessage,
        string $errorMessage,
        string $redirectRoute = null,
        array $routeParams = []
    ): RedirectResponse {
        try {
            DB::beginTransaction();

            $result = $operation();

            DB::commit();

            if ($redirectRoute) {
                return redirect()->route($redirectRoute, $routeParams)
                    ->with('success', $successMessage);
            }

            return redirect()->back()->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage . ': ' . $e->getMessage());
        }
    }

    /**
     * Ejecuta una operación y retorna respuesta JSON
     *
     * @param callable $operation
     * @param string $successMessage
     * @param string $errorMessage
     * @param int $errorCode
     * @return JsonResponse
     */
    protected function executeJsonOperation(
        callable $operation,
        string $successMessage,
        string $errorMessage,
        int $errorCode = 500
    ): JsonResponse {
        try {
            $result = $operation();

            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage . ': ' . $e->getMessage()
            ], $errorCode);
        }
    }

    /**
     * Valida archivos según configuración estándar
     *
     * @param Request $request
     * @param string $fieldName
     * @param array $allowedMimes
     * @param int $maxSize
     * @return array
     */
    protected function validateFile(
        Request $request,
        string $fieldName,
        array $allowedMimes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
        int $maxSize = 5120
    ): array {
        return $request->validate([
            $fieldName => [
                'file',
                'mimes:' . implode(',', $allowedMimes),
                'max:' . $maxSize,
            ]
        ], [
            $fieldName . '.file' => 'El archivo es requerido.',
            $fieldName . '.mimes' => 'Solo se permiten archivos: ' . implode(', ', $allowedMimes),
            $fieldName . '.max' => 'El archivo no puede superar ' . ($maxSize / 1024) . 'MB.',
        ]);
    }

    /**
     * Genera respuesta de error estándar
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    /**
     * Genera respuesta de éxito estándar
     *
     * @param string $message
     * @param mixed $data
     * @return JsonResponse
     */
    protected function successResponse(string $message, $data = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }
}
