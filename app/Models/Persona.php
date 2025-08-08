<?php

namespace App\Models;

use App\Helpers\SexoHelper;
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
        'tipo_documento_id',
        'sexo_id',
        'estado_civil_id',
        'identidad_genero_id',
        'tipo_sangre_id',
        'factor_rh_id',
        'tipo_afiliacion_salud_id',
        'eps_id',
        'tipo_discapacidad_id',
        'pertenencia_etnica_id',
        'ocupacion_id',
        'barrio_id',
        'numero_documento',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'telefono',
        'celular',
        'afiliacion_salud',
        'discapacidad',
        'atencion_integral_discapacidad',
        'nombre_etnia',
        'direccion',
        'foto',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($persona) {
            // Convertir campos de texto a mayúsculas
            $textFields = [
                'numero_documento',
                'primer_nombre',
                'segundo_nombre',
                'primer_apellido',
                'segundo_apellido',
                'telefono',
                'celular',
                'nombre_etnia',
                'direccion'
            ];

            foreach ($textFields as $field) {
                if (!empty($persona->$field)) {
                    $persona->$field = strtoupper($persona->$field);
                }
            }
        });
    }

    /**
     * Get the user that owns the persona.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sexo text attribute.
     */
    public function getSexoTextoAttribute(): string
    {
        return SexoHelper::getTexto($this->sexo);
    }

    /**
     * Get the tipo documento parameter.
     */
    public function tipoDocumento()
    {
        return $this->belongsTo(Parametro::class, 'tipo_documento_id');
    }

    /**
     * Get the tipo documento name attribute.
     */
    public function getTipoDocumentoNombreAttribute(): ?string
    {
        return $this->tipoDocumento?->name;
    }

    /**
     * Get the sexo parameter.
     */
    public function sexo()
    {
        return $this->belongsTo(Parametro::class, 'sexo_id');
    }

    /**
     * Get the sexo name attribute.
     */
    public function getSexoNombreAttribute(): ?string
    {
        return $this->sexo?->name;
    }

    /**
     * Get the estado civil parameter.
     */
    public function estadoCivil()
    {
        return $this->belongsTo(Parametro::class, 'estado_civil_id');
    }

    /**
     * Get the identidad genero parameter.
     */
    public function identidadGenero()
    {
        return $this->belongsTo(Parametro::class, 'identidad_genero_id');
    }

    /**
     * Get the identidad genero name attribute.
     */
    public function getIdentidadGeneroNombreAttribute(): ?string
    {
        return $this->identidadGenero?->name;
    }

    /**
     * Get the tipo sangre parameter.
     */
    public function tipoSangre()
    {
        return $this->belongsTo(Parametro::class, 'tipo_sangre_id');
    }

    /**
     * Get the factor rh parameter.
     */
    public function factorRh()
    {
        return $this->belongsTo(Parametro::class, 'factor_rh_id');
    }

    /**
     * Get the tipo afiliacion salud parameter.
     */
    public function tipoAfiliacionSalud()
    {
        return $this->belongsTo(Parametro::class, 'tipo_afiliacion_salud_id');
    }

    /**
     * Get the eps parameter.
     */
    public function eps()
    {
        return $this->belongsTo(Parametro::class, 'eps_id');
    }

    /**
     * Get the tipo discapacidad parameter.
     */
    public function tipoDiscapacidad()
    {
        return $this->belongsTo(Parametro::class, 'tipo_discapacidad_id');
    }

    /**
     * Get the pertenencia etnica parameter.
     */
    public function pertenenciaEtnica()
    {
        return $this->belongsTo(Parametro::class, 'pertenencia_etnica_id');
    }

    /**
     * Get the ocupacion parameter.
     */
    public function ocupacion()
    {
        return $this->belongsTo(Parametro::class, 'ocupacion_id');
    }

    /**
     * Get the barrio parameter.
     */
    public function barrio()
    {
        return $this->belongsTo(Parametro::class, 'barrio_id');
    }

    /**
     * Get the edad attribute.
     */
    public function getEdadAttribute(): ?int
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }

        return \Carbon\Carbon::parse($this->fecha_nacimiento)->diffInYears(now());
    }
}
