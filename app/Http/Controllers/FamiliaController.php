<?php

namespace App\Http\Controllers;

use App\Http\Requests\FamiliaRequest;
use App\Models\Familia;
use App\Models\Persona;
use App\Services\FamiliaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FamiliaController extends Controller
{
    public function __construct(private FamiliaService $familiaService) {}

    public function index()
    {
        $familias = Familia::with(['barrio', 'jefe'])->latest()->paginate(15);
        return view('familias.index', compact('familias'));
    }

    public function create()
    {
        $personas = Persona::orderBy('primer_nombre')->get();
        return view('familias.create', compact('personas'));
    }

    public function store(FamiliaRequest $request): RedirectResponse
    {
        $familia = $this->familiaService->create($request->validated());
        return redirect()->route('familias.show', $familia)->with('success', 'Familia creada exitosamente.');
    }

    public function show(Familia $familia)
    {
        $familia->load([
            'barrio',
            'jefe.tipoDocumento',
            'jefe.sexo',
            'personas' => function ($query) {
                $query->withPivot(['es_jefe', 'rol', 'created_by']);
            },
            'personas.tipoDocumento',
            'personas.sexo',
            'encuestas.respuestas'
        ]);
        $personas = Persona::orderBy('primer_nombre')->get();
        return view('familias.show', compact('familia', 'personas'));
    }

    public function edit(Familia $familia)
    {
        $familia->load(['personas']);
        $personas = Persona::orderBy('primer_nombre')->get();
        return view('familias.edit', compact('familia', 'personas'));
    }

    public function update(FamiliaRequest $request, Familia $familia): RedirectResponse
    {
        $this->familiaService->update($familia, $request->validated());
        return redirect()->route('familias.show', $familia)->with('success', 'Familia actualizada exitosamente.');
    }

    public function destroy(Familia $familia)
    {
        $familia->delete();
        return redirect()->route('familias.index')->with('success', 'Familia eliminada.');
    }
}
