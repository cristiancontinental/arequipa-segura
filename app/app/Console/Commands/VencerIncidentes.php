<?php

namespace App\Console\Commands;

use App\Services\VencimientoService;
use Illuminate\Console\Command;

/**
 * Comando programado: marca como vencidos los reportes aprobados cuya
 * vigencia terminó, para que dejen de aparecer en el mapa.
 */
class VencerIncidentes extends Command
{
    protected $signature = 'incidentes:vencer';

    protected $description = 'Marca como vencidos los reportes aprobados cuya vigencia terminó';

    public function handle(VencimientoService $vencimiento): int
    {
        $cantidad = $vencimiento->vencerCaducados();
        $this->info("Reportes vencidos: {$cantidad}");

        return self::SUCCESS;
    }
}
