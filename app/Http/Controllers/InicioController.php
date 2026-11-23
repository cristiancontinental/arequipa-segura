<?php

namespace App\Http\Controllers;

use App\Models\ContactoEmergencia;
use App\Services\EmergenciaService;
use Illuminate\Contracts\View\View;

/**
 * Pantalla de inicio con las tres opciones principales y, debajo, botones de
 * llamada rápida (1 toque) para las emergencias más frecuentes.
 */
class InicioController extends Controller
{
    public function __construct(private EmergenciaService $emergencias)
    {
    }

    public function __invoke(): View
    {
        $grupos = $this->emergencias->agrupadosPorSituacion();

                // Un contacto activo por situación, en el orden en que se muestran en pantalla.
        $rapidos = collect(['incendio', 'peligro_taxi', 'violencia_familiar', 'robo_vivienda'])
            ->map(function (string $situacion) use ($grupos) {

                $contacto = $grupos->get($situacion)?->first();

                return $contacto ? ['situacion' => $situacion, 'contacto' => $contacto] : null;
            })
            ->filter()
            ->values();

        return view('inicio', [
            'rapidos' => $rapidos,
            'etiquetas' => ContactoEmergencia::SITUACIONES,
        ]);
    }
}