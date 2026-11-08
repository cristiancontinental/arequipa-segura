<?php

namespace App\Providers;

use App\Repositories\Contracts\ContactoEmergenciaRepositoryInterface;
use App\Repositories\Contracts\EvidenciaRepositoryInterface;
use App\Repositories\Contracts\IncidenteRepositoryInterface;
use App\Repositories\Contracts\ModeracionRepositoryInterface;
use App\Repositories\EloquentContactoEmergenciaRepository;
use App\Repositories\EloquentEvidenciaRepository;
use App\Repositories\EloquentIncidenteRepository;
use App\Repositories\EloquentModeracionRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Registra en el contenedor de servicios de Laravel qué implementación
 * concreta corresponde a cada contrato de repositorio.
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(IncidenteRepositoryInterface::class, EloquentIncidenteRepository::class);
        $this->app->bind(EvidenciaRepositoryInterface::class, EloquentEvidenciaRepository::class);
        $this->app->bind(ModeracionRepositoryInterface::class, EloquentModeracionRepository::class);
        $this->app->bind(ContactoEmergenciaRepositoryInterface::class, EloquentContactoEmergenciaRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
