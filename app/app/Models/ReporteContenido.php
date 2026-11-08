<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Aviso de contenido inapropiado sobre una alerta ya publicada. */
class ReporteContenido extends Model
{
    protected $table = 'reportes_contenido';

    protected $fillable = ['incidente_id', 'motivo'];

    public function incidente(): BelongsTo
    {
        return $this->belongsTo(Incidente::class);
    }
}
