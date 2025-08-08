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

class PersonaController extends Controller
{
    public function __construct(
        private PersonaService $personaService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personas = Persona::with([
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
        ])->paginate(15);

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
        try {
            $user = Auth::user();

            if (!$user) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Debe iniciar sesión para crear una persona.'
                    ], 401);
                }

                return redirect()
                    ->route('login')
                    ->with('error', 'Debe iniciar sesión para crear una persona.');
            }

            // Crear persona según el rol del usuario
            $result = $this->personaService->createPersonaByRole($request->validated());

            // Si es una solicitud AJAX, devolver JSON
            if ($request->ajax()) {
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
            }

            // Determinar el mensaje según el tipo de resultado
            if ($result instanceof User) {
                $message = 'Usuario y persona creados exitosamente.';
            } else {
                $message = 'Persona creada exitosamente.';
            }

            return redirect()
                ->route('personas.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la persona: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear la persona: ' . $e->getMessage());
        }
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
        try {
            $persona->update($request->validated());

            return redirect()
                ->route('personas.index')
                ->with('success', 'Persona actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar la persona: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Persona $persona): JsonResponse
    {
        try {
            $persona->delete();

            return response()->json([
                'success' => true,
                'message' => 'Persona eliminada exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la persona: ' . $e->getMessage()
            ], 500);
        }
    }
}
