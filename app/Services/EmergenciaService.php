<?php

namespace App\Services;

use App\Models\ContactoEmergencia;
use App\Repositories\Contracts\ContactoEmergenciaRepositoryInterface;
use Illuminate\Support\Collection;

/** Obtiene y administra los contactos de emergencia que muestra la aplicación. */
class EmergenciaService
{
    public function __construct(private ContactoEmergenciaRepositoryInterface $contactos)
    {
    }

    /**
     * Contactos activos agrupados por situación, en el orden de la interfaz.
     *
     * @return Collection<string, Collection<int, ContactoEmergencia>>
     */
    public function agrupadosPorSituacion(): Collection
    {
        $grupos = $this->contactos->activos()->groupBy('situacion');

        return collect(array_keys(ContactoEmergencia::SITUACIONES))
            ->mapWithKeys(fn (string $situacion) => [$situacion => $grupos->get($situacion, collect())])
            ->filter(fn (Collection $lista) => $lista->isNotEmpty());
    }

    public function listar(): Collection
    {
        return $this->contactos->todos();
    }

    public function crear(array $datos): ContactoEmergencia
    {
        return $this->contactos->crear($datos);
    }

    public function actualizar(ContactoEmergencia $contacto, array $datos): ContactoEmergencia
    {
        return $this->contactos->actualizar($contacto, $datos);
    }

    public function eliminar(ContactoEmergencia $contacto): void
    {
        $this->contactos->eliminar($contacto);
    }
}
