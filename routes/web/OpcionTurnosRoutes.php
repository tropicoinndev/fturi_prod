<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpcionTurnosController as OpcionTurnos; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'opcion_turnos'; #Ruta
    $name = $path;          #Nombre de ruta

    Route::get($path, [OpcionTurnos::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [OpcionTurnos::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [OpcionTurnos::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [OpcionTurnos::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [OpcionTurnos::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [OpcionTurnos::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [OpcionTurnos::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [OpcionTurnos::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::get($path . '/status/{id}', [OpcionTurnos::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');

    /*Route::post($path . '/api/ciudades', [Municipios::class, 'getByCiudad'])
        ->name($name . '.apiByCiudad');*/
});
