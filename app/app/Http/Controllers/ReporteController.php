<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReporteRequest;
use App\Models\Categoria;
use App\Services\IncidenteService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/** Formulario público para reportar una situación, sin cuenta. */
class ReporteController extends Controller
{
    public function __construct(private IncidenteService $incidentes)
    {
    }

    public function create(): View
    {
        return view('reportar', [
            'categorias' => Categoria::where('activa', true)->orderBy('nombre')->get(),
        ]);
    }

    /** Guarda el reporte como "pendiente"; un moderador lo revisará. */
    public function store(StoreReporteRequest $request): RedirectResponse
    {
        $this->incidentes->registrar(
            $request->safe()->only(['categoria_id', 'descripcion', 'latitud', 'longitud']),
            $request->file('evidencia')
        );

        return redirect()
            ->route('reportes.create')
            ->with('status', 'Tu reporte fue enviado y será revisado antes de publicarse. Gracias por colaborar.');
    }
}
