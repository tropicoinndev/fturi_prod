<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnulacionComprobantesController as Anulaciones; #Usar controllador
use App\Http\Middleware\isLoginCaja;

Route::middleware(['auth', isLoginCaja::class])->group(function () {
    $path = 'anulacion/comprobantes'; #Ruta
    $name = 'anulacion_comprobantes';

    Route::get($path . '/list', [Anulaciones::class, 'list'])
        ->name($name . '.list')
        ->middleware('permission:' . $path . '.index');

    Route::post($path . '/list', [Anulaciones::class, 'list_search'])
        ->name($name . '.list_search')

        ->middleware('permission:' . $path . '.index');
    Route::get($path, [Anulaciones::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [Anulaciones::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear/{id}', [Anulaciones::class, 'create'])
        ->name($name . '.create_by_id')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Anulaciones::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [Anulaciones::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [Anulaciones::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Anulaciones::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [Anulaciones::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');
});
