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
            return $this->executeJsonOperation(
                fn() => $this->personaService->createPersonaByRole($request->validated()),
                'Persona creada exitosamente.',
                'Error al crear la persona'
            );
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
