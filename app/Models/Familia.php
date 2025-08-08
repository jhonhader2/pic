<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'codigo',
        'direccion',
        'barrio_id',
        'jefe_persona_id',
        'latitud',
        'longitud',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($familia) {
            if (!empty($familia->codigo)) {
                $familia->codigo = strtoupper($familia->codigo);
            }
            if (!empty($familia->direccion)) {
                $familia->direccion = strtoupper($familia->direccion);
            }
        });
    }

    public function barrio()
    {
        return $this->belongsTo(Parametro::class, 'barrio_id');
    }

    public function jefe()
    {
        return $this->belongsTo(Persona::class, 'jefe_persona_id');
    }

    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'familia_personas')
            ->withPivot(['rol', 'es_jefe', 'created_by'])
            ->withTimestamps();
    }

    public function encuestas()
    {
        return $this->belongsToMany(Encuesta::class, 'encuesta_familias')
            ->withPivot(['created_by'])
            ->withTimestamps();
    }
}
