<?php

namespace App\Repositories;

use App\Models\ContactoEmergencia;
use App\Repositories\Contracts\ContactoEmergenciaRepositoryInterface;
use Illuminate\Support\Collection;

/** Implementación Eloquent del repositorio de contactos de emergencia. */
class EloquentContactoEmergenciaRepository implements ContactoEmergenciaRepositoryInterface
{
    public function todos(): Collection
    {
        return ContactoEmergencia::orderBy('situacion')->orderBy('servicio')->get();
    }

    public function activos(): Collection
    {
        return ContactoEmergencia::where('activo', true)->orderBy('servicio')->get();
    }

    public function crear(array $datos): ContactoEmergencia
    {
        return ContactoEmergencia::create($datos);
    }

    public function actualizar(ContactoEmergencia $contacto, array $datos): ContactoEmergencia
    {
        $contacto->update($datos);

        return $contacto;
    }

    public function eliminar(ContactoEmergencia $contacto): void
    {
        $contacto->delete();
    }
}
