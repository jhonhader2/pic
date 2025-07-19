<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
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
        'encuesta_id',
        'usuario_id',
        'fecha_respuesta'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_respuesta' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($respuesta) {
            // Si no se proporciona fecha_respuesta, usar la fecha actual
            if (empty($respuesta->fecha_respuesta)) {
                $respuesta->fecha_respuesta = now();
            }
        });
    }

    /**
     * Get the encuesta that owns the respuesta.
     */
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }

    /**
     * Get the user that owns the respuesta.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Get the detalle respuestas for this respuesta.
     */
    public function detalleRespuestas()
    {
        return $this->hasMany(DetalleRespuesta::class);
    }
}
