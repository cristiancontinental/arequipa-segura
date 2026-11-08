<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Metadatos de una fotografía adjunta a un reporte (archivo en almacenamiento privado). */
class Evidencia extends Model
{
    protected $table = 'evidencias';

    protected $fillable = ['incidente_id', 'ruta_privada', 'tipo_archivo', 'tamano'];

    public function incidente(): BelongsTo
    {
        return $this->belongsTo(Incidente::class);
    }
}
