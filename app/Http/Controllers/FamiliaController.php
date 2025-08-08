<?php

namespace App\Http\Controllers;

use App\Helpers\EdadHelper;
use App\Http\Requests\FamiliaRequest;
use App\Models\Familia;
use App\Models\Persona;
use App\Services\FamiliaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FamiliaController extends Controller
{
    public function __construct(private FamiliaService $familiaService) {}

    public function index()
    {
        $familias = Familia::with([
            'barrio',
            'jefe.tipoDocumento',
            'jefe.sexo',
            'personas',
            'encuestas'
        ])->latest()->paginate(15);

        // Obtener datos para el gráfico de distribución por edad usando el helper
        $personasEnFamilias = DB::table('familia_personas')
            ->join('personas', 'familia_personas.persona_id', '=', 'personas.id')
            ->select('personas.*')
            ->whereNotNull('personas.fecha_nacimiento')
            ->get();

        $distribucionEdad = EdadHelper::calcularDistribucionEdad($personasEnFamilias);

        // Obtener estadística de mujeres cabeza de familia
        $mujeresCabezaFamilia = DB::table('familias')
            ->join('personas', 'familias.jefe_persona_id', '=', 'personas.id')
            ->whereIn('personas.sexo_id', [14, 16]) // IDs para mujeres
            ->count();

        return view('familias.index', compact('familias', 'distribucionEdad', 'mujeresCabezaFamilia'));
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

        // Calcular distribución por edad usando el helper
        $distribucionEdad = EdadHelper::calcularDistribucionEdad($familia->personas);
        $datosGrafico = EdadHelper::getDatosGrafico($distribucionEdad);

        $personas = Persona::orderBy('primer_nombre')->get();
        return view('familias.show', compact('familia', 'personas', 'distribucionEdad', 'datosGrafico'));
    }

    public function edit(Familia $familia)
    {
        $familia->load([
            'barrio',
            'jefe',
            'personas' => function ($query) {
                $query->withPivot(['es_jefe', 'rol', 'created_by']);
            }
        ]);
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
