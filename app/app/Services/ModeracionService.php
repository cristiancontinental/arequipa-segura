<?php

namespace App\Services;

use App\Models\Incidente;
use App\Models\User;
use App\Repositories\Contracts\IncidenteRepositoryInterface;
use App\Repositories\Contracts\ModeracionRepositoryInterface;
use DomainException;

/**
 * Reglas de moderación: aprobar o rechazar reportes y dejar registro de
 * cada acción para tener trazabilidad.
 */
class ModeracionService
{
    public function __construct(
        private IncidenteRepositoryInterface $incidentes,
        private ModeracionRepositoryInterface $moderacion
    ) {
    }

    /**
     * Aprueba un reporte pendiente o rechazado y fija su vencimiento
     * (7 días por defecto).
     *
     * @throws DomainException si el reporte no puede aprobarse.
     */
    public function aprobar(Incidente $incidente, User $moderador): Incidente
    {
        if (! in_array($incidente->estado, [Incidente::ESTADO_PENDIENTE, Incidente::ESTADO_RECHAZADO], true)) {
            throw new DomainException('Solo se pueden aprobar reportes pendientes o rechazados.');
        }

        $dias = (int) config('arequipa.vigencia_dias', 7);

        $incidente = $this->incidentes->actualizar($incidente, [
            'estado' => Incidente::ESTADO_APROBADO,
            'aprobado_en' => now(),
            'vence_en' => now()->addDays($dias),
        ]);

        $this->registrar($incidente, $moderador, 'aprobado');

        return $incidente;
    }

    /**
     * Rechaza un reporte pendiente o retira uno aprobado.
     *
     * @throws DomainException si el reporte no puede rechazarse.
     */
    public function rechazar(Incidente $incidente, User $moderador, ?string $motivo = null): Incidente
    {
        if (! in_array($incidente->estado, [Incidente::ESTADO_PENDIENTE, Incidente::ESTADO_APROBADO], true)) {
            throw new DomainException('Solo se pueden rechazar reportes pendientes o aprobados.');
        }

        $incidente = $this->incidentes->actualizar($incidente, [
            'estado' => Incidente::ESTADO_RECHAZADO,
            'aprobado_en' => null,
            'vence_en' => null,
        ]);

        $this->registrar($incidente, $moderador, 'rechazado', $motivo);

        return $incidente;
    }

    /** Anota una acción de moderación (aprobado, rechazado, editado, eliminado). */
    public function registrar(Incidente $incidente, User $moderador, string $accion, ?string $motivo = null): void
    {
        $this->moderacion->registrar([
            'incidente_id' => $incidente->id,
            'moderador_id' => $moderador->id,
            'accion' => $accion,
            'motivo' => $motivo,
        ]);
    }
}
