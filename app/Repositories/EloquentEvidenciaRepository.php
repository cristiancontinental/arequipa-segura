<?php

namespace App\Repositories;

use App\Models\Evidencia;
use App\Repositories\Contracts\EvidenciaRepositoryInterface;

/** Implementación Eloquent del repositorio de evidencias. */
class EloquentEvidenciaRepository implements EvidenciaRepositoryInterface
{
    public function crear(array $datos): Evidencia
    {
        return Evidencia::create($datos);
    }

    public function eliminar(Evidencia $evidencia): void
    {
        $evidencia->delete();
    }
}
