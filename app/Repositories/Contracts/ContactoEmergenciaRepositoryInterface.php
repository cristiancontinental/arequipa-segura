<?php

namespace App\Repositories\Contracts;

use App\Models\ContactoEmergencia;
use Illuminate\Support\Collection;

/** Contrato de acceso a los contactos de emergencia. */
interface ContactoEmergenciaRepositoryInterface
{
    public function todos(): Collection;

    public function activos(): Collection;

    public function crear(array $datos): ContactoEmergencia;

    public function actualizar(ContactoEmergencia $contacto, array $datos): ContactoEmergencia;

    public function eliminar(ContactoEmergencia $contacto): void;
}
