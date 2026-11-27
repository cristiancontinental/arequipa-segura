<?php

use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\ContactoEmergenciaController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContenidoInapropiadoController;
use App\Http\Controllers\EmergenciaController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\MapaController;
use App\Http\Controllers\Panel\EvidenciaController;
use App\Http\Controllers\Panel\ModeracionController;
use App\Http\Controllers\ReporteController;
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas (sin cuenta)
|--------------------------------------------------------------------------
| Los límites de solicitudes (throttle) reducen el envío automatizado de
| contenido abusivo. Todas las rutas POST están protegidas con CSRF.
*/
Route::get('/', InicioController::class)->name('inicio');
Route::get('/mapa', [MapaController::class, 'index'])->name('mapa');
Route::get('/mapa/datos', [MapaController::class, 'datos'])
    ->name('api.alertas');
Route::get('/reportar', [ReporteController::class, 'create'])->name('reportes.create');
Route::post('/reportar', [ReporteController::class, 'store'])->middleware('throttle:5,1')->name('reportes.store');
Route::post('/alertas/{incidente}/inapropiado', [ContenidoInapropiadoController::class, 'store'])
    ->middleware('throttle:5,1')->name('alertas.inapropiado');
Route::get('/emergencias', [EmergenciaController::class, 'index'])->name('emergencias');

/*
|--------------------------------------------------------------------------
| Autenticación de moderadores y administradores
|--------------------------------------------------------------------------
*/
Route::get('/ingresar', [LoginController::class, 'create'])->name('login');
Route::post('/ingresar', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
Route::post('/salir', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Panel de moderación (moderador o administrador)
|--------------------------------------------------------------------------
*/
Route::prefix('panel')->name('panel.')
    ->middleware(['auth', EnsureRole::class.':moderador,administrador'])
    ->group(function () {
        Route::get('reportes', [ModeracionController::class, 'index'])->name('reportes.index');
        Route::get('reportes/{incidente}/editar', [ModeracionController::class, 'edit'])->name('reportes.edit');
        Route::put('reportes/{incidente}', [ModeracionController::class, 'update'])->name('reportes.update');
        Route::post('reportes/{incidente}/aprobar', [ModeracionController::class, 'aprobar'])->name('reportes.aprobar');
        Route::post('reportes/{incidente}/rechazar', [ModeracionController::class, 'rechazar'])->name('reportes.rechazar');
        Route::delete('reportes/{incidente}', [ModeracionController::class, 'destroy'])->name('reportes.destroy');
        Route::get('evidencias/{evidencia}', [EvidenciaController::class, 'show'])->name('evidencias.show');
    });

/*
|--------------------------------------------------------------------------
| Administración (solo administrador)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')
    ->middleware(['auth', EnsureRole::class.':administrador'])
    ->group(function () {
        Route::resource('categorias', CategoriaController::class)->except('show')->parameters(['categorias' => 'categoria']);
        Route::resource('contactos', ContactoEmergenciaController::class)->except('show')->parameters(['contactos' => 'contacto']);
        Route::resource('usuarios', UsuarioController::class)->except('show')->parameters(['usuarios' => 'usuario']);
    });
