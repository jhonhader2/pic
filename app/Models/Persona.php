<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory, HasUuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'tipo_documento',
        'numero_documento',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'sexo',
        'identidad_genero',
        'estado_civil',
        'telefono',
        'celular',
        'tipo_sangre',
        'factor_rh',
        'afiliacion_salud',
        'tipo_afiliacion_salud',
        'eps',
        'discapacidad',
        'tipo_discapacidad',
        'atencion_integral_discapacidad',
        'pertenencia_etnica',
        'nombre_etnia',
        'ocupacion',
        'barrio',
        'direccion',
        'foto',
    ];

    /**
     * Get the user that owns the persona.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
