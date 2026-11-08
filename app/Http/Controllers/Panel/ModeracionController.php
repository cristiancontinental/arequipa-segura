<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReporteRequest;
use App\Models\Categoria;
use App\Models\Incidente;
use App\Services\IncidenteService;
use App\Services\ModeracionService;
use DomainException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Panel de moderación: consultar, aprobar, rechazar, editar y eliminar
 * reportes. Las rutas exigen sesión iniciada y rol moderador o administrador.
 */
class ModeracionController extends Controller
{
    public function __construct(
        private IncidenteService $incidentes,
        private ModeracionService $moderacion
    ) {
    }

    /** Lista de reportes; por defecto los pendientes. */
    public function index(Request $request): View
    {
        $estado = $request->query('estado', Incidente::ESTADO_PENDIENTE);

        return view('panel.reportes.index', [
            'reportes' => $this->incidentes->paginar($estado === 'todos' ? null : $estado),
            'estado' => $estado,
            'estados' => Incidente::ESTADOS,
        ]);
    }

    public function edit(Incidente $incidente): View
    {
        $incidente->load(['categoria', 'evidencias', 'registros.moderador']);

        return view('panel.reportes.edit', [
            'incidente' => $incidente,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function update(UpdateReporteRequest $request, Incidente $incidente): RedirectResponse
    {
        $this->incidentes->actualizar($incidente, $request->validated());
        $this->moderacion->registrar($incidente, $request->user(), 'editado');

        return back()->with('status', 'Reporte actualizado.');
    }

    public function aprobar(Request $request, Incidente $incidente): RedirectResponse
    {
        try {
            $this->moderacion->aprobar($incidente, $request->user());
        } catch (DomainException $e) {
            return back()->withErrors(['estado' => $e->getMessage()]);
        }

        return back()->with('status', 'Reporte aprobado: ya aparece en el mapa.');
    }

    public function rechazar(Request $request, Incidente $incidente): RedirectResponse
    {
        $datos = $request->validate(['motivo' => ['nullable', 'string', 'max:255']]);

        try {
            $this->moderacion->rechazar($incidente, $request->user(), $datos['motivo'] ?? null);
        } catch (DomainException $e) {
            return back()->withErrors(['estado' => $e->getMessage()]);
        }

        return back()->with('status', 'Reporte rechazado.');
    }

    public function destroy(Request $request, Incidente $incidente): RedirectResponse
    {
        $this->moderacion->registrar($incidente, $request->user(), 'eliminado', 'Reporte #'.$incidente->id);
        $this->incidentes->eliminar($incidente);

        return redirect()->route('panel.reportes.index')->with('status', 'Reporte eliminado.');
    }
}
