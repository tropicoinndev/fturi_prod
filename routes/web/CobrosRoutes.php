<?php

use App\Http\Controllers\CobrosController as model; #Usar controllador
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'caja'])->group(function () {
    $path = 'cobros'; #Ruta
    $name = $path;
    Route::get($path, [model::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [model::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear/{origen}/{origen_id}/{tipo_comprobante}', [model::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/crear/form', [model::class, 'createHab'])
        ->name($name . '.create_form')
        ->middleware('permission:' . $path . '.create');
    Route::get($path . '/configurar/{id}', [model::class, 'create'])
        ->name($name . '.create_config')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/anticipos', [model::class, 'anticipos'])
        ->name($name . '.anticipos')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/anticipos/destroy', [model::class, 'anticiposDestroy'])
        ->name($name . '.anticipos_delete')
        ->middleware('permission:' . $path . '.create');
    Route::post($path . '/anticipos/reserva/destroy', [model::class, 'anticiposReservaDestroy'])
        ->name($name . '.anticipos_reserva_delete')
        ->middleware('permission:anticipo_reserva.create');

    Route::post($path . '/store', [model::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [model::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/comprobante/{id}/{tipo}', [model::class, 'comprobante'])
        ->name($name . '.tipo_comprobante')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [model::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [model::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [model::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::get($path . '/status/{id}', [model::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');
});
