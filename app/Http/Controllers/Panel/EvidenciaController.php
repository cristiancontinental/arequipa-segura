<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Evidencia;
use App\Services\EvidenciaService;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Entrega las fotografías privadas solo a usuarios autorizados (lo exige la ruta). */
class EvidenciaController extends Controller
{
    public function show(Evidencia $evidencia, EvidenciaService $servicio): StreamedResponse
    {
        return $servicio->respuesta($evidencia);
    }
}
