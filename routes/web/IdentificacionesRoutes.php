<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdentificacionesController as Identificaciones; #Usar controlador

Route::middleware(['auth'])->group(function () {


    $path = 'identificaciones'; #Ruta
    $name = $path;             #Nombre de ruta

    Route::get($path, [Identificaciones::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $path . '.index');

    Route::post($path, [Identificaciones::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $path . '.index');

    Route::get($path . '/crear', [Identificaciones::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $path . '.create');

    Route::post($path . '/store', [Identificaciones::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $path . '.create');

    Route::get($path . '/edit/{id}', [Identificaciones::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $path . '.edit');

    Route::post($path . '/update', [Identificaciones::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $path . '.edit');

    Route::get($path . '/confirm/{id}', [Identificaciones::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $path . '.delete');

    Route::post($path . '/delete', [Identificaciones::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $path . '.delete');

    Route::get($path . '/status/{id}', [Identificaciones::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $path . '.status');

    Route::get($path . '/api/get/identificaciones', [Identificaciones::class, 'apiGetIdentificaciones'])
        ->name($name . '.apiGetIdentificaciones')
        ->middleware('permission:' . $path . '.apiGetIdentificaciones');
});
