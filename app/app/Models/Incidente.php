<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Reporte ciudadano de una situación de riesgo.
 *
 * Estados: pendiente (recién enviado), aprobado (visible en el mapa),
 * rechazado y vencido (superó los días de vigencia).
 */
class Incidente extends Model
{
    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_APROBADO = 'aprobado';
    public const ESTADO_RECHAZADO = 'rechazado';
    public const ESTADO_VENCIDO = 'vencido';

    public const ESTADOS = [
        self::ESTADO_PENDIENTE,
        self::ESTADO_APROBADO,
        self::ESTADO_RECHAZADO,
        self::ESTADO_VENCIDO,
    ];

    protected $table = 'incidentes';

    protected $fillable = [
        'categoria_id', 'descripcion', 'latitud_aprox', 'longitud_aprox',
        'estado', 'aprobado_en', 'vence_en',
    ];

    protected $casts = [
        'latitud_aprox' => 'float',
        'longitud_aprox' => 'float',
        'aprobado_en' => 'datetime',
        'vence_en' => 'datetime',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function evidencias(): HasMany
    {
        return $this->hasMany(Evidencia::class);
    }

    public function registros(): HasMany
    {
        return $this->hasMany(RegistroModeracion::class);
    }

    /** Reportes aprobados cuya vigencia aún no terminó (los del mapa público). */
    public function scopeVigentes(Builder $consulta): Builder
    {
        return $consulta->where('estado', self::ESTADO_APROBADO)
            ->where('vence_en', '>', now());
    }
}
