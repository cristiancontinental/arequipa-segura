<?php

namespace App\Repositories\Contracts;

use App\Models\Evidencia;

/** Contrato de acceso a los metadatos de las evidencias. */
interface EvidenciaRepositoryInterface
{
    public function crear(array $datos): Evidencia;

    public function eliminar(Evidencia $evidencia): void;
}
