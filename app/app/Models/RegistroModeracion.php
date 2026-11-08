<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Acción de moderación realizada sobre un reporte (trazabilidad). */
class RegistroModeracion extends Model
{
    protected $table = 'registros_moderacion';

    protected $fillable = ['incidente_id', 'moderador_id', 'accion', 'motivo'];

    public function incidente(): BelongsTo
    {
        return $this->belongsTo(Incidente::class);
    }

    public function moderador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderador_id');
    }
}
