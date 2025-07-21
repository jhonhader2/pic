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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        // Generar clave de cache única basada en los filtros
        $cacheKey = $this->generarClaveCacheEncuestas($request);

        $encuestas = Cache::remember($cacheKey, 900, function () use ($request) {
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

            return $query->paginate(10)->withQueryString();
        });

        return view('encuestas.index', compact('encuestas'));
    }

    /**
     * Genera una clave única de cache para las consultas de encuestas
     *
     * @param Request $request
     * @return string
     */
    private function generarClaveCacheEncuestas(Request $request): string
    {
        $filtros = [
            'estado' => $request->get('estado', ''),
            'creador' => $request->get('creador', ''),
            'buscar' => $request->get('buscar', ''),
            'orden' => $request->get('orden', 'created_at'),
            'direccion' => $request->get('direccion', 'desc'),
            'page' => $request->get('page', 1),
        ];

        return 'encuestas_list_' . md5(serialize($filtros));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $personas = Cache::remember('personas_list', 3600, function () {
            return Persona::orderBy('primer_nombre')->get();
        });

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

        return view('encuestas.show', compact('encuesta'));
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
            'tipo_pregunta' => 'required|array|min:1',
            'tipo_pregunta.*' => 'in:' . implode(',', array_keys(TipoPreguntaHelper::getTiposDisponibles())),
            'temas' => 'nullable|array',
            'temas.*' => 'exists:temas,id',
            'requeridas' => 'nullable|array',
            'requeridas.*' => 'boolean',
            'descripciones_pregunta' => 'nullable|array',
            'descripciones_pregunta.*' => 'nullable|string|max:500',
            'titulos_genericos' => 'nullable|array',
            'titulos_genericos.*' => 'required|string|max:255',
            'descripciones_genericas' => 'nullable|array',
            'descripciones_genericas.*' => 'nullable|string|max:500',
            'requeridas_genericas' => 'nullable|array',
            'requeridas_genericas.*' => 'boolean',
        ], [
            'tipo_pregunta.required' => 'Debe seleccionar al menos un tipo de pregunta.',
            'tipo_pregunta.min' => 'Debe seleccionar al menos un tipo de pregunta.',
            'tipo_pregunta.*.in' => 'El tipo de pregunta seleccionado no es válido.',
            'temas.*.exists' => 'Uno de los temas seleccionados no existe.',
            'titulos_genericos.*.required' => 'El título de la pregunta es obligatorio.',
        ]);

        try {
            DB::beginTransaction();

            $orden = 1;
            $temasData = [];

            // Procesar temas seleccionados
            if ($request->has('temas') && !empty($request->temas)) {
                foreach ($request->temas as $temaId) {
                    $temasData[$temaId] = [
                        'tipo_pregunta' => $request->tipo_pregunta[0] ?? 'seleccion_unica', // Por ahora usa el primer tipo
                        'requerida' => isset($request->requeridas[$temaId]),
                        'descripcion_pregunta' => $request->descripciones_pregunta[$temaId] ?? null,
                        'opciones_personalizadas' => null,
                        'orden' => $orden++
                    ];
                }
            }

            // Procesar preguntas genéricas
            if ($request->has('titulos_genericos')) {
                foreach ($request->titulos_genericos as $tipo => $titulo) {
                    // Crear un tema temporal para preguntas genéricas
                    $temaGenerico = Tema::create([
                        'name' => $titulo,
                        'descripcion' => $request->descripciones_genericas[$tipo] ?? null,
                        'status' => true,
                        'user_create_id' => Auth::id(),
                        'user_edit_id' => Auth::id(),
                    ]);

                    $temasData[$temaGenerico->id] = [
                        'tipo_pregunta' => $tipo,
                        'requerida' => isset($request->requeridas_genericas[$tipo]),
                        'descripcion_pregunta' => $request->descripciones_genericas[$tipo] ?? null,
                        'opciones_personalizadas' => null,
                        'orden' => $orden++
                    ];
                }
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
                'user_id' => Auth::id(),
                'fecha_respuesta' => now(),
                'status' => true,
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
                        'tema_id' => $temaId,
                        'valor_texto' => $rutaArchivo,
                        'valor_archivo' => $rutaArchivo,
                        'status' => true,
                    ]);
                } else {
                    // Manejar texto, números, etc.
                    DetalleRespuesta::create([
                        'respuesta_id' => $respuesta->id,
                        'tema_id' => $temaId,
                        'valor_texto' => is_array($valor) ? implode(',', $valor) : $valor,
                        'status' => true,
                    ]);
                }
            }

            DB::commit();

            // Enviar notificación de respuesta recibida
            $this->notificationService->notificarRespuestaRecibida($encuesta, $respuesta);

            return redirect()->route('encuestas.index')
                ->with('success', 'Respuesta enviada exitosamente.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Encuesta $encuesta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Encuesta $encuesta)
    {
        //
    }
}
