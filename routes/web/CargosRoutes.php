<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CargosController as controller; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'cargos'; #Ruta
    $name = $path;      #Nombre de ruta

    Route::get($path, [controller::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [controller::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [controller::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [controller::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [controller::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');


    Route::post($path . '/update', [controller::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [controller::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [controller::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::get($path . '/status/{id}', [controller::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');

    Route::get($path . '/set/iva/{id}', [controller::class, 'setIVA'])
        ->name($name . '.statusServiciosIva')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/set/cesc/{id}', [controller::class, 'setCESC'])
        ->name($name . '.statusServiciosCesc')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/set/propina/{id}', [controller::class, 'setPropina'])
        ->name($name . '.statusServiciosPropina')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/store/cargos/recepcion', [controller::class, 'storeRecepcion'])
        ->name($name . '.store_recepcion')
        ->middleware('permission:' . $path . '.recepcion');

    Route::post($path . '/delete/cargos/recepcion/{id}', [controller::class, 'deleteRecepcion'])
        ->name($name . '.delete_recepcion')
        ->middleware('permission:' . $path . '.recepcion');
});
