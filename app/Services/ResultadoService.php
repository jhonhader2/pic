<?php

namespace App\Services;

use App\Models\Encuesta;
use App\Models\DetalleRespuesta;
use Carbon\Carbon;

/**
 * Servicio para análisis y exportación de resultados de encuestas
 * 
 * Responsabilidades:
 * - Analizar resultados de encuestas
 * - Generar estadísticas
 * - Exportar datos
 * 
 * Principios aplicados: POO, DRY, KISS, Single Responsibility
 */
class ResultadoService
{
    /**
     * Obtiene participación por día para una encuesta
     *
     * @param Encuesta $encuesta
     * @param int $maxDias
     * @return array
     */
    public function getParticipacionPorDia(Encuesta $encuesta, int $maxDias = 30): array
    {
        $fechaInicio = $encuesta->fecha_inicio;
        $fechaFin = min($encuesta->fecha_fin, now());
        $dias = $fechaInicio->diffInDays($fechaFin) + 1;

        $labels = [];
        $data = [];

        for ($i = 0; $i < min($dias, $maxDias); $i++) {
            $fecha = $fechaInicio->copy()->addDays($i);
            $labels[] = $fecha->format('d/m');

            $respuestasDelDia = $encuesta->respuestas()
                ->whereDate('created_at', $fecha)
                ->count();

            $data[] = $respuestasDelDia;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Obtiene resultados detallados por pregunta
     *
     * @param Encuesta $encuesta
     * @return array
     */
    public function getResultadosPorPregunta(Encuesta $encuesta): array
    {
        $resultados = [];

        foreach ($encuesta->temas as $tema) {
            $resultado = [
                'pregunta' => $tema->name,
                'descripcion' => $tema->pivot->descripcion_pregunta,
                'tipo' => $tema->pivot->tipo_pregunta,
                'opciones' => [],
                'respuestas' => [],
                'promedio' => 0,
                'maximo' => 0,
                'minimo' => 0,
                'distribucion' => []
            ];

            // Obtener respuestas para esta pregunta
            $detalleRespuestas = DetalleRespuesta::where('pregunta_id', $tema->id)
                ->whereHas('respuesta', function ($query) use ($encuesta) {
                    $query->where('encuesta_id', $encuesta->id);
                })
                ->get();

            // Procesar según el tipo de pregunta
            $resultado = array_merge($resultado, $this->procesarResultadoPorTipo($tema, $detalleRespuestas));

            $resultados[] = $resultado;
        }

        return $resultados;
    }

    /**
     * Procesa resultados según el tipo de pregunta
     *
     * @param mixed $tema
     * @param mixed $detalleRespuestas
     * @return array
     */
    private function procesarResultadoPorTipo($tema, $detalleRespuestas): array
    {
        switch ($tema->pivot->tipo_pregunta) {
            case 'seleccion_unica':
            case 'seleccion_multiple':
                return ['opciones' => $this->getOpcionesResultados($tema, $detalleRespuestas)];

            case 'escala':
                return $this->getEscalaResultados($detalleRespuestas);

            case 'numero':
                return $this->getNumeroResultados($detalleRespuestas);

            case 'texto_corto':
            case 'texto_largo':
                return ['respuestas' => $this->getTextoResultados($detalleRespuestas)];

            case 'fecha':
                return ['respuestas' => $this->getFechaResultados($detalleRespuestas)];

            case 'archivo':
                return ['respuestas' => $this->getArchivoResultados($detalleRespuestas)];

            default:
                return [];
        }
    }

    /**
     * Obtiene resultados para preguntas de selección
     *
     * @param mixed $tema
     * @param mixed $detalleRespuestas
     * @return array
     */
    private function getOpcionesResultados($tema, $detalleRespuestas): array
    {
        $opciones = [];
        $totalRespuestas = $detalleRespuestas->count();

        foreach ($tema->parametros as $parametro) {
            $cantidad = $detalleRespuestas->where('parametro_id', $parametro->id)->count();
            $porcentaje = $totalRespuestas > 0 ? round(($cantidad / $totalRespuestas) * 100, 1) : 0;

            $opciones[] = [
                'texto' => $parametro->name,
                'cantidad' => $cantidad,
                'porcentaje' => $porcentaje
            ];
        }

        return $opciones;
    }

    /**
     * Obtiene resultados para preguntas de escala
     *
     * @param mixed $detalleRespuestas
     * @return array
     */
    private function getEscalaResultados($detalleRespuestas): array
    {
        $valores = $detalleRespuestas->pluck('respuesta')->filter()->map(function ($valor) {
            return (int) $valor;
        });

        $distribucion = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribucion[] = [
                'valor' => $i,
                'cantidad' => $valores->filter(function ($valor) use ($i) {
                    return $valor == $i;
                })->count()
            ];
        }

        return [
            'promedio' => $valores->count() > 0 ? round($valores->avg(), 1) : 0,
            'distribucion' => $distribucion
        ];
    }

    /**
     * Obtiene resultados para preguntas numéricas
     *
     * @param mixed $detalleRespuestas
     * @return array
     */
    private function getNumeroResultados($detalleRespuestas): array
    {
        $valores = $detalleRespuestas->pluck('respuesta')->filter()->map(function ($valor) {
            return (float) $valor;
        });

        return [
            'promedio' => $valores->count() > 0 ? round($valores->avg(), 2) : 0,
            'maximo' => $valores->count() > 0 ? $valores->max() : 0,
            'minimo' => $valores->count() > 0 ? $valores->min() : 0
        ];
    }

    /**
     * Obtiene resultados para preguntas de texto
     *
     * @param mixed $detalleRespuestas
     * @return array
     */
    private function getTextoResultados($detalleRespuestas): array
    {
        return $detalleRespuestas->map(function ($detalle) {
            return [
                'respuesta' => $detalle->respuesta,
                'fecha' => $detalle->created_at->format('d/m/Y H:i'),
                'fecha_formateada' => $detalle->created_at->format('d/m/Y H:i')
            ];
        })->toArray();
    }

    /**
     * Obtiene resultados para preguntas de fecha
     *
     * @param mixed $detalleRespuestas
     * @return array
     */
    private function getFechaResultados($detalleRespuestas): array
    {
        return $detalleRespuestas->map(function ($detalle) {
            return [
                'respuesta' => $detalle->respuesta,
                'fecha' => Carbon::parse($detalle->respuesta)->format('d/m/Y'),
                'fecha_formateada' => Carbon::parse($detalle->respuesta)->format('d/m/Y')
            ];
        })->toArray();
    }

    /**
     * Obtiene resultados para preguntas de archivo
     *
     * @param mixed $detalleRespuestas
     * @return array
     */
    private function getArchivoResultados($detalleRespuestas): array
    {
        return $detalleRespuestas->map(function ($detalle) {
            return [
                'respuesta' => $detalle->ruta_archivo,
                'nombre' => basename($detalle->ruta_archivo),
                'fecha' => $detalle->created_at->format('d/m/Y H:i'),
                'fecha_formateada' => $detalle->created_at->format('d/m/Y H:i')
            ];
        })->toArray();
    }

    /**
     * Genera estadísticas generales de la encuesta
     *
     * @param Encuesta $encuesta
     * @return array
     */
    public function getEstadisticasGenerales(Encuesta $encuesta): array
    {
        $totalRespuestas = $encuesta->respuestas()->count();
        $totalPreguntas = $encuesta->temas()->count();
        $totalPersonasAsignadas = $encuesta->personas()->count();
        $porcentajeParticipacion = $totalPersonasAsignadas > 0
            ? round(($totalRespuestas / $totalPersonasAsignadas) * 100, 1)
            : 0;
        $diasActiva = $encuesta->fecha_inicio->diffInDays(now());

        return [
            'total_respuestas' => $totalRespuestas,
            'total_preguntas' => $totalPreguntas,
            'total_personas_asignadas' => $totalPersonasAsignadas,
            'porcentaje_participacion' => $porcentajeParticipacion,
            'dias_activa' => $diasActiva,
        ];
    }

    /**
     * Exporta resultados a formato específico
     *
     * @param Encuesta $encuesta
     * @param string $formato
     * @return mixed
     */
    public function exportarResultados(Encuesta $encuesta, string $formato = 'json')
    {
        $estadisticas = $this->getEstadisticasGenerales($encuesta);
        $participacionPorDia = $this->getParticipacionPorDia($encuesta);
        $resultadosPreguntas = $this->getResultadosPorPregunta($encuesta);

        $data = [
            'encuesta' => [
                'id' => $encuesta->id,
                'titulo' => $encuesta->titulo,
                'descripcion' => $encuesta->descripcion,
                'fecha_inicio' => $encuesta->fecha_inicio->format('Y-m-d'),
                'fecha_fin' => $encuesta->fecha_fin->format('Y-m-d'),
            ],
            'estadisticas' => $estadisticas,
            'participacion_por_dia' => $participacionPorDia,
            'resultados_por_pregunta' => $resultadosPreguntas,
            'fecha_exportacion' => now()->format('Y-m-d H:i:s')
        ];

        switch ($formato) {
            case 'json':
                return response()->json($data);
            case 'pdf':
                return $this->exportToPdf($data);
            case 'excel':
                return $this->exportToExcel($data);
            default:
                return response()->json($data);
        }
    }

    /**
     * Exporta a PDF (placeholder)
     *
     * @param array $data
     * @return mixed
     */
    private function exportToPdf(array $data)
    {
        // Implementar exportación a PDF
        return response()->json(['message' => 'Exportación PDF no implementada aún', 'data' => $data]);
    }

    /**
     * Exporta a Excel (placeholder)
     *
     * @param array $data
     * @return mixed
     */
    private function exportToExcel(array $data)
    {
        // Implementar exportación a Excel
        return response()->json(['message' => 'Exportación Excel no implementada aún', 'data' => $data]);
    }
}
