<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Tema;
use App\Models\Persona;
use App\Models\Respuesta;
use App\Models\DetalleRespuesta;
use App\Helpers\TipoPreguntaHelper;
use App\Http\Requests\EncuestaRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EncuestaController extends Controller
{
    /**
     * Constructor con inyección de dependencias
     */
    public function __construct(
        private NotificationService $notificationService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Encuesta::with(['creador', 'personas', 'temas', 'respuestas']);

        // Filtro por estado
        if ($request->has('estado') && $request->estado !== '') {
            switch ($request->estado) {
                case 'activas':
                    $query->activas();
                    break;
                case 'disponibles':
                    $query->disponibles();
                    break;
                case 'expiradas':
                    $query->where('fecha_fin', '<', now());
                    break;
                case 'pendientes':
                    $query->where('fecha_inicio', '>', now());
                    break;
            }
        }

        // Filtro por creador
        if ($request->has('creador') && $request->creador !== '') {
            $query->porCreador($request->creador);
        }

        // Búsqueda por título
        if ($request->has('buscar') && $request->buscar !== '') {
            $query->where('titulo', 'ilike', '%' . $request->buscar . '%');
        }

        // Ordenamiento
        $orden = $request->get('orden', 'created_at');
        $direccion = $request->get('direccion', 'desc');
        $query->orderBy($orden, $direccion);

        $encuestas = $query->paginate(10)->withQueryString();

        // Calcular totales para cada encuesta
        $encuestas->getCollection()->transform(function ($encuesta) {
            $encuesta->total_respuestas = $encuesta->respuestas()->count();
            $encuesta->total_personas_asignadas = $encuesta->personas()->count();
            return $encuesta;
        });

        return view('encuestas.index', compact('encuestas'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $personas = Persona::orderBy('primer_nombre')->get();

        return view('encuestas.create', compact('personas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EncuestaRequest $request)
    {

        try {
            DB::beginTransaction();

            // Crear la encuesta
            $encuesta = Encuesta::create([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'activa' => $request->has('activa'),
                'created_by' => Auth::id(),
            ]);

            // Asignar personas a la encuesta (si se seleccionaron)
            if ($request->has('personas') && !empty($request->personas)) {
                $personasData = [];
                foreach ($request->personas as $personaId) {
                    $personasData[$personaId] = ['created_by' => Auth::id()];
                }
                $encuesta->personas()->attach($personasData);
            }

            DB::commit();

            // Enviar notificaciones
            $this->notificationService->notificarEncuestaCreada($encuesta);

            // Redirigir a la configuración de preguntas
            return redirect()->route('encuestas.preguntas.create', $encuesta)
                ->with('success', 'Encuesta creada. Ahora configure las preguntas.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al crear la encuesta: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Encuesta $encuesta)
    {
        $encuesta->load(['temas.parametros', 'personas']);
        $todasLasPersonas = Persona::orderBy('primer_nombre')->get();

        // Calcular totales
        $encuesta->total_respuestas = $encuesta->respuestas()->count();
        $encuesta->total_personas_asignadas = $encuesta->personas()->count();

        return view('encuestas.show', compact('encuesta', 'todasLasPersonas'));
    }

    /**
     * Show the form for responding to an encuesta.
     */
    public function responder(Encuesta $encuesta)
    {
        // Verificar si la encuesta está disponible
        if (!$encuesta->estaDisponible()) {
            return redirect()->route('encuestas.index')
                ->with('error', 'Esta encuesta no está disponible para responder.');
        }

        $encuesta->load(['temas.parametros']);

        return view('encuestas.responder', compact('encuesta'));
    }

    /**
     * Show the form for configuring questions.
     */
    public function createPreguntas(Encuesta $encuesta)
    {
        $temas = Tema::where('status', true)->orderBy('name')->get();
        $tiposPregunta = TipoPreguntaHelper::getTiposDisponibles();

        return view('encuestas.preguntas.create', compact('encuesta', 'temas', 'tiposPregunta'));
    }

    /**
     * Store the questions configuration.
     */
    public function storePreguntas(Request $request, Encuesta $encuesta)
    {
        // Validación de datos
        $request->validate([
            'preguntas' => 'required|array|min:1',
            'preguntas.*.titulo' => 'required|string|max:255',
            'preguntas.*.tipo' => 'required|in:' . implode(',', array_keys(TipoPreguntaHelper::getTiposDisponibles())),
            'preguntas.*.descripcion' => 'nullable|string|max:500',
            'preguntas.*.requerida' => 'boolean',
            'preguntas.*.opciones' => 'nullable|string', // JSON string para opciones
        ], [
            'preguntas.required' => 'Debe agregar al menos una pregunta.',
            'preguntas.min' => 'Debe agregar al menos una pregunta.',
            'preguntas.*.titulo.required' => 'El título de la pregunta es obligatorio.',
            'preguntas.*.tipo.required' => 'Debe seleccionar un tipo de pregunta.',
            'preguntas.*.tipo.in' => 'El tipo de pregunta seleccionado no es válido.',
        ]);

        try {
            DB::beginTransaction();

            $orden = 1;
            $temasData = [];

            // Procesar cada pregunta
            foreach ($request->preguntas as $preguntaData) {
                // Crear un tema para cada pregunta
                $tema = Tema::create([
                    'name' => $preguntaData['titulo'],
                    'descripcion' => $preguntaData['descripcion'] ?? null,
                    'status' => true,
                    'user_create_id' => Auth::id(),
                    'user_edit_id' => Auth::id(),
                ]);

                // Procesar opciones si las hay
                $opciones = [];
                if (isset($preguntaData['opciones']) && !empty($preguntaData['opciones'])) {
                    $opciones = json_decode($preguntaData['opciones'], true);

                    // Crear parámetros para las opciones
                    if (is_array($opciones)) {
                        foreach ($opciones as $opcion) {
                            \App\Models\Parametro::create([
                                'name' => $opcion,
                                'tema_id' => $tema->id,
                                'status' => true,
                                'user_create_id' => Auth::id(),
                                'user_edit_id' => Auth::id(),
                            ]);
                        }
                    }
                }

                $temasData[$tema->id] = [
                    'tipo_pregunta' => $preguntaData['tipo'],
                    'requerida' => isset($preguntaData['requerida']),
                    'descripcion_pregunta' => $preguntaData['descripcion'] ?? null,
                    'opciones_personalizadas' => !empty($opciones) ? json_encode($opciones) : null,
                    'orden' => $orden++
                ];
            }

            // Asignar temas a la encuesta
            if (!empty($temasData)) {
                $encuesta->temas()->attach($temasData);
            }

            DB::commit();

            return redirect()->route('encuestas.show', $encuesta)
                ->with('success', 'Preguntas configuradas exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al configurar las preguntas: ' . $e->getMessage());
        }
    }

    /**
     * Store a survey response.
     */
    public function storeRespuesta(Request $request, Encuesta $encuesta)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión para responder la encuesta.');
        }

        // Validación de datos de respuesta
        $request->validate([
            'respuestas' => 'required|array|min:1',
            'respuestas.*' => 'nullable',
        ], [
            'respuestas.required' => 'Debe responder al menos una pregunta.',
            'respuestas.min' => 'Debe responder al menos una pregunta.',
        ]);

        // Validación específica para archivos
        foreach ($request->respuestas as $temaId => $respuesta) {
            if ($request->hasFile("respuestas.{$temaId}")) {
                $request->validate([
                    "respuestas.{$temaId}" => [
                        'file',
                        'mimes:pdf,doc,docx,jpg,jpeg,png',
                        'max:5120', // 5MB máximo
                    ]
                ], [
                    "respuestas.{$temaId}.file" => 'El archivo es requerido.',
                    "respuestas.{$temaId}.mimes" => 'Solo se permiten archivos PDF, DOC, DOCX, JPG, PNG.',
                    "respuestas.{$temaId}.max" => 'El archivo no puede superar 5MB.',
                ]);
            }
        }

        try {
            DB::beginTransaction();

            // Crear la respuesta principal
            $respuesta = Respuesta::create([
                'encuesta_id' => $encuesta->id,
                'usuario_id' => Auth::id(),
                'fecha_respuesta' => now(),
            ]);

            // Procesar cada respuesta
            foreach ($request->respuestas as $temaId => $valor) {
                if ($request->hasFile("respuestas.{$temaId}")) {
                    // Manejar archivo
                    $archivo = $request->file("respuestas.{$temaId}");
                    $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                    $rutaArchivo = $archivo->storeAs('respuestas', $nombreArchivo, 'public');

                    DetalleRespuesta::create([
                        'respuesta_id' => $respuesta->id,
                        'pregunta_id' => $temaId,
                        'ruta_archivo' => $rutaArchivo,
                        'respuesta' => $nombreArchivo,
                    ]);
                } else {
                    // Manejar texto, números, etc.
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
            }

            DB::commit();

            // Enviar notificación de respuesta recibida
            $this->notificationService->notificarRespuestaRecibida($encuesta, $respuesta);

            return redirect()->route('encuestas.index')
                ->with('success', '¡Respuesta enviada exitosamente! Gracias por participar en la encuesta.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al enviar la respuesta: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Encuesta $encuesta)
    {
        $personas = Persona::orderBy('primer_nombre')->get();

        // Cargar relaciones necesarias
        $encuesta->load(['personas', 'temas']);

        return view('encuestas.edit', compact('encuesta', 'personas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EncuestaRequest $request, Encuesta $encuesta)
    {
        try {
            DB::beginTransaction();

            // Actualizar datos básicos de la encuesta
            $encuesta->update([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'activa' => $request->has('activa'),
                'updated_by' => Auth::id(),
            ]);

            // Actualizar personas asignadas
            $personasData = [];
            if ($request->has('personas') && !empty($request->personas)) {
                foreach ($request->personas as $personaId) {
                    $personasData[$personaId] = ['created_by' => Auth::id()];
                }
            }

            // Sincronizar personas (elimina las que no están en la lista y agrega las nuevas)
            $encuesta->personas()->sync($personasData);

            DB::commit();

            return redirect()->route('encuestas.show', $encuesta)
                ->with('success', 'Encuesta actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al actualizar la encuesta: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Encuesta $encuesta)
    {
        try {
            DB::beginTransaction();

            // Eliminar respuestas asociadas
            $respuestas = Respuesta::where('encuesta_id', $encuesta->id)->get();
            foreach ($respuestas as $respuesta) {
                // Eliminar detalles de respuesta
                DetalleRespuesta::where('respuesta_id', $respuesta->id)->delete();
                // Eliminar archivos físicos si existen
                $this->eliminarArchivosRespuesta($respuesta);
            }
            Respuesta::where('encuesta_id', $encuesta->id)->delete();

            // Desvincular temas y personas
            $encuesta->temas()->detach();
            $encuesta->personas()->detach();

            // Eliminar la encuesta
            $encuesta->delete();

            DB::commit();

            return redirect()->route('encuestas.index')
                ->with('success', 'Encuesta eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('encuestas.index')
                ->with('error', 'Error al eliminar la encuesta: ' . $e->getMessage());
        }
    }

    /**
     * Elimina los archivos físicos asociados a una respuesta
     */
    private function eliminarArchivosRespuesta(Respuesta $respuesta): void
    {
        $detalles = DetalleRespuesta::where('respuesta_id', $respuesta->id)
            ->whereNotNull('valor_archivo')
            ->get();

        foreach ($detalles as $detalle) {
            if ($detalle->valor_archivo && Storage::disk('public')->exists($detalle->valor_archivo)) {
                Storage::disk('public')->delete($detalle->valor_archivo);
            }
        }
    }

    /**
     * Update the assigned persons to the survey.
     */
    public function updatePersonas(Request $request, Encuesta $encuesta)
    {
        $request->validate([
            'personas' => 'nullable|array',
            'personas.*' => 'exists:personas,id',
        ], [
            'personas.*.exists' => 'Una de las personas seleccionadas no existe.',
        ]);

        try {
            DB::beginTransaction();

            $personasData = [];
            if ($request->has('personas') && !empty($request->personas)) {
                foreach ($request->personas as $personaId) {
                    $personasData[$personaId] = ['created_by' => Auth::id()];
                }
            }

            // Sincronizar personas (elimina las que no están en la lista y agrega las nuevas)
            $encuesta->personas()->sync($personasData);

            DB::commit();

            return redirect()->route('encuestas.show', $encuesta)
                ->with('success', 'Personas asignadas actualizadas exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al actualizar las personas asignadas: ' . $e->getMessage());
        }
    }

    /**
     * Remove a specific person from the survey.
     */
    public function detachPersona(Request $request, Encuesta $encuesta)
    {
        $request->validate([
            'persona_id' => 'required|exists:personas,id',
        ], [
            'persona_id.required' => 'ID de persona es requerido.',
            'persona_id.exists' => 'La persona especificada no existe.',
        ]);

        try {
            $personaId = $request->persona_id;
            $encuesta->personas()->detach($personaId);

            return redirect()->route('encuestas.show', $encuesta)
                ->with('success', 'Persona removida de la encuesta exitosamente.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al remover la persona de la encuesta: ' . $e->getMessage());
        }
    }

    /**
     * Show survey results dashboard.
     */
    public function resultados(Encuesta $encuesta)
    {
        // Cargar relaciones necesarias
        $encuesta->load(['temas.parametros', 'respuestas.detalleRespuestas']);

        // Estadísticas generales
        $totalRespuestas = $encuesta->respuestas()->count();
        $totalPreguntas = $encuesta->temas()->count();
        $totalPersonasAsignadas = $encuesta->personas()->count();
        $porcentajeParticipacion = $totalPersonasAsignadas > 0 ? round(($totalRespuestas / $totalPersonasAsignadas) * 100, 1) : 0;
        $diasActiva = $encuesta->fecha_inicio->diffInDays(now());

        // Participación por día (últimos 30 días)
        $participacionPorDia = $this->getParticipacionPorDia($encuesta);

        // Resultados por pregunta
        $resultadosPreguntas = $this->getResultadosPorPregunta($encuesta);

        return view('encuestas.resultados', compact(
            'encuesta',
            'totalRespuestas',
            'totalPreguntas',
            'porcentajeParticipacion',
            'diasActiva',
            'participacionPorDia',
            'resultadosPreguntas'
        ));
    }

    /**
     * Export survey results.
     */
    public function exportResultados(Encuesta $encuesta, $formato = 'pdf')
    {
        // Cargar relaciones necesarias
        $encuesta->load(['temas.parametros', 'respuestas.detalleRespuestas']);

        $totalRespuestas = $encuesta->respuestas()->count();
        $totalPreguntas = $encuesta->temas()->count();
        $totalPersonasAsignadas = $encuesta->personas()->count();
        $porcentajeParticipacion = $totalPersonasAsignadas > 0 ? round(($totalRespuestas / $totalPersonasAsignadas) * 100, 1) : 0;
        $diasActiva = $encuesta->fecha_inicio->diffInDays(now());
        $participacionPorDia = $this->getParticipacionPorDia($encuesta);
        $resultadosPreguntas = $this->getResultadosPorPregunta($encuesta);

        $data = [
            'encuesta' => $encuesta,
            'totalRespuestas' => $totalRespuestas,
            'totalPreguntas' => $totalPreguntas,
            'porcentajeParticipacion' => $porcentajeParticipacion,
            'diasActiva' => $diasActiva,
            'participacionPorDia' => $participacionPorDia,
            'resultadosPreguntas' => $resultadosPreguntas
        ];

        if ($formato === 'pdf') {
            return $this->exportToPdf($data);
        } else {
            return $this->exportToExcel($data);
        }
    }

    /**
     * Get participation by day data.
     */
    private function getParticipacionPorDia(Encuesta $encuesta)
    {
        $fechaInicio = $encuesta->fecha_inicio;
        $fechaFin = min($encuesta->fecha_fin, now());
        $dias = $fechaInicio->diffInDays($fechaFin) + 1;

        $labels = [];
        $data = [];

        for ($i = 0; $i < min($dias, 30); $i++) {
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
     * Get results by question.
     */
    private function getResultadosPorPregunta(Encuesta $encuesta)
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

            switch ($tema->pivot->tipo_pregunta) {
                case 'seleccion_unica':
                case 'seleccion_multiple':
                    $resultado['opciones'] = $this->getOpcionesResultados($tema, $detalleRespuestas);
                    break;

                case 'escala':
                    $resultado = array_merge($resultado, $this->getEscalaResultados($detalleRespuestas));
                    break;

                case 'numero':
                    $resultado = array_merge($resultado, $this->getNumeroResultados($detalleRespuestas));
                    break;

                case 'texto_corto':
                case 'texto_largo':
                    $resultado['respuestas'] = $this->getTextoResultados($detalleRespuestas);
                    break;

                case 'fecha':
                    $resultado['respuestas'] = $this->getFechaResultados($detalleRespuestas);
                    break;

                case 'archivo':
                    $resultado['respuestas'] = $this->getArchivoResultados($detalleRespuestas);
                    break;
            }

            $resultados[] = $resultado;
        }

        return $resultados;
    }

    /**
     * Get options results for selection questions.
     */
    private function getOpcionesResultados($tema, $detalleRespuestas)
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
     * Get scale results.
     */
    private function getEscalaResultados($detalleRespuestas)
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
     * Get number results.
     */
    private function getNumeroResultados($detalleRespuestas)
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
     * Get text results.
     */
    private function getTextoResultados($detalleRespuestas)
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
     * Get date results.
     */
    private function getFechaResultados($detalleRespuestas)
    {
        return $detalleRespuestas->map(function ($detalle) {
            return [
                'respuesta' => $detalle->respuesta,
                'fecha' => \Carbon\Carbon::parse($detalle->respuesta)->format('d/m/Y'),
                'fecha_formateada' => \Carbon\Carbon::parse($detalle->respuesta)->format('d/m/Y')
            ];
        })->toArray();
    }

    /**
     * Get file results.
     */
    private function getArchivoResultados($detalleRespuestas)
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
     * Export to PDF.
     */
    private function exportToPdf($data)
    {
        // Implementar exportación a PDF
        return response()->json(['message' => 'Exportación PDF no implementada aún']);
    }

    /**
     * Export to Excel.
     */
    private function exportToExcel($data)
    {
        // Implementar exportación a Excel
        return response()->json(['message' => 'Exportación Excel no implementada aún']);
    }
}
