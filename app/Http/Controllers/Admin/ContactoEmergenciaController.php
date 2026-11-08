<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactoEmergencia;
use App\Services\EmergenciaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** CRUD de contactos de emergencia (solo administrador). */
class ContactoEmergenciaController extends Controller
{
    public function __construct(private EmergenciaService $emergencias)
    {
    }

    public function index(): View
    {
        return view('admin.contactos.index', [
            'contactos' => $this->emergencias->listar(),
            'etiquetas' => ContactoEmergencia::SITUACIONES,
        ]);
    }

    public function create(): View
    {
        return view('admin.contactos.form', [
            'contacto' => new ContactoEmergencia(['activo' => true]),
            'situaciones' => ContactoEmergencia::SITUACIONES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->emergencias->crear($this->validar($request));

        return redirect()->route('admin.contactos.index')->with('status', 'Contacto creado.');
    }

    public function edit(ContactoEmergencia $contacto): View
    {
        return view('admin.contactos.form', [
            'contacto' => $contacto,
            'situaciones' => ContactoEmergencia::SITUACIONES,
        ]);
    }

    public function update(Request $request, ContactoEmergencia $contacto): RedirectResponse
    {
        $this->emergencias->actualizar($contacto, $this->validar($request));

        return redirect()->route('admin.contactos.index')->with('status', 'Contacto actualizado.');
    }

    public function destroy(ContactoEmergencia $contacto): RedirectResponse
    {
        $this->emergencias->eliminar($contacto);

        return back()->with('status', 'Contacto eliminado.');
    }

    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'situacion' => ['required', Rule::in(array_keys(ContactoEmergencia::SITUACIONES))],
            'servicio' => ['required', 'string', 'max:80'],
            // Solo dígitos y un "+" inicial opcional: se usa en un enlace tel:.
            'telefono' => ['required', 'regex:/^\+?[0-9]{3,15}$/'],
            'instruccion' => ['nullable', 'string', 'max:255'],
        ], [
            'telefono.regex' => 'El teléfono solo puede tener números (y un + al inicio).',
        ]);
        $datos['activo'] = $request->boolean('activo');

        return $datos;
    }
}
