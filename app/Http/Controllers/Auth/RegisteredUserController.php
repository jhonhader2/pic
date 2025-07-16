<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Persona;
use App\Helpers\TipoDocumentoHelper;
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
            'tipo_documento' => ['required', 'integer', 'exists:parametros,id'],
            'numero_documento' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date', 'before:tomorrow'],
            'sexo' => ['required', 'integer', 'exists:parametros,id'],
            'identidad_genero' => ['required', 'integer', 'exists:parametros,id'],
            'estado_civil' => ['required', 'integer', 'exists:parametros,id'],
            'celular' => ['required', 'string', 'max:20'],
            'tipo_sangre' => ['required', 'integer', 'exists:parametros,id'],
            'factor_rh' => ['required', 'integer', 'exists:parametros,id'],
            'afiliacion_salud' => ['required', 'in:0,1'],
            'discapacidad' => ['required', 'in:0,1'],
            'pertenencia_etnica' => ['required', 'integer', 'exists:parametros,id'],
            'ocupacion' => ['required', 'integer', 'exists:parametros,id'],
            'barrio' => ['required', 'integer', 'exists:parametros,id'],
            'direccion' => ['required', 'string', 'max:255'],
        ], [
            'primer_nombre.required' => 'El primer nombre es obligatorio.',
            'primer_apellido.required' => 'El primer apellido es obligatorio.',
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'tipo_documento.integer' => 'El tipo de documento debe ser un valor válido.',
            'tipo_documento.exists' => 'El tipo de documento seleccionado no es válido.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe tener un formato válido.',
            'sexo.required' => 'El sexo es obligatorio.',
            'sexo.integer' => 'El sexo debe ser un valor válido.',
            'sexo.exists' => 'El sexo seleccionado no es válido.',
            'identidad_genero.required' => 'La identidad de género es obligatoria.',
            'identidad_genero.integer' => 'La identidad de género debe ser un valor válido.',
            'identidad_genero.exists' => 'La identidad de género seleccionada no es válida.',
            'estado_civil.required' => 'El estado civil es obligatorio.',
            'estado_civil.integer' => 'El estado civil debe ser un valor válido.',
            'estado_civil.exists' => 'El estado civil seleccionado no es válido.',
            'celular.required' => 'El número de celular es obligatorio.',
            'tipo_sangre.required' => 'El tipo de sangre es obligatorio.',
            'tipo_sangre.integer' => 'El tipo de sangre debe ser un valor válido.',
            'tipo_sangre.exists' => 'El tipo de sangre seleccionado no es válido.',
            'factor_rh.required' => 'El factor RH es obligatorio.',
            'factor_rh.integer' => 'El factor RH debe ser un valor válido.',
            'factor_rh.exists' => 'El factor RH seleccionado no es válido.',
            'afiliacion_salud.required' => 'La afiliación a salud es obligatoria.',
            'afiliacion_salud.in' => 'La afiliación a salud debe ser Sí o No.',
            'discapacidad.required' => 'El campo discapacidad es obligatorio.',
            'discapacidad.in' => 'El campo discapacidad debe ser Sí o No.',
            'pertenencia_etnica.required' => 'La pertenencia étnica es obligatoria.',
            'pertenencia_etnica.integer' => 'La pertenencia étnica debe ser un valor válido.',
            'pertenencia_etnica.exists' => 'La pertenencia étnica seleccionada no es válida.',
            'ocupacion.required' => 'La ocupación es obligatoria.',
            'ocupacion.integer' => 'La ocupación debe ser un valor válido.',
            'ocupacion.exists' => 'La ocupación seleccionada no es válida.',
            'barrio.required' => 'El barrio es obligatorio.',
            'barrio.integer' => 'El barrio debe ser un valor válido.',
            'barrio.exists' => 'El barrio seleccionado no es válido.',
            'direccion.required' => 'La dirección es obligatoria.',
        ]);

        // Validar campos opcionales
        $request->validate([
            'segundo_nombre' => ['nullable', 'string', 'max:255'],
            'segundo_apellido' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'nombre_etnia' => ['nullable', 'string', 'max:255'],
        ]);

        // Validar campos de afiliación solo si afiliacion_salud es "Sí"
        if ($request->afiliacion_salud == '1') {
            $request->validate([
                'tipo_afiliacion_salud' => ['nullable', 'integer', 'exists:parametros,id'],
                'eps' => ['nullable', 'integer', 'exists:parametros,id'],
            ]);
        }

        // Validar campos de discapacidad solo si discapacidad es "Sí"
        if ($request->discapacidad == '1') {
            $request->validate([
                'tipo_discapacidad' => ['required', 'integer', 'exists:parametros,id'],
                'atencion_integral_discapacidad' => ['required', 'in:0,1'],
            ], [
                'tipo_discapacidad.required' => 'El tipo de discapacidad es obligatorio cuando presenta discapacidad.',
                'tipo_discapacidad.integer' => 'El tipo de discapacidad debe ser un valor válido.',
                'tipo_discapacidad.exists' => 'El tipo de discapacidad seleccionado no es válido.',
                'atencion_integral_discapacidad.required' => 'La atención integral de discapacidad es obligatoria cuando presenta discapacidad.',
                'atencion_integral_discapacidad.in' => 'La atención integral de discapacidad debe ser Sí o No.',
            ]);
        }

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
                'tipo_documento_id' => $request->tipo_documento,
                'sexo_id' => $request->sexo,
                'estado_civil_id' => $request->estado_civil,
                'tipo_sangre_id' => $request->tipo_sangre,
                'factor_rh_id' => $request->factor_rh,
                'pertenencia_etnica_id' => $request->pertenencia_etnica,
                'ocupacion_id' => $request->ocupacion,
                'barrio_id' => $request->barrio,
                'numero_documento' => $request->numero_documento,
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'identidad_genero_id' => $request->identidad_genero,
                'telefono' => $request->telefono,
                'celular' => $request->celular,
                'afiliacion_salud' => $request->afiliacion_salud == '1',
                'tipo_afiliacion_salud_id' => $request->afiliacion_salud == '1' ? $request->tipo_afiliacion_salud : null,
                'eps_id' => $request->afiliacion_salud == '1' ? $request->eps : null,
                'discapacidad' => $request->discapacidad == '1',
                'tipo_discapacidad_id' => $request->discapacidad == '1' ? $request->tipo_discapacidad : null,
                'atencion_integral_discapacidad' => $request->discapacidad == '1' ? ($request->atencion_integral_discapacidad == '1') : false,
                'nombre_etnia' => $request->nombre_etnia,
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
