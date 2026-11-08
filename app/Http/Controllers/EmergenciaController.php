<?php

namespace App\Http\Controllers;

use App\Models\ContactoEmergencia;
use App\Services\EmergenciaService;
use Illuminate\Contracts\View\View;

/** Enlace de Emergencia Crítica: botones de llamada y SOS. No publica nada en el mapa. */
class EmergenciaController extends Controller
{
    public function __construct(private EmergenciaService $emergencias)
    {
    }

    public function index(): View
    {
        return view('emergencias', [
            'grupos' => $this->emergencias->agrupadosPorSituacion(),
            'etiquetas' => ContactoEmergencia::SITUACIONES,
        ]);
    }
}
