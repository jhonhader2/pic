<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FamiliaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo' => ['required', 'string', 'max:50'],
            'direccion' => ['required', 'string', 'max:255'],
            'barrio_id' => ['nullable', 'integer', 'exists:parametros,id'],
            'jefe_persona_id' => ['nullable', 'uuid', 'exists:personas,id'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
            'personas' => ['nullable', 'array'],
            'personas.*' => ['uuid', 'exists:personas,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'El código de familia es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
        ];
    }
}
