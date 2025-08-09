<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonaRequest;
use App\Models\Persona;
use App\Models\User;
use App\Services\PersonaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PersonaController extends BaseController
{
    public function __construct(
        private PersonaService $personaService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Persona::with([
            'user',
            'tipoDocumento',
            'sexo',
            'estadoCivil',
            'identidadGenero',
            'tipoSangre',
            'factorRh',
            'tipoAfiliacionSalud',
            'eps',
            'tipoDiscapacidad',
            'pertenenciaEtnica',
            'ocupacion',
            'barrio'
        ]);

        // Aplicar filtros
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('primer_nombre', 'LIKE', "%{$buscar}%")
                    ->orWhere('segundo_nombre', 'LIKE', "%{$buscar}%")
                    ->orWhere('primer_apellido', 'LIKE', "%{$buscar}%")
                    ->orWhere('segundo_apellido', 'LIKE', "%{$buscar}%")
                    ->orWhere('numero_documento', 'LIKE', "%{$buscar}%");
            });
        }

        if ($request->filled('sexo')) {
            $query->where('sexo_id', $request->input('sexo'));
        }

        if ($request->filled('estado_civil')) {
            $query->where('estado_civil_id', $request->input('estado_civil'));
        }

        if ($request->filled('barrio')) {
            $query->where('barrio_id', $request->input('barrio'));
        }

        // Ordenar por nombre
        $query->orderBy('primer_nombre')->orderBy('primer_apellido');

        $personas = $query->paginate(15)->withQueryString();

        return view('personas.index', compact('personas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('personas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonaRequest $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            if ($request->ajax()) {
                return $this->errorResponse('Debe iniciar sesión para crear una persona.', 401);
            }

            return redirect()
                ->route('login')
                ->with('error', 'Debe iniciar sesión para crear una persona.');
        }

        if ($request->ajax()) {
            try {
                $result = $this->personaService->createPersonaByRole($request->validated());

                // Si el resultado es un User (admin creando usuario), obtener la persona
                $persona = $result instanceof User ? $result->persona : $result;

                return response()->json([
                    'success' => true,
                    'message' => 'Persona creada exitosamente.',
                    'persona' => [
                        'id' => $persona->id,
                        'primer_nombre' => $persona->primer_nombre,
                        'primer_apellido' => $persona->primer_apellido,
                        'numero_documento' => $persona->numero_documento
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la persona: ' . $e->getMessage()
                ], 500);
            }
        }

        return $this->executeTransaction(
            fn() => $this->personaService->createPersonaByRole($request->validated()),
            'Persona creada exitosamente.',
            'Error al crear la persona',
            'personas.index'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Persona $persona)
    {
        $persona->load([
            'user',
            'tipoDocumento',
            'sexo',
            'estadoCivil',
            'identidadGenero',
            'tipoSangre',
            'factorRh',
            'tipoAfiliacionSalud',
            'eps',
            'tipoDiscapacidad',
            'pertenenciaEtnica',
            'ocupacion',
            'barrio'
        ]);

        return view('personas.show', compact('persona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persona $persona)
    {
        return view('personas.edit', compact('persona'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PersonaRequest $request, Persona $persona): RedirectResponse
    {
        return $this->executeTransaction(
            fn() => $persona->update($request->validated()),
            'Persona actualizada exitosamente.',
            'Error al actualizar la persona',
            'personas.index'
        );
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Persona $persona): JsonResponse
    {
        return $this->executeJsonOperation(
            fn() => $persona->delete(),
            'Persona eliminada exitosamente.',
            'Error al eliminar la persona'
        );
    }
}
