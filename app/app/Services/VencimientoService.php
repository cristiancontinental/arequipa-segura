<?php

namespace App\Services;

use App\Repositories\Contracts\IncidenteRepositoryInterface;

/**
 * Vencimiento automático: los reportes aprobados dejan de ser vigentes
 * al cumplirse su plazo. Lo ejecuta el planificador de tareas de Laravel.
 */
class VencimientoService
{
    public function __construct(private IncidenteRepositoryInterface $incidentes)
    {
    }

    /** Marca como vencidos los reportes caducados y devuelve la cantidad. */
    public function vencerCaducados(): int
    {
        return $this->incidentes->vencerCaducados();
    }
}
