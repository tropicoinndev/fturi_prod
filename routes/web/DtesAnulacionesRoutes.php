<?php

use App\Http\Controllers\AnulacionComprobantesController;
use App\Http\Controllers\DteAnulacionesCajasController;
use App\Http\Controllers\DteAnulacionesController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'permission:admin'])->group(function () {
    $path = 'admin/dte/anulaciones'; #Ruta
    $name = 'dte_anulaciones';         #Nombre de ruta
    Route::get($path . '/solicitudes', [DteAnulacionesController::class, 'index'])
        ->name($name . '.solicitudes');

    Route::post($path . '/solicitudes', [DteAnulacionesController::class, 'search'])
        ->name($name . '.search');

    Route::get($path . '/configurar/{id}', [DteAnulacionesController::class, 'config'])
        ->name($name . '.config');

    Route::post($path . '/store', [DteAnulacionesController::class, 'store'])
        ->name($name . '.store');

    Route::get($path . '/detalles/{id}', [DteAnulacionesController::class, 'show'])
        ->name($name . '.show');

    Route::get($path . '/historia', [DteAnulacionesController::class, 'historia'])
        ->name($name . '.historia');

    Route::post($path . '/historia', [DteAnulacionesController::class, 'historiaSearch'])
        ->name($name . '.historia_search');

    Route::get($path . '/resuelta/{id}', [DteAnulacionesController::class, 'resuelta'])
        ->name($name . '.resuelta');

    Route::get($path . '/comprobante/eliminar/{id}', [DteAnulacionesController::class, 'comprobanteDelete'])
        ->name($name . '.comprobante_eliminar');

    Route::post($path . '/comprobante/destroy', [DteAnulacionesController::class, 'comprobanteDestroy'])
        ->name($name . '.comprobante_destroy');
});

Route::middleware(['auth', 'permission:dte.anulaciones'])->group(function () {
    $path = 'dte/anulaciones/user'; #Ruta
    $name = 'dte_anulaciones.cajas'; #Nombre de ruta
    Route::get($path . '/solicitudes', [DteAnulacionesCajasController::class, 'index'])
        ->name($name . '.solicitudes');

    Route::post($path . '/solicitudes', [DteAnulacionesCajasController::class, 'search'])
        ->name($name . '.search');

    Route::get($path . '/configurar/{id}', [DteAnulacionesCajasController::class, 'config'])
        ->name($name . '.config');

    Route::post($path . '/store', [DteAnulacionesCajasController::class, 'store'])
        ->name($name . '.store');

    Route::get($path . '/detalles/{id}', [DteAnulacionesCajasController::class, 'show'])
        ->name($name . '.show');

    Route::get($path . '/historia', [DteAnulacionesCajasController::class, 'historia'])
        ->name($name . '.historia');

    Route::post($path . '/historia', [DteAnulacionesCajasController::class, 'historiaSearch'])
        ->name($name . '.historia_search');

    Route::get($path . '/resuelta/{id}', [DteAnulacionesCajasController::class, 'resuelta'])
        ->name($name . '.resuelta');

    Route::post('dte/anulacion/comprobantes/update', [AnulacionComprobantesController::class, 'update'])
        ->name('anulacion_comprobantes.editar_tipo');

    Route::get($path . '/comprobante/eliminar/{id}', [DteAnulacionesCajasController::class, 'comprobanteDelete'])
        ->name($name . '.comprobante_eliminar');
});
