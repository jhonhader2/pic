<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar campos básicos del usuario
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        // Validar campos obligatorios de persona
        $request->validate([
            'primer_nombre' => ['required', 'string', 'max:255'],
            'primer_apellido' => ['required', 'string', 'max:255'],
            'tipo_documento' => ['required', 'string', 'max:255'],
            'numero_documento' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date', 'before:tomorrow'],
            'sexo' => ['required', 'boolean'],
            'estado_civil' => ['required', 'string', 'max:255'],
            'celular' => ['required', 'string', 'max:20'],
            'tipo_sangre' => ['required', 'string', 'max:10'],
            'factor_rh' => ['required', 'string', 'max:20'],
            'afiliacion_salud' => ['required', 'in:0,1'],
            'discapacidad' => ['required', 'in:0,1'],
            'atencion_integral_discapacidad' => ['required', 'in:0,1'],
            'pertenencia_etnica' => ['required', 'string', 'max:255'],
            'barrio' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
        ], [
            'primer_nombre.required' => 'El primer nombre es obligatorio.',
            'primer_apellido.required' => 'El primer apellido es obligatorio.',
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe tener un formato válido.',
            'sexo.required' => 'El sexo es obligatorio.',
            'sexo.boolean' => 'El sexo debe ser masculino o femenino.',
            'estado_civil.required' => 'El estado civil es obligatorio.',
            'celular.required' => 'El número de celular es obligatorio.',
            'tipo_sangre.required' => 'El tipo de sangre es obligatorio.',
            'factor_rh.required' => 'El factor RH es obligatorio.',
            'afiliacion_salud.required' => 'La afiliación a salud es obligatoria.',
            'afiliacion_salud.in' => 'La afiliación a salud debe ser Sí o No.',
            'discapacidad.required' => 'El campo discapacidad es obligatorio.',
            'discapacidad.in' => 'El campo discapacidad debe ser Sí o No.',
            'atencion_integral_discapacidad.required' => 'La atención integral de discapacidad es obligatoria.',
            'atencion_integral_discapacidad.in' => 'La atención integral de discapacidad debe ser Sí o No.',
            'pertenencia_etnica.required' => 'La pertenencia étnica es obligatoria.',
            'barrio.required' => 'El barrio es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
        ]);

        // Validar campos opcionales
        $request->validate([
            'segundo_nombre' => ['nullable', 'string', 'max:255'],
            'segundo_apellido' => ['nullable', 'string', 'max:255'],
            'identidad_genero' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'tipo_afiliacion_salud' => ['nullable', 'string', 'max:255'],
            'eps' => ['nullable', 'string', 'max:255'],
            'tipo_discapacidad' => ['nullable', 'string', 'max:255'],
            'nombre_etnia' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();

        try {
            // Crear el nombre completo para el usuario
            $nombreCompleto = trim($request->primer_nombre . ' ' .
                ($request->segundo_nombre ? $request->segundo_nombre . ' ' : '') .
                $request->primer_apellido . ' ' .
                ($request->segundo_apellido ? $request->segundo_apellido : ''));

            // Crear el usuario
            $user = User::create([
                'name' => $nombreCompleto,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Crear la persona asociada
            $persona = $user->persona()->create([
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'identidad_genero' => $request->identidad_genero,
                'estado_civil' => $request->estado_civil,
                'telefono' => $request->telefono,
                'celular' => $request->celular,
                'tipo_sangre' => $request->tipo_sangre,
                'factor_rh' => $request->factor_rh,
                'afiliacion_salud' => $request->afiliacion_salud,
                'tipo_afiliacion_salud' => $request->tipo_afiliacion_salud,
                'eps' => $request->eps,
                'discapacidad' => $request->discapacidad,
                'tipo_discapacidad' => $request->tipo_discapacidad,
                'atencion_integral_discapacidad' => $request->atencion_integral_discapacidad,
                'pertenencia_etnica' => $request->pertenencia_etnica,
                'nombre_etnia' => $request->nombre_etnia,
                'barrio' => $request->barrio,
                'direccion' => $request->direccion,
            ]);

            event(new Registered($user));

            DB::commit();

            return redirect(route('dashboard'))->with('success', 'Usuario registrado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Hubo un problema al registrar el usuario: ' . $e->getMessage());
        }
    }
}
