<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Inicio y cierre de sesión de moderadores y administradores. */
class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    /** Valida las credenciales; solo entran cuentas activas. */
    public function store(Request $request): RedirectResponse
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(['email' => $credenciales['email'], 'password' => $credenciales['password'], 'activo' => true])) {
            // Evita la fijación de sesión.
            $request->session()->regenerate();

            return redirect()->intended(route('panel.reportes.index'));
        }

        return back()
            ->withErrors(['email' => 'Las credenciales no son válidas o la cuenta está desactivada.'])
            ->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }
}
