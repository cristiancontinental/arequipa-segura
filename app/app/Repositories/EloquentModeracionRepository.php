<?php

namespace App\Repositories;

use App\Models\RegistroModeracion;
use App\Repositories\Contracts\ModeracionRepositoryInterface;

/** Implementación Eloquent del repositorio de moderación. */
class EloquentModeracionRepository implements ModeracionRepositoryInterface
{
    public function registrar(array $datos): RegistroModeracion
    {
        return RegistroModeracion::create($datos);
    }
}
