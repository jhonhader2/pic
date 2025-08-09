<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            // Campos obligatorios
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

            // Campos opcionales
            'segundo_nombre' => ['nullable', 'string', 'max:255'],
            'segundo_apellido' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'nombre_etnia' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ];

        // Validación condicional para afiliación de salud
        if ($this->afiliacion_salud == '1') {
            $rules['tipo_afiliacion_salud'] = ['required', 'integer', 'exists:parametros,id'];
            $rules['eps'] = ['required', 'integer', 'exists:parametros,id'];
        }

        // Validación condicional para discapacidad
        if ($this->discapacidad == '1') {
            $rules['tipo_discapacidad'] = ['required', 'integer', 'exists:parametros,id'];
            $rules['atencion_integral_discapacidad'] = ['required', 'in:0,1'];
        }

        // Validación condicional para nombre de etnia
        // Si pertenece a una etnia específica (no es "NO DEFINE" = ID 5), requerir nombre
        if ($this->pertenencia_etnica && $this->pertenencia_etnica != '5') {
            $rules['nombre_etnia'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
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

            // Mensajes condicionales
            'tipo_discapacidad.required' => 'El tipo de discapacidad es obligatorio cuando presenta discapacidad.',
            'tipo_discapacidad.integer' => 'El tipo de discapacidad debe ser un valor válido.',
            'tipo_discapacidad.exists' => 'El tipo de discapacidad seleccionado no es válido.',
            'atencion_integral_discapacidad.required' => 'La atención integral de discapacidad es obligatoria cuando presenta discapacidad.',
            'atencion_integral_discapacidad.in' => 'La atención integral de discapacidad debe ser Sí o No.',
        ];
    }
}
