<?php

namespace App\Repositories;

use App\Models\Incidente;
use App\Repositories\Contracts\IncidenteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/** Implementación Eloquent del repositorio de reportes. */
class EloquentIncidenteRepository implements IncidenteRepositoryInterface
{
    public function crear(array $datos): Incidente
    {
        return Incidente::create($datos);
    }

    public function actualizar(Incidente $incidente, array $datos): Incidente
    {
        $incidente->update($datos);

        return $incidente;
    }

    public function eliminar(Incidente $incidente): void
    {
        $incidente->delete();
    }

    public function vigentes(): Collection
    {
        return Incidente::with('categoria')->vigentes()->latest()->get();
    }

    public function paginar(?string $estado, int $porPagina = 15): LengthAwarePaginator
    {
        return Incidente::with('categoria')
            ->when($estado, fn ($consulta) => $consulta->where('estado', $estado))
            ->latest()
            ->paginate($porPagina)
            ->withQueryString();
    }

    public function vencerCaducados(): int
    {
        return Incidente::where('estado', Incidente::ESTADO_APROBADO)
            ->where('vence_en', '<=', now())
            ->update(['estado' => Incidente::ESTADO_VENCIDO]);
    }
}
