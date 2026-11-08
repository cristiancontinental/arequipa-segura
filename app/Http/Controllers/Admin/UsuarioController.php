<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** CRUD de moderadores y administradores (solo administrador). */
class UsuarioController extends Controller
{
    public function index(): View
    {
        return view('admin.usuarios.index', ['usuarios' => User::orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.usuarios.form', ['usuario' => new User(['rol' => User::ROL_MODERADOR, 'activo' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        User::create($this->validar($request));

        return redirect()->route('admin.usuarios.index')->with('status', 'Usuario creado.');
    }

    public function edit(User $usuario): View
    {
        return view('admin.usuarios.form', ['usuario' => $usuario]);
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $datos = $this->validar($request, $usuario);

        // Evita que el administrador se quite sus propios permisos por error.
        if ($usuario->is($request->user()) && ($datos['rol'] !== User::ROL_ADMINISTRADOR || ! $datos['activo'])) {
            return back()->withErrors(['rol' => 'No puedes quitarte el rol de administrador ni desactivar tu propia cuenta.'])->withInput();
        }

        $usuario->update($datos);

        return redirect()->route('admin.usuarios.index')->with('status', 'Usuario actualizado.');
    }

    public function destroy(Request $request, User $usuario): RedirectResponse
    {
        if ($usuario->is($request->user())) {
            return back()->withErrors(['usuario' => 'No puedes eliminar tu propia cuenta.']);
        }

        $usuario->delete();

        return back()->with('status', 'Usuario eliminado.');
    }

    /** La contraseña es obligatoria al crear y opcional al editar. */
    private function validar(Request $request, ?User $usuario = null): array
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($usuario?->id)],
            'rol' => ['required', Rule::in([User::ROL_MODERADOR, User::ROL_ADMINISTRADOR])],
            'password' => [$usuario ? 'nullable' : 'required', 'string', 'min:8'],
        ]);
        $datos['activo'] = $request->boolean('activo');

        if (empty($datos['password'])) {
            unset($datos['password']);
        }

        return $datos;
    }
}
