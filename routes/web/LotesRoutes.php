<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LotesController as Lotes; #Usar controllador

Route::middleware(['auth'])->group(function () {
    $path = 'lotes'; #Ruta
    $name = $path; #Nombre de ruta

    Route::get($path . '/{lotesId}', [Lotes::class, 'index'])
        ->name($name . '.index')
        ->middleware('permission:' . $name . '.index');

    /*Route::post($path, [Lotes::class, 'search'])
        ->name($name . '.search')
        ->middleware('permission:' . $name . '.index');*/

    Route::get($path . '/crear', [Lotes::class, 'create'])
        ->name($name . '.create')
        ->middleware('permission:' . $name . '.create');

    Route::post($path . '/store', [Lotes::class, 'store'])
        ->name($name . '.store')
        ->middleware('permission:' . $name . '.create');

    Route::get($path . '/show/{id}', [Lotes::class, 'show'])
        ->name($name . '.show')
        ->middleware('permission:' . $name . '.index');

    Route::get($path . '/edit/{id}', [Lotes::class, 'edit'])
        ->name($name . '.edit')
        ->middleware('permission:' . $name . '.edit');

    Route::post($path . '/update', [Lotes::class, 'update'])
        ->name($name . '.update')
        ->middleware('permission:' . $name . '.edit');

    Route::get($path . '/confirm/{id}', [Lotes::class, 'confirm'])
        ->name($name . '.confirm')
        ->middleware('permission:' . $name . '.delete');

    Route::post($path . '/delete', [Lotes::class, 'destroy'])
        ->name($name . '.delete')
        ->middleware('permission:' . $name . '.delete');

    Route::get($path . '/status/{id}', [Lotes::class, 'status'])
        ->name($name . '.status')
        ->middleware('permission:' . $name . '.status');

    /*Route::get($path . '/servicio/{id}/{descuento}/{precio}', [Lotes::class, 'testCal'])
        ->name($name . '.status')
        ->middleware('permission:' . $name . '.status');*/
});
