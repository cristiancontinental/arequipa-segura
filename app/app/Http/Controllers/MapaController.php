<?php

namespace App\Http\Controllers;

use App\Services\IncidenteService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

/** Mapa Preventivo: vista pública con las alertas aprobadas y vigentes. */
class MapaController extends Controller
{
    public function __construct(private IncidenteService $incidentes)
    {
    }

    /** Página del mapa y lista de alertas (alternativa accesible al mapa). */
    public function index(): View
    {
        return view('mapa', ['alertas' => $this->incidentes->vigentes()]);
    }

    /** Datos JSON del mapa de calor: solo coordenadas aproximadas y categoría. */
    public function datos(): JsonResponse
    {
        return response()->json($this->incidentes->puntosParaMapa());
    }
}
