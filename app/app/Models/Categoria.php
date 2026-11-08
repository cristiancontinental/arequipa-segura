<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Categoría de un reporte (por ejemplo, falsos taxis o robos en viviendas). */
class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = ['nombre', 'activa'];

    protected $casts = ['activa' => 'boolean'];

    public function incidentes(): HasMany
    {
        return $this->hasMany(Incidente::class);
    }
}
