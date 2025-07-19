<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleRespuesta extends Model
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
        'respuesta_id',
        'pregunta_id',
        'parametro_id',
        'valor_numerico',
        'fecha_respuesta',
        'ruta_archivo',
        'respuesta'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'valor_numerico' => 'integer',
        'fecha_respuesta' => 'date',
    ];

    /**
     * Get the respuesta that owns the detalle respuesta.
     */
    public function respuesta()
    {
        return $this->belongsTo(Respuesta::class);
    }

    /**
     * Get the tema (pregunta) for this detalle respuesta.
     */
    public function pregunta()
    {
        return $this->belongsTo(Tema::class, 'pregunta_id');
    }

    /**
     * Get the parametro for this detalle respuesta.
     */
    public function parametro()
    {
        return $this->belongsTo(Parametro::class);
    }
}
