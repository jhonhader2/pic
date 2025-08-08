<?php

namespace App\Http\Controllers;

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

        // Obtener datos para el gráfico de distribución por edad
        $personasEnFamilias = DB::table('familia_personas')
            ->join('personas', 'familia_personas.persona_id', '=', 'personas.id')
            ->select('personas.fecha_nacimiento')
            ->whereNotNull('personas.fecha_nacimiento')
            ->get();

        $distribucionEdad = [
            'ninos' => 0,      // 0-11 años
            'adolescentes' => 0, // 12-17 años
            'adultos' => 0,    // 18-59 años
            'adultos_mayores' => 0 // 60+ años
        ];

        foreach ($personasEnFamilias as $persona) {
            $edad = \Carbon\Carbon::parse($persona->fecha_nacimiento)->diffInYears(now());

            if ($edad < 12) {
                $distribucionEdad['ninos']++;
            } elseif ($edad >= 12 && $edad < 18) {
                $distribucionEdad['adolescentes']++;
            } elseif ($edad >= 18 && $edad < 60) {
                $distribucionEdad['adultos']++;
            } else {
                $distribucionEdad['adultos_mayores']++;
            }
        }

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
