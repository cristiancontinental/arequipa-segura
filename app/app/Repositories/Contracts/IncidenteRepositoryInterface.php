<?php

namespace App\Repositories\Contracts;

use App\Models\Incidente;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/** Contrato de acceso a datos de los reportes (incidentes). */
interface IncidenteRepositoryInterface
{
    /** Guarda un nuevo reporte. */
    public function crear(array $datos): Incidente;

    /** Actualiza un reporte existente y lo devuelve actualizado. */
    public function actualizar(Incidente $incidente, array $datos): Incidente;

    /** Elimina un reporte. */
    public function eliminar(Incidente $incidente): void;

    /** Reportes aprobados y vigentes (los que se muestran en el mapa). */
    public function vigentes(): Collection;

    /** Lista paginada, opcionalmente filtrada por estado. */
    public function paginar(?string $estado, int $porPagina = 15): LengthAwarePaginator;

    /** Marca como vencidos los aprobados cuya vigencia terminó; devuelve cuántos. */
    public function vencerCaducados(): int;
}
