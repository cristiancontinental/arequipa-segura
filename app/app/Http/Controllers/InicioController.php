<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

/** Pantalla de inicio con las tres opciones principales. */
class InicioController extends Controller
{
    public function __invoke(): View
    {
        return view('inicio');
    }
}
