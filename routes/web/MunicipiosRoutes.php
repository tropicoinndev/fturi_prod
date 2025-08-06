<?php

use App\Http\Controllers\MunicipiosController as Municipios; #Usar controllador
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    $path = 'municipios'; #Ruta
    $name = $path;       #Nombre de ruta

    Route::get($path, [Municipios::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [Municipios::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [Municipios::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Municipios::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');



    Route::get($path . '/edit/{id}', [Municipios::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [Municipios::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Municipios::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [Municipios::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/api/ciudades', [Municipios::class, 'getByCiudad'])
        ->name($name . '.apiByCiudad');
    Route::post($path . '/api/paises', [Municipios::class, 'getByPais'])
        ->name($name . '.apiByPais');
});
