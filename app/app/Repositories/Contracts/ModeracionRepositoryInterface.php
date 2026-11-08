<?php

namespace App\Repositories\Contracts;

use App\Models\RegistroModeracion;

/** Contrato de acceso al registro de acciones de moderación. */
interface ModeracionRepositoryInterface
{
    public function registrar(array $datos): RegistroModeracion;
}
