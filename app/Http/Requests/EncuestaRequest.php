<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EncuestaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // La autorización se maneja en el controlador
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'titulo' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\-_.,!?()]+$/'
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:1000',
                'min:10'
            ],
            'fecha_inicio' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:fecha_fin'
            ],
            'fecha_fin' => [
                'required',
                'date',
                'after:fecha_inicio',
                'after:today'
            ],
            'activa' => [
                'boolean'
            ],
            'personas' => [
                'nullable',
                'array',
                'min:1'
            ],
            'personas.*' => [
                'exists:personas,id',
                'distinct'
            ],
        ];

        // Reglas específicas para actualización
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['titulo'][] = Rule::unique('encuestas')->ignore($this->encuesta->id);
        } else {
            $rules['titulo'][] = Rule::unique('encuestas');
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'El título de la encuesta es obligatorio.',
            'titulo.string' => 'El título debe ser texto.',
            'titulo.max' => 'El título no puede tener más de 255 caracteres.',
            'titulo.min' => 'El título debe tener al menos 3 caracteres.',
            'titulo.regex' => 'El título contiene caracteres no permitidos.',
            'titulo.unique' => 'Ya existe una encuesta con este título.',

            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres.',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',

            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio debe ser hoy o una fecha futura.',
            'fecha_inicio.before_or_equal' => 'La fecha de inicio debe ser anterior o igual a la fecha de fin.',

            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'fecha_fin.after_today' => 'La fecha de fin debe ser posterior a hoy.',

            'activa.boolean' => 'El estado activo debe ser verdadero o falso.',

            'personas.array' => 'Las personas deben ser una lista.',
            'personas.min' => 'Debe seleccionar al menos una persona.',
            'personas.*.exists' => 'Una de las personas seleccionadas no existe.',
            'personas.*.distinct' => 'No puede seleccionar la misma persona más de una vez.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'titulo' => 'título de la encuesta',
            'descripcion' => 'descripción',
            'fecha_inicio' => 'fecha de inicio',
            'fecha_fin' => 'fecha de fin',
            'activa' => 'estado activo',
            'personas' => 'personas',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Limpiar y normalizar datos
        $this->merge([
            'titulo' => trim($this->titulo),
            'descripcion' => $this->descripcion ? trim($this->descripcion) : null,
            'activa' => $this->boolean('activa'),
        ]);
    }
}
