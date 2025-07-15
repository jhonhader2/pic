<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tipo_documento' => 'required|string|max:255',
            'numero_documento' => 'required|string|max:255',
            'primer_nombre' => 'required|string|max:255',
            'segundo_nombre' => 'nullable|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|boolean',
            'identidad_genero' => 'nullable|string|max:255',
            'estado_civil' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'celular' => 'required|string|max:255',
            'correo_electronico' => 'nullable|email|max:255',
            'tipo_sangre' => 'required|string|max:255',
            'factor_rh' => 'required|string|max:255',
            'afiliacion_salud' => 'required|boolean',
            'tipo_afiliacion_salud' => 'required|string|max:255',
            'eps' => 'required|string|max:255',
            'discapacidad' => 'required|boolean',
            'tipo_discapacidad' => 'required|string|max:255',
            'atencion_integral_discapacidad' => 'required|boolean',
            'pertenencia_etnica' => 'required|string|max:255',
            'nombre_etnia' => 'nullable|string|max:255',
            'ocupacion' => 'nullable|string|max:255',
            'barrio' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Crear una nueva instancia de Persona
        $persona = new \App\Models\Persona($validatedData);

        // Guardar la persona en la base de datos
        $persona->save();

        // Redirigir o devolver una respuesta
        return redirect()->route('personas.index')->with('success', 'Persona creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
