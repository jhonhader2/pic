<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Tema;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EncuestaController extends Controller
{
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

        return view('encuestas.index', compact('encuestas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $temas = Tema::where('status', true)->orderBy('name')->get();
        $personas = Persona::orderBy('primer_nombre')->get();

        return view('encuestas.create', compact('temas', 'personas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'activa' => 'boolean',
            'temas' => 'required|array|min:1',
            'temas.*' => 'exists:temas,id',
            'personas' => 'nullable|array',
            'personas.*' => 'exists:personas,id',
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede tener más de 255 caracteres.',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio debe ser hoy o una fecha futura.',
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'temas.required' => 'Debe seleccionar al menos un tema.',
            'temas.min' => 'Debe seleccionar al menos un tema.',
            'temas.*.exists' => 'Uno de los temas seleccionados no existe.',
            'personas.*.exists' => 'Una de las personas seleccionadas no existe.',
        ]);

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

            // Asignar temas a la encuesta
            $encuesta->temas()->attach($request->temas);

            // Asignar personas a la encuesta (si se seleccionaron)
            if ($request->has('personas') && !empty($request->personas)) {
                $personasData = [];
                foreach ($request->personas as $personaId) {
                    $personasData[$personaId] = ['created_by' => Auth::id()];
                }
                $encuesta->personas()->attach($personasData);
            }

            DB::commit();

            return redirect()->route('encuestas.index')
                ->with('success', 'Encuesta creada exitosamente.');
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
        //
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
