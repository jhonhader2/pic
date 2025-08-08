<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class FamiliaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $familiaId = $this->route('familia')?->id;

        return [
            'codigo' => ['required', 'string', 'max:50'],
            'direccion' => ['required', 'string', 'max:255'],
            'barrio_id' => ['nullable', 'integer', 'exists:parametros,id'],
            'jefe_persona_id' => [
                'nullable',
                'uuid',
                'exists:personas,id',
                function ($attribute, $value, $fail) use ($familiaId) {
                    if ($value) {
                        $query = \App\Models\Familia::where('jefe_persona_id', $value);
                        if ($familiaId) {
                            $query->where('id', '!=', $familiaId);
                        }
                        if ($query->exists()) {
                            $fail('Esta persona ya es jefe de otra familia.');
                        }
                    }
                }
            ],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
            'personas' => ['nullable', 'array'],
            'personas.*' => [
                'uuid',
                'exists:personas,id',
                function ($attribute, $value, $fail) use ($familiaId) {
                    if ($value) {
                        $query = DB::table('familia_personas')
                            ->where('persona_id', $value);
                        if ($familiaId) {
                            $query->where('familia_id', '!=', $familiaId);
                        }
                        if ($query->exists()) {
                            $fail('Esta persona ya pertenece a otra familia.');
                        }
                    }
                }
            ],
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
