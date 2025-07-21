<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Events\DashboardUpdated;

class Encuesta extends Model
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
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'activa',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activa' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($encuesta) {
            // Convertir título a mayúsculas
            if (!empty($encuesta->titulo)) {
                $encuesta->titulo = strtoupper($encuesta->titulo);
            }
        });

        // Invalidar cache y disparar evento cuando se crea, actualiza o elimina una encuesta
        static::created(function ($encuesta) {
            self::limpiarCacheEncuesta($encuesta);
            event(new DashboardUpdated($encuesta));
        });

        static::updated(function ($encuesta) {
            self::limpiarCacheEncuesta($encuesta);
            event(new DashboardUpdated($encuesta));
        });

        static::deleted(function ($encuesta) {
            self::limpiarCacheEncuesta($encuesta);
            event(new DashboardUpdated($encuesta));
        });
    }

    /**
     * Limpia el cache relacionado con una encuesta específica y el dashboard global
     *
     * @param Encuesta $encuesta
     * @return void
     */
    private static function limpiarCacheEncuesta(Encuesta $encuesta): void
    {
        Cache::forget("encuesta_{$encuesta->id}_total_respuestas");
        Cache::forget("encuesta_{$encuesta->id}_total_personas");
        Cache::forget("encuesta_{$encuesta->id}_porcentaje_completado");

        // Limpiar cache de listas y dashboard global
        Cache::forget('encuestas_activas');
        Cache::forget('total_encuestas');
        Cache::forget('dashboard_stats');
        Cache::forget('estadisticas_sistema');
    }

    /**
     * Get the user that created the encuesta.
     */
    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the personas assigned to this encuesta.
     */
    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'encuesta_personas')
            ->withPivot('created_by')
            ->withTimestamps();
    }

    /**
     * Get the temas associated with this encuesta.
     */
    public function temas()
    {
        return $this->belongsToMany(Tema::class, 'encuesta_temas')
            ->withPivot([
                'tipo_pregunta',
                'requerida',
                'descripcion_pregunta',
                'opciones_personalizadas',
                'orden'
            ])
            ->withTimestamps();
    }

    /**
     * Get the respuestas for this encuesta.
     */
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }

    /**
     * Scope a query to only include active encuestas.
     */
    public function scopeActivas(Builder $query): void
    {
        $query->where('activa', true);
    }

    /**
     * Scope a query to only include encuestas within date range.
     */
    public function scopeEnRango(Builder $query): void
    {
        $today = Carbon::today();
        $query->where('fecha_inicio', '<=', $today)
            ->where('fecha_fin', '>=', $today);
    }

    /**
     * Scope a query to only include encuestas that are currently active and in range.
     */
    public function scopeDisponibles(Builder $query): void
    {
        $query->activas()->enRango();
    }

    /**
     * Scope a query to only include encuestas created by a specific user.
     */
    public function scopePorCreador(Builder $query, int $userId): void
    {
        $query->where('created_by', $userId);
    }

    /**
     * Check if the encuesta is currently available.
     */
    public function estaDisponible(): bool
    {
        $today = Carbon::today();
        return $this->activa &&
            $this->fecha_inicio <= $today &&
            $this->fecha_fin >= $today;
    }

    /**
     * Check if the encuesta has expired.
     */
    public function haExpirado(): bool
    {
        return Carbon::today()->gt($this->fecha_fin);
    }

    /**
     * Check if the encuesta has not started yet.
     */
    public function noHaIniciado(): bool
    {
        return Carbon::today()->lt($this->fecha_inicio);
    }

    /**
     * Get the total number of responses for this encuesta.
     */
    public function getTotalRespuestasAttribute(): int
    {
        $cacheKey = "encuesta_{$this->id}_total_respuestas";

        return Cache::remember($cacheKey, 900, function () {
            return $this->respuestas()->count();
        });
    }

    /**
     * Get the total number of assigned personas for this encuesta.
     */
    public function getTotalPersonasAsignadasAttribute(): int
    {
        $cacheKey = "encuesta_{$this->id}_total_personas";

        return Cache::remember($cacheKey, 1800, function () {
            return $this->personas()->count();
        });
    }

    /**
     * Get the completion percentage for this encuesta.
     */
    public function getPorcentajeCompletadoAttribute(): float
    {
        $cacheKey = "encuesta_{$this->id}_porcentaje_completado";

        return Cache::remember($cacheKey, 900, function () {
            $totalAsignadas = $this->total_personas_asignadas;

            if ($totalAsignadas === 0) {
                return 0.0;
            }

            return round(($this->total_respuestas / $totalAsignadas) * 100, 2);
        });
    }

    /**
     * Get the status text attribute.
     */
    public function getEstadoTextoAttribute(): string
    {
        if (!$this->activa) {
            return 'INACTIVA';
        }

        if ($this->noHaIniciado()) {
            return 'PENDIENTE';
        }

        if ($this->haExpirado()) {
            return 'EXPIRADA';
        }

        return 'ACTIVA';
    }

    /**
     * Get the duration in days.
     */
    public function getDuracionDiasAttribute(): int
    {
        return $this->fecha_inicio->diffInDays($this->fecha_fin) + 1;
    }

    /**
     * Get the remaining days.
     */
    public function getDiasRestantesAttribute(): int
    {
        if ($this->haExpirado()) {
            return 0;
        }

        return Carbon::today()->diffInDays($this->fecha_fin, false);
    }
}
