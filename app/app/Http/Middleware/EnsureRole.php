<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Autorización por rol. Se usa en las rutas así:
 *   EnsureRole::class . ':administrador'
 *   EnsureRole::class . ':moderador,administrador'
 *
 * Responde 403 si el usuario no tiene alguno de los roles indicados o si su
 * cuenta está desactivada.
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        if ($usuario === null || $usuario->activo === false || ! in_array($usuario->rol, $roles, true)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        return $next($request);
    }
}
