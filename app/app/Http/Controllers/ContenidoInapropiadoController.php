<?php

namespace App\Http\Controllers;

use App\Models\Incidente;
use App\Models\ReporteContenido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Permite a cualquier visitante avisar de una alerta publicada que parece inapropiada. */
class ContenidoInapropiadoController extends Controller
{
    public function store(Request $request, Incidente $incidente): RedirectResponse
    {
        // Solo se puede avisar sobre alertas que están publicadas.
        abort_unless($incidente->estado === Incidente::ESTADO_APROBADO, 404);

        $datos = $request->validate([
            'motivo' => ['required', 'string', 'min:5', 'max:255'],
        ]);

        ReporteContenido::create([
            'incidente_id' => $incidente->id,
            'motivo' => $datos['motivo'],
        ]);

        return back()->with('status', 'Gracias. Un moderador revisará esta alerta.');
    }
}
