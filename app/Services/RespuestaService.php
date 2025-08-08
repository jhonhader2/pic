<?php

namespace App\Services;

use App\Models\Encuesta;
use App\Models\Respuesta;
use App\Models\DetalleRespuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Servicio para manejo de respuestas de encuestas
 * 
 * Responsabilidades:
 * - Procesar respuestas de encuestas
 * - Validar respuestas
 * - Almacenar respuestas en base de datos
 * 
 * Principios aplicados: POO, DRY, KISS, Single Responsibility
 */
class RespuestaService
{
    public function __construct(
        private FileService $fileService
    ) {}

    /**
     * Procesa y almacena una respuesta de encuesta
     *
     * @param Request $request
     * @param Encuesta $encuesta
     * @return Respuesta
     * @throws \Exception
     */
    public function procesarRespuesta(Request $request, Encuesta $encuesta): Respuesta
    {
        // Validar que el usuario esté autenticado
        if (!Auth::check()) {
            throw new \Exception('Debe iniciar sesión para responder la encuesta.');
        }

        // Validar datos básicos
        $request->validate([
            'respuestas' => 'required|array|min:1',
            'respuestas.*' => 'nullable',
        ], [
            'respuestas.required' => 'Debe responder al menos una pregunta.',
            'respuestas.min' => 'Debe responder al menos una pregunta.',
        ]);

        // Crear la respuesta principal
        $respuesta = Respuesta::create([
            'encuesta_id' => $encuesta->id,
            'usuario_id' => Auth::id(),
            'fecha_respuesta' => now(),
        ]);

        // Procesar cada respuesta individual
        foreach ($request->respuestas as $temaId => $valor) {
            $this->procesarRespuestaIndividual($request, $respuesta, $temaId, $valor);
        }

        return $respuesta;
    }

    /**
     * Procesa una respuesta individual
     *
     * @param Request $request
     * @param Respuesta $respuesta
     * @param string $temaId
     * @param mixed $valor
     * @return void
     */
    private function procesarRespuestaIndividual(Request $request, Respuesta $respuesta, string $temaId, $valor): void
    {
        if ($request->hasFile("respuestas.{$temaId}")) {
            $this->procesarArchivo($request, $respuesta, $temaId);
        } else {
            $this->procesarTexto($respuesta, $temaId, $valor);
        }
    }

    /**
     * Procesa un archivo subido como respuesta
     *
     * @param Request $request
     * @param Respuesta $respuesta
     * @param string $temaId
     * @return void
     */
    private function procesarArchivo(Request $request, Respuesta $respuesta, string $temaId): void
    {
        $archivo = $request->file("respuestas.{$temaId}");

        // Validar archivo
        $errors = $this->fileService->validateFile($archivo);
        if (!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }

        // Subir archivo
        $rutaArchivo = $this->fileService->uploadFile($archivo, 'respuestas');

        // Crear detalle de respuesta
        DetalleRespuesta::create([
            'respuesta_id' => $respuesta->id,
            'pregunta_id' => $temaId,
            'ruta_archivo' => $rutaArchivo,
            'respuesta' => basename($rutaArchivo),
        ]);
    }

    /**
     * Procesa texto, números y otros tipos de respuesta
     *
     * @param Respuesta $respuesta
     * @param string $temaId
     * @param mixed $valor
     * @return void
     */
    private function procesarTexto(Respuesta $respuesta, string $temaId, $valor): void
    {
        $valorRespuesta = is_array($valor) ? implode(',', $valor) : $valor;

        // Determinar el tipo de valor y guardarlo en el campo apropiado
        if (is_numeric($valorRespuesta)) {
            DetalleRespuesta::create([
                'respuesta_id' => $respuesta->id,
                'pregunta_id' => $temaId,
                'valor_numerico' => $valorRespuesta,
                'respuesta' => $valorRespuesta,
            ]);
        } else {
            DetalleRespuesta::create([
                'respuesta_id' => $respuesta->id,
                'pregunta_id' => $temaId,
                'respuesta' => $valorRespuesta,
            ]);
        }
    }

    /**
     * Elimina todas las respuestas de una encuesta
     *
     * @param Encuesta $encuesta
     * @return int
     */
    public function eliminarRespuestasEncuesta(Encuesta $encuesta): int
    {
        $respuestas = $encuesta->respuestas;
        $archivosEliminados = 0;

        foreach ($respuestas as $respuesta) {
            $archivosEliminados += $this->eliminarArchivosRespuesta($respuesta);
            $respuesta->delete();
        }

        return $archivosEliminados;
    }

    /**
     * Elimina los archivos asociados a una respuesta
     *
     * @param Respuesta $respuesta
     * @return int
     */
    public function eliminarArchivosRespuesta(Respuesta $respuesta): int
    {
        $detalles = DetalleRespuesta::where('respuesta_id', $respuesta->id)
            ->whereNotNull('ruta_archivo')
            ->get();

        $archivosEliminados = 0;

        foreach ($detalles as $detalle) {
            if ($this->fileService->deleteFile($detalle->ruta_archivo)) {
                $archivosEliminados++;
            }
        }

        return $archivosEliminados;
    }

    /**
     * Obtiene estadísticas de respuestas para una encuesta
     *
     * @param Encuesta $encuesta
     * @return array
     */
    public function getEstadisticasRespuestas(Encuesta $encuesta): array
    {
        $totalRespuestas = $encuesta->respuestas()->count();
        $totalPreguntas = $encuesta->temas()->count();
        $totalPersonasAsignadas = $encuesta->personas()->count();
        $porcentajeParticipacion = $totalPersonasAsignadas > 0
            ? round(($totalRespuestas / $totalPersonasAsignadas) * 100, 1)
            : 0;

        return [
            'total_respuestas' => $totalRespuestas,
            'total_preguntas' => $totalPreguntas,
            'total_personas_asignadas' => $totalPersonasAsignadas,
            'porcentaje_participacion' => $porcentajeParticipacion,
            'dias_activa' => $encuesta->fecha_inicio->diffInDays(now()),
        ];
    }

    /**
     * Verifica si un usuario ya respondió una encuesta
     *
     * @param Encuesta $encuesta
     * @param int $userId
     * @return bool
     */
    public function usuarioYaRespondio(Encuesta $encuesta, int $userId): bool
    {
        return $encuesta->respuestas()
            ->where('usuario_id', $userId)
            ->exists();
    }
}
