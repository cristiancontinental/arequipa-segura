<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** CRUD de categorías de reporte (solo administrador). */
class CategoriaController extends Controller
{
    public function index(): View
    {
        return view('admin.categorias.index', [
            'categorias' => Categoria::withCount('incidentes')->orderBy('nombre')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categorias.form', ['categoria' => new Categoria(['activa' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Categoria::create($this->validar($request));

        return redirect()->route('admin.categorias.index')->with('status', 'Categoría creada.');
    }

    public function edit(Categoria $categoria): View
    {
        return view('admin.categorias.form', ['categoria' => $categoria]);
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $categoria->update($this->validar($request, $categoria));

        return redirect()->route('admin.categorias.index')->with('status', 'Categoría actualizada.');
    }

    /** Si la categoría ya tiene reportes se desactiva para conservar la integridad de los datos. */
    public function destroy(Categoria $categoria): RedirectResponse
    {
        if ($categoria->incidentes()->exists()) {
            $categoria->update(['activa' => false]);

            return back()->with('status', 'La categoría tiene reportes asociados, por eso se desactivó en lugar de eliminarse.');
        }

        $categoria->delete();

        return back()->with('status', 'Categoría eliminada.');
    }

    private function validar(Request $request, ?Categoria $categoria = null): array
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:60', Rule::unique('categorias', 'nombre')->ignore($categoria?->id)],
        ]);
        $datos['activa'] = $request->boolean('activa');

        return $datos;
    }
}
